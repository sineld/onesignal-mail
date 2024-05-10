<?php

namespace Sineld\OneSignalMail;

use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class OneSignalMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->afterResolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('onesignal-mail', function () {
                $config = $this->app['config']->get('mail.mailers.onesignal-mail', []);

                return new OneSignalTransport(
                    $config['api_url'],
                    $config['api_key'],
                    $config['app_id'],
                );
            });
        });
    }
}
