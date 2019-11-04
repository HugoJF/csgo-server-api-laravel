<?php

namespace hugojf\CsgoServerApi\Classes\Senders;

use hugojf\CsgoServerApi\Classes\Api;
use hugojf\CsgoServerApi\Classes\Summaries\ByServerSummary;
use hugojf\CsgoServerApi\Contracts\Summary;

abstract class BaseSender
{
	/** @var Api */
	protected $api;

	/** @var Summary */
	protected $summary;

	public function __construct($summaryClass = ByServerSummary::class)
	{
		$this->api = app(Api::class);
		$this->summary = new $summaryClass();
	}

	abstract public function send();
}