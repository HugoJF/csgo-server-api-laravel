<?php

namespace hugojf\CsgoServerApi\Classes\Lists;

use hugojf\CsgoServerApi\Classes\Server;
use hugojf\CsgoServerApi\InvalidAddressException;

class ServerList extends BaseList
{
	protected $itemClass = Server::class;

	/**
	 * @param array $params
	 *
	 * @return Server
	 * @throws InvalidAddressException
	 */
	protected function buildItem(...$params)
	{
		return new Server(...func_get_args());
	}
}