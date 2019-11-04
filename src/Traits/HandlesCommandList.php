<?php

namespace hugojf\CsgoServerApi\Traits;

use hugojf\CsgoServerApi\Classes\Lists\CommandList;

trait HandlesCommandList
{
	/** @var CommandList */
	protected $commands;

	public function bootCommandList()
	{
		$this->commands = new CommandList();
	}

	public function addCommands($commands)
	{
		$this->commands->addItem($commands);

		return $this;
	}

	public function addCommand($command)
	{
		return $this->addCommands($command);
	}

	public function commands($commands)
	{
		return $this->addCommands($commands);
	}

	public function command($commands)
	{
		return $this->addCommands($commands);
	}
}