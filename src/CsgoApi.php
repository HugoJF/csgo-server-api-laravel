<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 4/2/2019
 * Time: 8:37 PM
 */

namespace hugojf\CsgoApi;

use Ixudra\Curl\Builder;
use Ixudra\Curl\CurlService;
use Ixudra\Curl\Facades\Curl;

class CsgoApi
{
	protected $servers = [];

	protected $broadcast = false;

	public function __construct()
	{
		$this->url = config('csgo-api.url');
		$this->key = config('csgo-api.key');
	}

	public function to($server)
	{
		if (!is_array($server)) {
			$server = [$server];
		}

		$this->servers = array_merge($this->servers, $server);

		return $this;
	}

	public function execute($command, $delay)
	{
		foreach ($this->servers as $server) {
			if (list($ip, $port) = $this->splitServerData($server)) {
				$this->sendCommandToServer($ip, $port, $command, $delay);
			}
		}
	}

	protected function splitServerData($server)
	{
		$data = preg_split('/\:/', $server);

		if (count($data) == 2) {
			return [
				'ip'   => $data[0],
				'port' => $data[1],
			];
		} else {
			return false;
		}
	}

	protected function broadcast($command, $delay)
	{
		$key = $this->key;

		/** @var Builder $curl */
		$curl = Curl::to("$this->url/sendAll");

		$curl->withData(compact('command', 'delay', 'key'));

		$curl->get();
	}

	protected function sendCommandToServer($ip, $port, $command, $delay)
	{
		$key = $this->key;

		/** @var Builder $curl */
		$curl = Curl::to("$this->url/send");

		$curl->withData(compact('ip', 'port', 'command', 'delay', 'key'));

		$curl->get();
	}
}