<?php

namespace hugojf\Tests;

use hugojf\CsgoServerApi\Facades\CsgoApi;
use hugojf\CsgoServerApi    \Providers\PackageServiceProvider;
use Ixudra\Curl\CurlServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class ExecTest extends OrchestraTestCase
{
	protected function getPackageProviders($app)
	{
		return [PackageServiceProvider::class, CurlServiceProvider::class];
	}

	/** @test */
	public function testRun()
	{
		dd(CsgoApi::to('170.81.43.200:27008')->commands([
			['echo a', 1],
		])->send());

		$this->assertTrue(true);
	}
}

/*
 *
 *
 * settings
 * welcome message
 * messages
 *
 * 	"Settings": {
 * 		"Enabled": {
 * 			"Component": "Checkbox",
 * 			"Default": "1",
 * 		},
 * 		"Prefix": {
 * 			"Component": "ColorInput",
 * 			"Default": "{darkred}[AUTO]",
 * 		}
 * 	},
 *
 * 	"Messages": [
 * 		{
 * 			"Enabled": {
 * 				"Component": "Checkbox",
 * 				"Default": "1",
 *	 		},
 * 			"Message": {
* 				}
 * 		}
 * 	]
 *
 *
 *
 *
 *
 */