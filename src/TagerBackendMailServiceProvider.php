<?php

namespace OZiTAG\Tager\Backend\Mail;

use Illuminate\Support\ServiceProvider;
use OZiTAG\Tager\Backend\Mail\Commands\FlushMailTemplatesCommand;

class TagerBackendMailServiceProvider extends ServiceProvider
{
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
            ]);
        }
    }
}
