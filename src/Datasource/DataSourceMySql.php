<?php

namespace RRComparator\Datasource;

use mysqli;
use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Exception\InvalidMethodCallException;

/**
 * DataSource implementation for MySQL
 * @codeCoverageIgnore
 */
class DataSourceMySql implements DataSource
{
	private $dbName;
	private $dbConnection;

	private $requiredFields = ['host', 'username', 'password', 'db'];

	public function __construct(Config $config)
	{
		foreach ($this->requiredFields as $field) {
			try {
				$config->$field;
			} catch (InvalidMethodCallException $e) {
				throw new InvalidConfigurationException("Missing configuration field: '{$field}'");
			}

			$this->dbName = $this->sanitize($config->db);
			$this->dbConnection = new mysqli($config->host, $config->username, $config->password);

			$this->init();
		}
	}

	private function init()
	{
		$this->dbConnection->query("CREATE DATABASE IF NOT EXISTS {$this->dbName}");
		$this->dbConnection->select_db($this->dbName);
	}

	public function getAllTables(): array
	{
		$stmt = $this->dbConnection->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=?");
		$stmt->bind_param("s", $this->dbName);
		$stmt->execute();
		$result = $stmt->get_result();

		$tables = [];

		while ($row = $result->fetch_assoc()) {
			$tables[] = $row['TABLE_NAME'];
		}

		return $tables;
	}

	public function getAllColumns(string $tableName): array
	{
		$stmt = $this->dbConnection->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                                                        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?");
		$stmt->bind_param("ss", $this->dbName, $tableName);
		$stmt->execute();
		$result = $stmt->get_result();

		$columns = [];

		while ($row = $result->fetch_assoc()) {
			$columns[] = $row['COLUMN_NAME'];
		}

		return $columns;
	}

	public function getData(string $tableName, array $columns): array
	{
		$columnsSanitized = [];
		foreach ($columns as $column) {
			$columnsSanitized[] = $this->sanitize($column);
		}

		$columnsImploded = implode(",", $columnsSanitized);

		$result = $this->dbConnection->query("SELECT {$columnsImploded} FROM {$tableName}");

		$columns = [];

		while ($row = $result->fetch_assoc()) {
			$columns[] = $row;
		}

		return $columns;
	}

	public function executeRawQuery(string $query): void
	{
		$this->dbConnection->query($query);
	}

	private function sanitize(string $data): string
	{
		return preg_replace('/\s+/', '', trim($data));
	}
}