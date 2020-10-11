<?php

namespace RRComparator\Datasource;

/**
 * Source of data, resulting from the execution of the script under refactoring
 */
interface DataSource
{
	public function getAllTables() : array;
	public function getAllColumns(string $tableName) : array;
	public function getData(string $tableName, array $columns) : array;
	public function executeRawQuery(string $query) : void;
}