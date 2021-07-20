<?php

namespace BinomeWay\NovaContactTool\Services;

use BinomeWay\NovaContactTool\Contracts\MailMessage;
use BinomeWay\NovaContactTool\Events\MessageSent;
use BinomeWay\NovaContactTool\Mail\ContactMessage;
use BinomeWay\NovaContactTool\Models\Message;
use BinomeWay\NovaContactTool\Models\Subscriber;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class Contact
{
    private Settings $settings;

    private ?string $errormessage = null;

    /**
     * Contact constructor.
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function settings(): Settings
    {
        return $this->settings;
    }

    public function send($message, Subscriber $subscriber, $to = null): Contact
    {
        // TODO: Add support for multiple recipients
        // Where this message will be sent
        $recipient = $to ?? $this->settings->getDefaultRecipient();

        $subject = $this->getSubject($subscriber);

        // Get the template to be used
        $mailable = $this->makeMailable($subscriber, $message, $subject);

        try {
            // Send the email
            if (LaravelGmail::check()) {
                $this->sendWithGmailApi($recipient, $mailable);
            } else {
                $this->sendWithDefault($recipient, $mailable);
            }

            // Do we want to record messages into the database?
            if ($this->settings->isSavingMessages()) {
                $this->saveMailable($mailable, $recipient);
            }

            // Fire an event that a message was sent.
            event(new MessageSent($subscriber, $mailable));

            // Inform the user that the message was sent successfully.

        } catch (\Exception $exception) {
            // Inform the user that the message was not sent.
            $this->errormessage = $exception->getMessage();
        }

        return $this;
    }

    private function getSubject(Subscriber $subscriber): string
    {
        $vars = [
            '{APP_NAME}' => config('app.name'),
            '{SENDER_EMAIL}' => $subscriber->email,
            '{SENDER_NAME}' => $subscriber->name,
            '{SENDER_PHONE}' => $subscriber->phone,
        ];

        $subject = $this->settings->getDefaultSubject();

        foreach ($vars as $search => $replace) {
            $subject = str_replace($search, $replace, $subject);
        }

        return $subject;
    }

    private function makeMailable(Subscriber $subscriber, string $message, string $subject): Mailable|MailMessage
    {
        $mailable = new ContactMessage($subscriber, $message, $subject);

        $mailable->from($this->settings->getFromAddress());
        $mailable->priority($this->settings->getPriority());

        return $mailable;
    }

    private function sendWithGmailApi(string $recipient, Mailable|MailMessage $mailable)
    {
        $mail = new \Dacastro4\LaravelGmail\Services\Message\Mail();
        $mail->to($recipient);
        $mail->from($mailable->getSubscriber()->email, $mailable->getSubscriber()->name);
        $mail->subject($mailable->getSubject());
        $mail->priority($this->settings->getPriority());
        $mail->message($mailable->render());
        $mail->send();
    }

    private function sendWithDefault(string|array $recipient, Mailable $mailable)
    {
        Mail::to($recipient)->send($mailable);
    }

    /**
     * Stores a mailable into the database
     *
     * @param MailMessage $mailable
     * @param string $recipient
     * @return mixed
     */
    public function saveMailable(MailMessage $mailable, string $recipient): mixed
    {
        return Message::create([
            'subscriber_id' => $mailable->getSubscriber()->id,
            'from' => $this->settings->getFromAddress(),
            'to' => $recipient,
            'content' => $mailable->getMessage(),
            'subject' => $mailable->getSubject(),
        ]);
    }

    public function hasSucceeded(): bool
    {
        return empty($this->errormessage);
    }

    public function hasFailed(): bool
    {
        return !empty($this->errormessage);
    }

    public function errorMessage(): ?string
    {
        return $this->errormessage;
    }

    /**
     * Add a new subscriber if there isn't one, otherwise just retrieve it.
     *
     * @param $email
     * @param null $name
     * @param null $phone
     * @param array $data
     * @return mixed
     */
    public function subscribe(string $email, string $name = null, string $phone = null, array $data = []): Subscriber
    {
        $name = $name ?? explode('@', $email)[0];

        $ip = request()->getClientIp();
        $agent = request()->userAgent();

        $subscriber = Subscriber::firstOrNew(
            compact('email'),
            compact('name', 'phone', 'data', 'ip', 'agent')
        );

        // Check if the subscriber is set to inactive, if so then set it active again.
        if (!$subscriber->active) {
            $subscriber->active = true;
        }

        if ($this->settings->isSavingSubscribers()) {
            $subscriber->save();
        }

        return $subscriber;
    }

    public function unsubscribeUrl(Subscriber $subscriber): string
    {
        return URL::signedRoute('nova-contact-tool.unsubscribe', ['subscriber' => $subscriber]);
    }

    /**
     * If the subscriber is active, set him as inactive.
     *
     * @param Subscriber $subscriber
     */
    public function unsubscribe(Subscriber $subscriber)
    {
        if ($subscriber->active) {
            $subscriber->active = false;
            $subscriber->save();
        }
    }
}
