<?php

namespace hugojf\CsgoServerApi\Classes;

use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;

class Api
{
	protected $key;
	protected $url;

	public function __construct()
	{
		$this->url = config('csgo-api.url');
		$this->key = config('csgo-api.key');
	}

	/**
	 * Sends a command to specified CS:GO server via API
	 *
	 * @param Command $cmd - Command to execute
	 * @param Server  $sv
	 *
	 * @return string|bool
	 */
	public function send(Command $cmd, Server $sv)
	{
		$token = $this->key;
		$ip = $sv->getIp();
		$port = $sv->getPort();
		$command = $cmd->getCommand();
		$delay = $cmd->getDelay();
		$wait = $cmd->getWait() ? 'true' : '';

		/** @var Builder $curl */
		$curl = Curl::to("$this->url/send");

		$curl->withData(compact('ip', 'port', 'command', 'delay', 'token', 'wait'));

		$res = $curl->asJson(true)->get();

		return $this->processResponse($res);
	}

	/**
	 * Send a command to all servers registered in the API
	 *
	 * @param Command $cmd - command to be executed
	 *
	 * @return array|bool
	 */
	public function sendToAll(Command $cmd)
	{
		$token = $this->key;
		$command = $cmd->getCommand();
		$delay = $cmd->getDelay();
		$wait = $cmd->getWait() ? 'true' : '';

		/** @var Builder $curl */
		$curl = Curl::to("$this->url/sendAll");

		$curl->withData(compact('token', 'command', 'delay', 'wait'));

		$res = $curl->asJson(true)->get();

		return $this->processResponse($res);
	}

	/**
	 * @param $res
	 *
	 * @return bool|mixed
	 */
	protected function processResponse($res)
	{
		return $res['response'] ?? false;
	}
}