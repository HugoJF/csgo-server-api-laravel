<?php

namespace hugojf\CsgoServerApi\Classes\Lists;

use Traversable;

abstract class BaseList
{
	protected $list = [];

	protected $itemClass = null;

	protected abstract function buildItem(...$params);

	public function addItem($items, ...$rest)
	{
		// Always wrap argument in array
		if (!is_array($items))
			$items = [func_get_args()];

		// Instantiate Server classes if needed
		$items = array_map(function ($item) {
			// Make sure item is unpackable (when just a string is given as parameter)
			if (!is_array($item) && !$item instanceof Traversable)
				$item = [$item];

			// Check if item is already instantiated
			if (count($item) === 1 && is_object($item[0]) && get_class($item[0]) === $this->itemClass)
				return $item[0];

			return $this->buildItem(...$item);
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