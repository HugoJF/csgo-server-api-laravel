<?php

namespace hugojf\CsgoServerApi\Tests\Unit;

use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\InvalidAddressException;
use hugojf\CsgoServerApi\Tests\Base;

class ServerTest extends Base
{
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

}