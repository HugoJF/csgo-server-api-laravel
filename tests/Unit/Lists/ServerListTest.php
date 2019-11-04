<?php

namespace hugojf\CsgoServerApi\Tests\Unit\Lists;

use hugojf\CsgoServerApi\Classes\Lists\ServerList;
use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\Tests\Base;

class ServerListTest extends Base
{
	public function test_server_list_will_add_new_servers()
	{
		$serverList = new ServerList();

		$serverList->addItem(new Server('177.54.150.15:27001'));
		$serverList->addItem([
			new Server('177.54.150.15:27001'),
			new Server('177.54.150.15:27002'),
		]);

		$serverList->addItem('177.54.150.15:27002');
		$serverList->addItem([
			'177.54.150.15:27001',
			'177.54.150.15:27002',
		]);
		$serverList->addItem('177.54.150.15', 27002);
		$serverList->addItem([
			['177.54.150.15', 27001],
			['177.54.150.15', 27002],
		]);

		$list = $serverList->getList();

		$this->assertEquals(9, count($list));
	}

}