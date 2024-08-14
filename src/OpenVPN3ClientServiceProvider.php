<?php

namespace Goudenvis\OpenVPN3Client;

use Goudenvis\OpenVPN3Client\Console\RemoveConfigCommand;
use Illuminate\Support\ServiceProvider;
use Goudenvis\OpenVPN3Client\Console\AddConfigCommand;

class OpenVPN3ClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddConfigCommand::class,
                RemoveConfigCommand::class,
            ]);
        }
    }
}
