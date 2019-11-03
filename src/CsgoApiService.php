<?php

namespace hugojf\CsgoServerApi;

/**
 * Delete this folder and have fun
 * creating your package.
 */
class CsgoApiService
{
	public static function broadcast()
	{
		return (new CsgoApi())->broadcast();
	}

	public static function direct()
	{
		return (new CsgoApi())->direct();
	}
}
