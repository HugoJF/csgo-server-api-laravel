<?php

namespace hugojf\CsgoServerApi    \Providers;

use hugojf\CsgoServerApi\CsgoApi;
use hugojf\CsgoServerApi\CsgoApiService;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
	/**
	 * Register bindings in the container.
	 */
	public function register()
	{
		$this->app->singleton('CsgoApi', function () {
			return new CsgoApiService();
		});
	}

	/**
	 * Perform post-registration booting of services.
	 */
	public function boot()
	{
		// Publish config files
		$this->publishes([
			__DIR__ . '/../config/config.php' => config_path('csgo-api.php'),
		]);
	}

	/**
	 * @return array
	 */
	public function provides()
	{
		return ['CsgoApi'];
	}
}
