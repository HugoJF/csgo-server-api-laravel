<?php

namespace hugojf\CsgoServerApi\Contracts;

interface Summary
{
	public function attach($command, $server, string $response);

	public function getSummary();
}