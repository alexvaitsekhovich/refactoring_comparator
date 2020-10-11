<?php

namespace RRComparator\Datasource;

/**
 * @codeCoverageIgnore
 */
class DataSourceArray implements DataSource
{
	private $data = [
		[
			'table0' => ['column00' => 0, 'column01' => 1, 'column02' => 2],
			'table1' => ['column10' => 'A', 'column11' => 'B']
		]
	];

	public function getAllTables(): array
	{
		return array_keys($this->data);
	}

	public function getAllColumns(string $tableName): array
	{
		return array_keys($this->data[$tableName]);
	}

	public function getData(string $tableName, array $columns): array
	{
		return $this->data[$tableName];
	}

	public function executeRawQuery(string $query): void
	{
	}
}