<?php

namespace hugojf\CsgoServerApi;

use hugojf\CsgoServerApi\Classes\Senders\BroadcastSender;
use hugojf\CsgoServerApi\Classes\Senders\DirectSender;

/**
 * Delete this folder and have fun
 * creating your package.
 */
class CsgoApiService
{
	public static function broadcast()
	{
		return new BroadcastSender();
	}

	public static function direct()
	{
		return new DirectSender();
	}
}
