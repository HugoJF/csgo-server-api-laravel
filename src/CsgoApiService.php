<?php

namespace hugojf\CsgoServerApi;

/**
 * Delete this folder and have fun
 * creating your package.
 */
class CsgoApiService
{
	public static function all()
	{
		$api = new CsgoApi();

		return $api->broadcast();
	}

	public static function to($server)
	{
		$api = new CsgoApi();

		return $api->to($server);
	}
}
