<?php

namespace hugojf\CsgoServerApi\Classes\Senders;

use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\Classes\Summaries\ByCommandSummary;
use hugojf\CsgoServerApi\Traits\HandlesCommandList;
use hugojf\CsgoServerApi\Traits\HandlesServerList;

class DirectSender extends BaseSender
{
	use HandlesCommandList;
	use HandlesServerList;

	public function __construct($summaryClass = ByCommandSummary::class)
	{
		parent::__construct($summaryClass);

		$this->bootCommandList();
		$this->bootServerList();
	}

	public function send()
	{
		foreach ($this->commands->getList() as $command) {
			$this->executeOnServers($command);
		}

		return $this->summary->getSummary();
	}

	protected function executeOnServers(Command $command)
	{
		foreach ($this->servers->getList() as $server) {
			$response = $this->execute($command, $server);
			$this->summary->attach($command, $server, $response);
		}
	}

	/**
	 * @param Command $command - command to be executed
	 * @param Server  $server  - server to execute
	 *
	 * @return string|bool - response string if successful or false if failed
	 */
	public function execute(Command $command, Server $server)
	{
		return $this->api->send($command, $server);
	}
}