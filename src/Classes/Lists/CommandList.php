<?php

namespace hugojf\CsgoServerApi\Classes\Lists;

use hugojf\CsgoServerApi\Classes\Command;

class CommandList extends BaseList
{
	protected $itemClass = Command::class;

	/**
	 * @param $item
	 *
	 * @return Command
	 */
	function buildItem($item)
	{
		return new Command($item);
	}
}