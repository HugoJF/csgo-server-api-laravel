<?php

namespace hugojf\CsgoServerApi\Classes\Senders;

use Exception;
use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\Traits\HandlesCommandList;

class BroadcastSender extends BaseSender
{
	use HandlesCommandList;

	public function __construct($summaryClass = ByServerSummary::class)
	{
		parent::__construct($summaryClass);
		$this->bootCommandList();
	}

	public function send()
	{
		foreach ($this->commands->getList() as $command) {
			$response = $this->api->sendToAll($command);
			$this->handleResponse($command, $response);
		}

		return $this->summary->getSummary();
	}

	protected function handleResponse(Command $command, $response)
	{
		if (!is_iterable($response))
			throw new Exception("Invalid command `{$command->getCommand()}` response `$response`");

		foreach ($response as $server => $item) {
			$this->summary->attach($command, $server, $item);
		}
	}
}