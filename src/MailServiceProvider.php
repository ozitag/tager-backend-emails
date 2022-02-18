<?php

namespace OZiTAG\Tager\Backend\Mail;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use OZiTAG\Tager\Backend\Mail\Console\FlushMailTemplatesCommand;
use OZiTAG\Tager\Backend\Mail\Console\ResendSkipMailCommand;
use OZiTAG\Tager\Backend\Mail\Console\TestMailCommand;
use OZiTAG\Tager\Backend\Mail\Enums\MailScope;
use OZiTAG\Tager\Backend\Mail\Events\MessageSentHandler;
use OZiTAG\Tager\Backend\Mail\Transports\SendPulseTransport;
use OZiTAG\Tager\Backend\Mail\Transports\TransportFactory;
use OZiTAG\Tager\Backend\Rbac\TagerScopes;
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;
use Symfony\Component\Mailer\Bridge\Mailchimp\Transport\MandrillApiTransport;

class MailServiceProvider extends EventServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            MessageSentHandler::class
        ],
    ];


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tager-mail');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('tager-mail.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                FlushMailTemplatesCommand::class,
                ResendSkipMailCommand::class
            ]);
        }

        TagerScopes::registerGroup(__('tager-mail::scopes.group'), [
            MailScope::ViewTemplates->value => __('tager-mail::scopes.view_templates'),
            MailScope::EditTemplates->value => __('tager-mail::scopes.edit_templates'),
            MailScope::ViewLogs->value => __('tager-mail::scopes.view_logs')
        ]);

        Mail::extend('sendpulse', function () {
            $config = $this->app['config']->get('services.sendpulse', []);

            $SPApiClient = new ApiClient(
                $config['user_id'],
                $config['user_secret'],
                new FileStorage(storage_path('/'))
            );

            return new SendPulseTransport($SPApiClient);
        });

        Mail::extend('mandrill', function () {
            $config = $this->app['config']->get('services.mandrill', []);

            if (!isset($config['apiKey'])) {
                throw new \Exception('Mandrill API Key is not set');
            }

            return new MandrillApiTransport($config['apiKey']);
        });

        parent::boot();
    }
}
