<?php

namespace RRComparator\DataManagement;

use mysqli;
use RRComparator\Configuration\Config;
use RRComparator\Exception\DatabaseConnectionError;
use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Exception\InvalidMethodCallException;
use RRComparator\Logger\ConsoleLogger;

/**
 * DataSource implementation for MySQL
 */
class DataSourceMySql extends DataSource
{
	private $dbName;
	private $dbConnection;

	private $requiredFields = ['host', 'username', 'password', 'db'];

	public function __construct(Config $config)
	{
		parent::__construct($config);

		foreach ($this->requiredFields as $field) {
			try {
				$this->config->$field;
			} catch (InvalidMethodCallException $e) {
				throw new InvalidConfigurationException("Missing configuration field: '{$field}'");
			}

			$this->dbName = $this->sanitize($config->db);
			$this->dbConnection = new mysqli($config->host, $config->username, $config->password);

			if ($this->dbConnection->connect_error) {
				throw new DatabaseConnectionError($this->dbConnection->connect_errno . " : " .
					$this->dbConnection->connect_error
				);
			}

			$this->init();
		}
	}

	private function sanitize(string $data): string
	{
		return preg_replace('/\s+/', '', trim($data));
	}

	private function init()
	{
		ConsoleLogger::log("DataSourceMySql: creating schema '{$this->dbName}'");

		$this->dbConnection->query("CREATE DATABASE IF NOT EXISTS {$this->dbName}");
		$this->dbConnection->select_db($this->dbName);
	}

	public function getAllTables(): array
	{
		ConsoleLogger::log("DataSourceMySql: getting all tables");

		$stmt = $this->dbConnection->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=?");
		$stmt->bind_param("s", $this->dbName);
		$stmt->execute();
		$result = $stmt->get_result();

		$tables = [];

		while ($row = $result->fetch_assoc()) {
			$tables[] = $row['TABLE_NAME'];
		}

		ConsoleLogger::log("DataSourceMySql: got " . count($tables) . " tables");

		return $tables;
	}

	public function getAllColumns(string $tableName): array
	{
		ConsoleLogger::log("DataSourceMySql: getting all columns");

		$stmt = $this->dbConnection->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                                                        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?");
		$stmt->bind_param("ss", $this->dbName, $tableName);
		$stmt->execute();
		$result = $stmt->get_result();

		$columns = [];

		while ($row = $result->fetch_assoc()) {
			$columns[] = $row['COLUMN_NAME'];
		}

		ConsoleLogger::log("DataSourceMySql: got " . count($columns) . " columns");

		return $columns;
	}

	public function getData(string $tableName, array $columns): array
	{
		ConsoleLogger::log("DataSourceMySql: getting data from {$tableName}, columns: " . implode(',', $columns));

		$columnsSanitized = [];
		foreach ($columns as $column) {
			$columnsSanitized[] = $this->sanitize($column);
		}

		$columnsImploded = implode(",", $columnsSanitized);

		$result = $this->dbConnection->query("SELECT {$columnsImploded} FROM {$tableName}");

		$rows = [];

		while ($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}

		ConsoleLogger::log("DataSourceMySql: got " . count($rows) . " rows");

		return $rows;
	}

	public function executeRawQuery(string $query): void
	{
		ConsoleLogger::log("DataSourceMySql: executing raw query {$query}");
		$this->dbConnection->query($query);
	}
}