<?php

namespace hugojf\CsgoServerApi\Classes\Lists;

use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\InvalidAddressException;

class ServerList extends BaseList
{
	protected $itemClass = Server::class;

	/**
	 * @param $item
	 *
	 * @return Server
	 * @throws InvalidAddressException
	 */
	function buildItem($item)
	{
		return new Server($item);
	}
}