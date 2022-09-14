<?php

namespace Helte\HermesSdk\Providers;

use Helte\HermesSdk\Console\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class HermesProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hermes.php' => config_path('hermes.php'),
        ], 'hermes-config');

        $this->commands([
            InstallCommand::class,
        ]);
    }
}