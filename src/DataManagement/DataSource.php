<?php

namespace RRComparator\DataManagement;

use RRComparator\Configuration\Config;

/**
 * Source of data, resulting from the execution of the script under refactoring
 */
abstract class DataSource
{
	protected $config;

	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	abstract public function getAllTables(): array;

	abstract public function getAllColumns(string $tableName): array;

	abstract public function getData(string $tableName, array $columns): array;

	abstract public function executeRawQuery(string $query): void;
}