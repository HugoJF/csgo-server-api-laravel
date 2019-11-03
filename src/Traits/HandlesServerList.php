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

	public function addServer($server)
	{
		$this->servers->addItem($server);

		return $this;
	}
}