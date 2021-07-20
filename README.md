# Contact

[![Latest Version on Packagist](https://img.shields.io/packagist/v/binomeway/nova-contact-tool.svg?style=flat-square)](https://packagist.org/packages/binomedev/contact)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/binomeway/nova-contact-tool/run-tests?label=tests)](https://github.com/binomedev/contact/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/binomeway/nova-contact-tool.svg?style=flat-square)](https://packagist.org/packages/binomedev/contact)

A package to manage contact information such as: email, phone, socials and forms.

## Installation

You can install the package via composer:

```bash
composer require binomeway/nova-contact-tool
```

Install nova-settings

```bash
composer require optimistdigital/nova-settings
```

Add the following line to the boot method within the NovaServiceProvider.php in order to be able to modify contact data
withing Nova.

```php
// NovaServiceProvider.php 
public function tools()
{
    return [
        \OptimistDigital\NovaSettings\NovaSettings\NovaSettings::make(),
        \BinomeWay\NovaContactTool\NovaContactTool\NovaContactTool::make(),
    ];
}
```

You have to publish and run the migrations

```bash
php artisan vendor:publish --provider="BinomeWay\NovaContactTool\ToolServiceProvider" --tag="nova-contact-tool-migrations"
php artisan migrate
```

You can publish the config file with if needed.

```bash
php artisan vendor:publish --provider="BinomeWay\NovaContactTool\ToolServiceProvider" --tag="nova-contact-tool-config"
```

This is the contents of the published config file:

```php
return [
    'default_to' => '',
    'default_subject' => '',
    'save_messages' => true,
    'save_subscribers' => true,
    'priority' => 3,
    'delete_on_unsubscribe' => false,
    'enable_gmail_api' => env('ENABLE_GMAIL_API', false),
];
```

## Usage

### Subscribers

Making a new subscriber.

```php
use BinomeWay\NovaContactTool\Facades\Contact;

$subscriber = Contact::subscribe($email, $name, $phone);
```

```php
use BinomeWay\NovaContactTool\Facades\Contact;

Contact::unsubscribe($subscriber);
```

### Sending Mails

Send emails easy as that.

```php
use BinomeWay\NovaContactTool\Facades\Contact;

Contact::send($message, $subscriber);
```

Example

```php
use BinomeWay\NovaContactTool\Facades\Contact;
use BinomeWay\NovaContactTool\Models\Subscriber;
// Perform validations
// Create a subscriber
$subscriber = new Subscriber();
// or use the subscribe method
$subscriber = Contact::subscribe($email, $name, $phone);

$message = 'Hello!';
$to = 'hello@binomeway.com'; // Optional

$contact = Contact::send($message, $subscriber, $to);

// Checking if the mail was sent.

if($contact->hasSucceded()){
     flash()->message('success', 'Yay! Email was sent.');
}

if($contact->hasFailed()){
    flash()->message('error', $contact->errorMessage());
}

```

### Sending using Gmail API

First you need to enable the Gmail API features.

```dotenv
ENABLE_GMAIL_API=true
```

Further, add the credentials in the `.env` file.

```dotenv
GOOGLE_PROJECT_ID=
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

Or use a JSON credentials file by adding it to the path: `storage/gmail/tokens/gmail-json.json`

Adding more options

```dotenv
GOOGLE_ALLOW_MULTIPLE_CREDENTIALS=true
GOOGLE_ALLOW_JSON_ENCRYPT=true
```

Next step is to authorize a Gmail Account by using the admin panel. 

That's it. Now you can use the same `send` method, and it will work.

*Note: The authorization login won't work on localhost. Gmail doesn't allow local domains*



## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Codrin Axinte](https://github.com/codrin-axinte)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
