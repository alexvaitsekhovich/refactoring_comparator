<?php

namespace RRComparator\Processor;

use RRComparator\Configuration\Config;
use RRComparator\DataManagement\DataSource;
use RRComparator\Exception\InvalidMethodCallException;

/**
 * Gather all the data from the data source
 */
class DataCollector
{
	private $dataSource;
	private $config;
	private $dbData;

	public function __construct(DataSource $dataSource, Config $config)
	{
		$this->dataSource = $dataSource;
		$this->config = $config;
		$this->dbData = [];
	}

	public function gatherData()
	{
		$excludedColumns = $this->getExcludedColumns();

		foreach ($this->dataSource->getAllTables() as $table) {

			$columns = array_diff(
				$this->dataSource->getAllColumns($table),
				array_map('trim', explode(',', $excludedColumns[$table] ?? ""))
			);

			if (count($columns) > 0) {
				$this->dbData[$table] = $this->dataSource->getData($table, $columns);
			}
		}

	}

	private function getExcludedColumns()
	{
		try {
			return $this->config->excludedColumns;
		} catch (InvalidMethodCallException $e) {
			return [];
		}
	}

	public function getData(): array
	{
		return $this->dbData;
	}

}