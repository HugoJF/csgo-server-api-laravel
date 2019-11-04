<?php

namespace hugojf\Tests\Unit;

use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\Classes\Summaries\ByCommandSummary;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\Tests\Base;

class SummaryTest extends Base
{
	public function test_by_server_summary()
	{
		$byServer = new ByServerSummary();

		$cmd = new Command('stats', 1000, true);
		$sv1 = new Server('177.54.150.15:27001');
		$sv2 = new Server('177.54.150.15:27002');

		$byServer->attach($cmd, $sv1, 'response');
		$byServer->attach($cmd, $sv2, 'response');

		$expected = [
			"177.54.150.15:27001" => [
				"stats" => "response",
			],
			"177.54.150.15:27002" => [
				"stats" => "response",
			],
		];

		$this->assertEquals($expected, $byServer->getSummary());
	}

	public function test_by_command_summary()
	{
		$byCommand = new ByCommandSummary();

		$cmd = new Command('stats', 1000, true);
		$sv1 = new Server('177.54.150.15:27001');
		$sv2 = new Server('177.54.150.15:27002');

		$byCommand->attach($cmd, $sv1, 'response');
		$byCommand->attach($cmd, $sv2, 'response');

		$expected = [
			"stats" => [
				"177.54.150.15:27001" => "response",
				"177.54.150.15:27002" => "response",
			],
		];

		$this->assertEquals($expected, $byCommand->getSummary());
	}
}
