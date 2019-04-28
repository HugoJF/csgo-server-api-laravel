<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 4/2/2019
 * Time: 8:37 PM
 */

namespace hugojf\CsgoServerApi;

use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;

class CsgoApi
{
	protected $servers = [];
	protected $commands = [];

	protected $broadcast = false;
	protected $waitResponse = false;

	private $key;
	private $url;

	public function __construct()
	{
		$this->url = config('csgo-api.url');
		$this->key = config('csgo-api.key');
	}

	/**
	 * Sets broadcast mode on
	 *
	 * @return $this
	 */
	public function broadcast()
	{
		$this->broadcast = true;

		return $this;
	}

	/**
	 * Sets list of servers to execute commands
	 *
	 * @param string|array $server - server or list of servers to execute commands
	 *
	 * @return $this
	 */
	public function to($server)
	{
		if (!is_array($server)) {
			$server = [$server];
		}

		$this->servers = array_merge($this->servers, $server);

		return $this;
	}

	/**
	 * Wait for API responses
	 */
	public function wait()
	{
		$this->waitResponse = true;

		return $this;
	}

	/**
	 * Adds commands to execution list
	 *
	 * @param array|string $commands - commands to be added
	 * @param int          $delay    - delay in milliseconds $commands is string
	 *
	 * @param bool         $wait     - wait for server response
	 *
	 * @return CsgoApi
	 */
	public function execute($commands, $delay = 0, $wait = false)
	{
		if (!is_array($commands)) {
			$commands = [[$commands, $delay, $wait]];
		} else {
			$commands = collect($commands)->map(function ($c) use ($delay, $wait) {
				return $c + ['', $delay, $wait];
			})->toArray();
		}

		$this->commands = array_merge($this->commands, $commands);

		return $this;
	}

	/**
	 * Execute command alias
	 *
	 * @param array|string $commands - commands to be added
	 * @param int          $delay    - delay in milliseconds $commands is string
	 *
	 * @param bool         $wait     - wait for server response
	 *
	 * @return CsgoApi
	 */
	public function commands($commands, $delay = 0, $wait = false)
	{
		return $this->execute($commands, $delay, $wait);
	}


	/**
	 * Executes a list of commands in the set list of servers
	 */
	public function send()
	{
		return $this->executeCommandList($this->commands, $this->servers);
	}

	/**
	 * Execute list of commands on a list of servers
	 *
	 * @param array $commands - list of commands to be executed
	 * @param array $servers  - list of servers to be executed on
	 *
	 * @return mixed
	 */
	protected function executeCommandList($commands, $servers)
	{
		$responses = [];

		foreach ($commands as $com) {
			[$command, $delay, $wait] = $com;

			if ($this->broadcast) {
				$responses[ $command ] = $this->sendToAll($command, $delay, $wait);
			} else {
				$responses[ $command ] = $this->executeOnServers($command, $delay, $servers, $wait);
			}
		}

		return $responses;
	}

	/**
	 * Execute a command in a list of servers
	 *
	 * @param string  $command - command to be executed
	 * @param integer $delay   - delay in milliseconds
	 * @param array   $servers - server list
	 * @param bool    $wait    - wait for server response
	 *
	 * @return array
	 */
	protected function executeOnServers($command, $delay, $servers, $wait = null)
	{
		$responses = [];

		foreach ($servers as $server) {
			if ($info = $this->splitServerData($server)) {
				['ip' => $ip, 'port' => $port] = $info;

				$responses[ $server ] = $this->sendCommandToServer($ip, $port, $command, $delay, $wait);
			}
		}

		return $responses;
	}

	/**
	 * Send a command to all servers registered in the API
	 *
	 * @param string  $command         - command to be executed
	 * @param integer $delay           - delay in milliseconds
	 * @param null    $waitForResponse - wait for server response
	 *
	 * @return array|bool
	 */
	protected function sendToAll($command, $delay, $waitForResponse = null)
	{
		$token = $this->key;
		if ($waitForResponse === null) {
			$wait = $this->waitResponse;
		} else {
			$wait = $waitForResponse;
		}

		$wait = $wait ? 'true' : '';

		/** @var Builder $curl */
		$curl = Curl::to("$this->url/sendAll");

		$curl->withData(compact('command', 'delay', 'token', 'wait'));

		$res = $curl->asJson(true)->get();

		if (array_key_exists('response', $res)) {
			return $res['response'];
		} else {
			return false;
		}
	}

	/**
	 * Sends a command to specified CS:GO server via API
	 *
	 * @param string  $ip              - CS:GO server IP
	 * @param integer $port            - CS:GO server port
	 * @param string  $command         - Command to execute
	 * @param integer $delay           - Delay to execute command in milliseconds
	 * @param boolean $waitForResponse - Wait for server response
	 *
	 * @return array|bool
	 */
	protected function sendCommandToServer($ip, $port, $command, $delay, $waitForResponse = null)
	{
		$token = $this->key;

		if ($waitForResponse === null) {
			$wait = $waitForResponse;
		} else {
			$wait = $this->waitResponse;
		}

		$wait = $wait ? 'true' : '';

		/** @var Builder $curl */
		$curl = Curl::to("$this->url/send");

		$curl->withData(compact('ip', 'port', 'command', 'delay', 'token', 'wait'));

		$res = $curl->asJson(true)->get();

		if (array_key_exists('response', $res)) {
			return $res['response'];
		} else {
			return false;
		}
	}

	/**
	 * Split server address string to IP and port
	 *
	 * @param string $server - Server IP with Port
	 *
	 * @return array|bool - false on fail, associative array on success
	 */
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
}