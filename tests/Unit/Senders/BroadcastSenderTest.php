<?php

namespace hugojf\CsgoServerApi\Tests\Unit\Senders;

use Exception;
use hugojf\CsgoServerApi\Classes\Api;
use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Senders\BroadcastSender;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\Tests\Base;

class BroadcastSenderTest extends Base
{
	public function test_broadcast_sender_by_add_command()
	{
		$this->execute_broadcast_sender_by_method_name('addCommand');
	}

	public function test_broadcast_sender_by_add_commands()
	{
		$this->execute_broadcast_sender_by_method_name('addCommands');
	}

	public function test_broadcast_sender_by_command()
	{
		$this->execute_broadcast_sender_by_method_name('command');
	}

	public function test_broadcast_sender_by_commands()
	{
		$this->execute_broadcast_sender_by_method_name('commands');
	}

	protected function execute_broadcast_sender_by_method_name($method)
	{
		$this->mock(Api::class, function ($mock) {
			$mock->shouldReceive('sendToAll')->once()->andReturn([
				'177.54.150.15:27001' => 'status-1',
				'177.54.150.15:27002' => 'status-2',
			]);
			$mock->shouldReceive('sendToAll')->once()->andReturn([
				'177.54.150.15:27001' => 'stats-1',
				'177.54.150.15:27002' => 'stats-2',
			]);
		});

		$broadcast = new BroadcastSender(ByServerSummary::class);
		$command1 = new Command('status', 2500, false);
		$command2 = new Command('stats', 1000, true);

		$broadcast->$method($command1);
		$broadcast->$method($command2);

		$summary = $broadcast->send();

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

	public function test_broadcast_sender_will_raise_exception_when_invalid_response_is_returned()
	{
		$this->mock(Api::class, function ($mock) {
			$mock->shouldReceive('sendToAll')->once()->andReturn(null);
		});

		$this->expectException(Exception::class);

		$broadcast = new BroadcastSender(ByServerSummary::class);
		$command = new Command('status', 2500, false);

		$broadcast->addCommand($command);

		$summary = $broadcast->send();

		$expected = [
			"177.54.150.15:27001" => [
				"status" => "status-1",
			],
			"177.54.150.15:27002" => [
				"status" => "status-2",
			],
		];

		$this->assertEquals($expected, $summary);
	}
}