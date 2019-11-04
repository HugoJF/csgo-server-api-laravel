<?php

namespace hugojf\CsgoServerApi\Tests\Unit\Senders;

use hugojf\CsgoServerApi\Classes\Api;
use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Senders\DirectSender;
use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\Tests\Base;

class DirectSenderTest extends Base
{
	public function test_direct_sender()
	{
		$this->mock(Api::class, function ($mock) {
			$mock->shouldReceive('send')->once()->andReturn('status-1');
			$mock->shouldReceive('send')->once()->andReturn('status-2');
			$mock->shouldReceive('send')->once()->andReturn('stats-1');
			$mock->shouldReceive('send')->once()->andReturn('stats-2');
		});

		$direct = new DirectSender(ByServerSummary::class);

		$command1 = new Command('status', 2500, false);
		$command2 = new Command('stats', 1000, true);

		$server1 = new Server('177.54.150.15:27001');
		$server2 = new Server('177.54.150.15:27002');

		$direct->addCommand($command1);
		$direct->addCommand($command2);

		$direct->addServer($server1);
		$direct->addServer($server2);

		$summary = $direct->send();

		$expected = [
			"177.54.150.15:27001" => [
				"status" => "status-1",
				"stats"  => "stats-1",
			],
			"177.54.150.15:27002" => [
				"status" => "status-2",
				"stats"  => "stats-2",
			],
		];

		$this->assertEquals($expected, $summary);
	}
}