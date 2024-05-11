# Sineld - OneSignal Mail for Laravel

This is a wrapper for OneSignal Email for Laravel.
You can send e-mails in your project just like you usually do with Laravel's native mailers, the package makes sure the e-mails are send via the OneSignal Mail API using your OneSignal Email account.

## Requires
Workes with Laravel version 9.0, 10.00, 11.0 or higher.

### Installation ###

* Step 1: Install package via composer.

```bash
composer require sineld/onesignal-mail:dev-main
```

* Step 2: Add your account and API keys to your **.env file**.
```
ONESIGNAL_MAIL_URL=https://onesignal.com/api/v1/notifications
ONESIGNAL_MAIL_API=<Your API KEY>
ONESIGNAL_MAIL_APP_ID=<Your APP ID>
```

* Step 3: Update **MAIL_MAILER** with 'onesignal-mail' in your **.env file**.
```
MAIL_MAILER=onesignal-mail
```

* Step 4: Add this new mailer to your **config/mail.php*** file.
```php
'mailers' => [
    ...
    'onesignal-mail' => [
        'transport' => 'onesignal-mail',
        'api_url' => env('ONESIGNAL_MAIL_URL'),
        'api_key' => env('ONESIGNAL_MAIL_API'),
        'app_id' => env('ONESIGNAL_MAIL_APP_ID'),
    ],
    ...
],
```

* Step 5: In your **config/app.php** (Laravel 11+ **bootstrap/providers.php**) file go to your providers array and add the following package provider:
```php
'providers' => [
    /*
     * Laravel Framework Service Providers...
     */
    ...
      Sineld\OneSignalMail\OneSignalMailServiceProvider::class
    ...
],
```

### Usage ###
```php
// Create **contact.blade.php** file under **resources/views** folder with this content:
<p>Hello {{ $name }}, ({{ $email }})</p>

<p>{{ $subject }}</p>

<p>{{ $body }}</p>

// Send your email
$data = [
    'name' => 'Recieptant Name',
    'email' => 'recieptant-name@gmail.com',
    'subject' => 'Hello World!',
    'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
];

Mail::send('contact', $data, function ($mail) use ($data) {
    $mail
        ->to(
            $data['email'],
            $data['name']
        )
        ->subject($data['subject'])
        // ->replyTo('support@your-company.com')
        ;
});

// or use mailables!
```

Read Laravel's documentation on how to send E-mails with the Laravel Framework.

https://laravel.com/docs/master/mail
