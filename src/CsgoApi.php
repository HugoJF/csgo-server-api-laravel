<?php

namespace hugojf\CsgoServerApi;

use hugojf\CsgoServerApi\Classes\Senders\BroadcastSender;
use hugojf\CsgoServerApi\Classes\Senders\DirectSender;

class CsgoApi
{
	public function broadcast()
	{
		return new BroadcastSender();
	}

	public function direct()
	{
		return new DirectSender();
	}
}