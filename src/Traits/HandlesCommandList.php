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

	public function addCommand($command)
	{
		$this->commands->addItem($command);

		return $this;
	}
}