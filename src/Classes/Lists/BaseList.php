<?php

namespace hugojf\CsgoServerApi\Classes\Lists;

abstract class BaseList
{
	protected $list = [];

	protected $itemClass = null;

	abstract function buildItem($item);

	public function addItem($items)
	{
		// Always wrap argument in array
		if (!is_array($items))
			$items = [$items];

		// Instantiate Server classes if needed
		$items = array_map(function ($item) {
			return (is_object($item) && get_class($item) === $this->itemClass) ? $item : $this->buildItem($item);
		}, $items);

		// Merge with current list
		$this->list = array_merge($this->list, $items);

		return $this;
	}

	public function getList()
	{
		return $this->list;
	}
}