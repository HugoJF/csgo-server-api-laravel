<?php

namespace hugojf\CsgoServerApi\Classes;

class Command
{
	/**
	 * @var string
	 */
	protected $command;
	/**
	 * @var int
	 */
	protected $delay;
	/**
	 * @var bool
	 */
	protected $wait;

	public function __construct($command, $delay = 0, $wait = false)
	{
		$this->command = $command;
		$this->delay = $delay;
		$this->wait = $wait;
	}

	public function __toString()
	{
		return $this->getCommand();
	}

	/**
	 * @return string
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * @return int
	 */
	public function getDelay()
	{
		return $this->delay;
	}

	/**
	 * @return bool
	 */
	public function getWait()
	{
		return $this->wait;
	}
}