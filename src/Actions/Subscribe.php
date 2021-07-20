<?php


namespace BinomeWay\NovaContactTool\Actions;

use BinomeWay\NovaContactTool\Services\Contact;
use BinomeWay\NovaContactTool\Models\Subscriber;
use Illuminate\Support\Facades\Validator;

class Subscribe
{
    /**
     * @var Contact
     */
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @param array $input
     * @return Subscriber
     * @throws \Illuminate\Validation\ValidationException
     */
    public function run(array $input = []): Subscriber
    {
        $data = $this->validate($input);

       return $this->contact->subscribe(
            $data['email']
        );
    }

    /**
     * @param array $input
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input): array
    {
        return Validator::make($input, [
            'email' => 'required|email',
        ])->validated();
    }
}
