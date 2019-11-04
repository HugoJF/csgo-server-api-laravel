<?php

namespace hugojf\CsgoServerApi\Traits;

use hugojf\CsgoServerApi\Classes\Lists\ServerList;

trait HandlesServerList
{
	/** @var ServerList */
	protected $servers;

	public function bootServerList()
	{
		$this->servers = new ServerList();
	}

	public function addServers($servers)
	{
		$this->servers->addItem($servers);

		return $this;
	}

	public function servers($servers)
	{
		return $this->addServers($servers);
	}

	public function addServer($server)
	{
		return $this->addServers($server);
	}

	public function server($server)
	{
		return $this->addServers($server);
	}
}