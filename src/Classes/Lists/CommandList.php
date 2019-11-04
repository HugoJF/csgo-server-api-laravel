<?php

namespace hugojf\CsgoServerApi\Classes\Lists;

use hugojf\CsgoServerApi\Classes\Command;

class CommandList extends BaseList
{
	protected $itemClass = Command::class;

	/**
	 * @param array $params
	 *
	 * @return Command
	 */
	protected function buildItem(...$params)
	{
		return new Command(...func_get_args());
	}
}