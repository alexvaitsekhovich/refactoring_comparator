<?php

namespace RRComparator\Processor;

use RRComparator\Configuration\Config;
use RRComparator\DataManagement\DataSource;
use RRComparator\Exception\InvalidMethodCallException;
use RRComparator\Logger\ConsoleLogger;

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
		ConsoleLogger::log("DataCollector: gathering data");

		$excludedColumns = $this->getExcludedColumns();

		ConsoleLogger::log("DataCollector: excluded columns are: " . print_r($excludedColumns, true));

		foreach ($this->dataSource->getAllTables() as $table) {

			$columns = array_diff(
				$this->dataSource->getAllColumns($table),
				array_map('trim', explode(',', $excludedColumns[$table] ?? ""))
			);

			ConsoleLogger::log("DataCollector: columns for table {$table}: " . implode(',', $columns));

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