<?php

namespace hugojf\CsgoServerApi\Classes\Summaries;

use hugojf\CsgoServerApi\Contracts\Summary;

class ByCommandSummary implements Summary
{
	protected $servers = [];

	public function attach($command, $server, $response)
	{
		$this->servers[ (string) $command ][ (string) $server ] = $response;
	}

	public function getSummary()
	{
		return $this->servers;
	}
}