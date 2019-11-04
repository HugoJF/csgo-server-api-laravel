<?php

namespace hugojf\CsgoServerApi\Tests\Unit\Lists;

use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Lists\CommandList;
use hugojf\CsgoServerApi\Tests\Base;

class CommandListTest extends Base
{
	public function testCommandList()
	{
		$commandList = new CommandList();

		$commandList->addItem(new Command('stats', 1000, false));
		$commandList->addItem([
			new Command('stats', 1500, false),
			new Command('status', 1500, false),
		]);

		$commandList->addItem('stats', 1500, false);

		$commandList->addItem([
			['stats', '1500', false],
			['status', '1500', false],
		]);

		$this->assertEquals(6, count($commandList->getList()));
	}
}