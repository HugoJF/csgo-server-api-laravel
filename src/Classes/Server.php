<?php

namespace hugojf\CsgoServerApi\Classes;

use hugojf\CsgoServerApi\InvalidAddressException;

class Server
{
	protected $ip;
	protected $port;

	/**
	 * Server constructor from address (IP:PORT) or IP and Port
	 *
	 * @param $address
	 * @param $port
	 *
	 * @throws InvalidAddressException
	 */
	public function __construct($address, $port = null)
	{
		if ($port)
			$this->fromIpAndPort($address, $port);
		else
			$this->fromAddress($address);
	}

	public function __toString()
	{
		return "$this->ip:$this->port";
	}

	/**
	 * Constructs server from IP and Port
	 *
	 * @param $ip
	 * @param $port
	 */
	private function fromIpAndPort($ip, $port)
	{
		$this->ip = $ip;
		$this->port = $port;
	}

	private function fromAddress($address)
	{
		$matched = preg_match('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}):(\d{1,5})$/', $address, $matches);

		if (!$matched)
			throw new InvalidAddressException("$address is not a valid address.");

		$this->ip = $matches[1];
		$this->port = $matches[2];
	}

	/**
	 * @return mixed
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * @return mixed
	 */
	public function getPort()
	{
		return $this->port;
	}

}