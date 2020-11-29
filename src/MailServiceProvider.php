<?php

namespace OZiTAG\Tager\Backend\Mail;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use OZiTAG\Tager\Backend\Mail\Console\FlushMailTemplatesCommand;
use OZiTAG\Tager\Backend\Mail\Console\ResendSkipMailCommand;
use OZiTAG\Tager\Backend\Mail\Enums\MailScope;
use OZiTAG\Tager\Backend\Mail\Events\MessageSentHandler;
use OZiTAG\Tager\Backend\Rbac\TagerScopes;

class MailServiceProvider extends EventServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            MessageSentHandler::class
        ],
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('tager-mail', function () {
            return new TagerMail();
        });

        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

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

        TagerScopes::registerGroup('Mail', [
            MailScope::EditTemplates => 'Edit templates',
            MailScope::ViewTemplates => 'View templates',
            MailScope::ViewLogs => 'View logs',
        ]);

        parent::boot();
    }
}
