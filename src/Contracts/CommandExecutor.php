<?php

namespace hugojf\CsgoServerApi\Contracts;

use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Server;

interface CommandExecutor
{
	public function __construct(Summary $summary = null);

	public function batch(array $commands, array $servers);

	public function execute(Command $command, Server $server);
}