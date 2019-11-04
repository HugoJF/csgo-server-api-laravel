<?php

namespace hugojf\CsgoServerApi;

use hugojf\CsgoServerApi\Classes\Senders\BroadcastSender;
use hugojf\CsgoServerApi\Classes\Senders\DirectSender;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;

/**
 * Delete this folder and have fun
 * creating your package.
 */
class CsgoApiService
{
	public static function all($summaryClass = ByServerSummary::class)
	{
		return static::broadcast($summaryClass);
	}

	public static function broadcast($summaryClass = ByServerSummary::class)
	{
		return new BroadcastSender($summaryClass);
	}

	public static function to($summaryClass = ByServerSummary::class)
	{
		return static::direct($summaryClass);
	}

	public static function direct($summaryClass = ByServerSummary::class)
	{
		return new DirectSender($summaryClass);
	}
}
