<?php

namespace RRComparator\DataManagement;

use RRComparator\Configuration\Config;

/**
 * Based on the configuration, create the data source and populate it with data
 */
abstract class DataFixture
{
	protected $dataConnection;
	protected $config;

	public function __construct(DataSource $dataConnection, Config $config)
	{
		$this->dataConnection = $dataConnection;
		$this->config = $config;
	}

	abstract public function populateData(): void;
}