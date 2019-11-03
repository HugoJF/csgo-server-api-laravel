<?php

namespace hugojf\CsgoServerApi\Classes\Summaries;

use hugojf\CsgoServerApi\Contracts\Summary;

class ByServerSummary implements Summary
{
	protected $servers = [];

	public function attach($command, $server, $response)
	{
		$this->servers[ (string) $server ][ (string) $command ] = $response;
	}

	public function getSummary()
	{
		return $this->servers;
	}
}