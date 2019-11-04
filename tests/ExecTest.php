<?php

namespace hugojf\Tests;

use Exception;
use hugojf\CsgoServerApi\Classes\Api;
use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Lists\CommandList;
use hugojf\CsgoServerApi\Classes\Lists\ServerList;
use hugojf\CsgoServerApi\Classes\Senders\BroadcastSender;
use hugojf\CsgoServerApi\Classes\Senders\DirectSender;
use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\Classes\Summaries\ByCommandSummary;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\InvalidAddressException;
use hugojf\CsgoServerApi\Providers\PackageServiceProvider;
use Ixudra\Curl\CurlServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class ExecTest extends OrchestraTestCase
{
	protected function getPackageProviders($app)
	{
		return [
			PackageServiceProvider::class,
			CurlServiceProvider::class,
		];
	}

	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('csgo-api.url', 'http://csgo-server-api.denerdtv.com');
		$app['config']->set('csgo-api.key', 'nice');
	}

	public function testByServerSummary()
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

	public function testByCommandSummary()
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

	public function testBroadcastSender()
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

		$broadcast->addCommand($command1);
		$broadcast->addCommand($command2);

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

	public function testBroadcastSenderWillRaiseExceptionWhenInvalidResponseIsReturned()
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

	public function testDirectSender()
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

	public function testServerCanBeInstantiatedByAddress()
	{
		$address = '177.54.150.15:27001';

		$server = new Server($address);

		$this->assertEquals('177.54.150.15', $server->getIp());
		$this->assertEquals('27001', $server->getPort());
	}

	public function testServerCanBeInstantiatedByIpAndPort()
	{
		$server = new Server('177.54.150.15', 27001);

		$this->assertEquals('177.54.150.15', $server->getIp());
		$this->assertEquals('27001', $server->getPort());
	}

	public function testServerWillRaiseExceptionOnInvalidAddress()
	{
		$this->expectException(InvalidAddressException::class);

		$address = '177.54.150.15:123456';

		$server = new Server($address);

		$this->assertEquals('177.54.150.15', $server->getIp());
		$this->assertEquals('27001', $server->getPort());
	}

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

	public function testServerList()
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