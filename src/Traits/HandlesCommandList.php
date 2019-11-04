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

	public function addCommands(...$args)
	{
		$this->commands->addItem(...$args);

		return $this;
	}

	public function addCommand(...$args)
	{
		return $this->addCommands(...$args);
	}

	public function commands(...$args)
	{
		return $this->addCommands(...$args);
	}

	public function command(...$args)
	{
		return $this->addCommands(...$args);
	}
}