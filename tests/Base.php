<?php

namespace hugojf\CsgoServerApi\Tests;

use hugojf\CsgoServerApi\Providers\PackageServiceProvider;
use Ixudra\Curl\CurlServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class Base extends OrchestraTestCase
{
	protected function getPackageProviders($app)
	{
		return [
			PackageServiceProvider::class,
			CurlServiceProvider::class,
			PackageServiceProvider::class,
		];
	}
}