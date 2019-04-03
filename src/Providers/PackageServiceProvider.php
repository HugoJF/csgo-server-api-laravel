<?php

namespace hugojf\CsgoApi\Providers;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
		// Publish config files
		$this->publishes([
			__DIR__.'/../config/config.php' => config_path('csgo-api.php'),
		]);
    }
}
