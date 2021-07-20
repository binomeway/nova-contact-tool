<?php


namespace BinomeWay\NovaContactTool\Settings;


use BinomeWay\NovaContactTool\Contracts\SettingsPage;
use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;

class MailSettings implements SettingsPage
{
    // TODO: Make it extendable

    public const DEFAULT_TO = 'mail_default_to';
    public const DEFAULT_SUBJECT = 'mail_default_subject';
    public const DEFAULT_FROM = 'mail_default_from';
    public const SAVE_MESSAGES = 'mail_save_messages';
    public const SAVE_SUBSCRIBERS = 'mail_save_subscribers';
    public const PRIORITY = 'mail_priority';

    public function fields(): array
    {
        return [
            Tabs::make(__('Mail Settings'), [
                Panel::make(__('Default Text'), [
                    Text::make(__('Default To Address'), self::DEFAULT_TO)
                        ->required()
                        ->rules('email')
                        ->help(__('Set where the messages should be delivered to.')),

                    Text::make(__('Default From Address'), self::DEFAULT_SUBJECT)
                        ->nullable(),

                    Text::make(__('Default Subject'), self::DEFAULT_SUBJECT)
                        ->required()
                        ->default('New Contact Message | {APP_NAME}')
                        ->help(self::formatSubjectHelpText()),
                ]),

                Panel::make(__('Options'), [
                    Boolean::make(__('Save Messages'), self::SAVE_MESSAGES)
                        ->default(true)
                        ->help(__('Saves a copy of the mail into the database')),

                    Boolean::make(__('Save Subscribers'), self::SAVE_SUBSCRIBERS)
                        ->default(true)
                        ->help(__('Saves the user data as a subscriber into the database.')),

                    Number::make(__('Priority'), self::PRIORITY)
                        ->default(3)
                        ->placeholder(3)
                        ->help(__('Set the priority of emails. From 1 being the highest to 5 being the lowest. Default is 3.')),
                ]),
            ])
        ];
    }


    private static function formatSubjectHelpText(): string
    {
        // TODO: Load view instead of this
        $vars = [
            '{APP_NAME}', '{SENDER_EMAIL}', '{SENDER_NAME}', '{SENDER_PHONE}'
        ];
        $help = __('The default subject for emails.');
        $html = "<p>$help</p>";
        $list = collect($vars)->map(fn($var) => "<li>{$var}</li>")->implode(' ');
        $html .= "<p>Variables:</p> <ul>$list</ul>";
        return $html;
    }

    public function name(): string
    {
        return __('Mail');
    }

    public function options(): array
    {
        return [
            self::DEFAULT_TO,
            self::DEFAULT_SUBJECT,
            self::DEFAULT_FROM,
            self::SAVE_MESSAGES,
            self::SAVE_SUBSCRIBERS,
            self::PRIORITY,
        ];
    }

    public function casts(): array
    {
        return [];
    }
}
