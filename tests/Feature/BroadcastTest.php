<?php

namespace hugojf\CsgoServerApi\Tests\Feature;

use hugojf\CsgoServerApi\Classes\Command;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\Facades\CsgoApi;
use hugojf\CsgoServerApi\Tests\Base;
use Ixudra\Curl\Builder;
use Ixudra\Curl\Facades\Curl;
use Mockery;

class BroadcastTest extends Base
{
	public function test_broadcast_method()
	{
		$this->execute_facade_method('broadcast');
	}
	public function test_all_method()
	{
		$this->execute_facade_method('all');
	}

	protected function execute_facade_method($method)
	{
		$builder = Mockery::mock(Builder::class)->makePartial();

		$builder->shouldReceive('get')->once()->andReturn(['error' => false, 'response' => [
			'177.54.150.15:27001' => 'response-1',
			'177.54.150.15:27002' => 'response-1',
		]]);
		$builder->shouldReceive('get')->once()->andReturn(['error' => false, 'response' => [
			'177.54.150.15:27001' => 'response-1',
			'177.54.150.15:27002' => 'response-1',
		]]);

		Curl::shouldReceive('to')->twice()->andReturn($builder);

		$response = CsgoApi::$method(ByServerSummary::class)->addCommand([
			new Command('stats', 1500, false),
			new Command('status', 1500, false),
		])->send();

		$expected = [
			"177.54.150.15:27001" => [
				"stats"  => "response-1",
				"status" => "response-1",
			],
			"177.54.150.15:27002" => [
				"stats"  => "response-1",
				"status" => "response-1",
			],
		];

		$this->assertEquals($expected, $response);
	}
}