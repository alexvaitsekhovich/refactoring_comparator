<?php


namespace RRComparator\DataManagement;

use Error;
use Exception;
use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Logger\ConsoleLogger;

class DataToolsContainer
{
	public $dataSource;
	public $dataFixture;

	public function __construct(Config $dataSourceConfig, Config $dbConfig)
	{
		$dataSourceClass = $dataSourceConfig->dataSourceImpl;
		$dataFixtureClass = $dataSourceConfig->dataFixtureImpl;

		$fixturesConfig = Config::init($dataSourceConfig->dataSourceConf);

		ConsoleLogger::log("DataToolsContainer: creating data connection from class '{$dataSourceClass}'");
		ConsoleLogger::log("DataToolsContainer: creating data fixture from class '{$dataFixtureClass}'");

		try {
			$this->dataSource = new $dataSourceClass($dbConfig);
			$this->dataFixture = new $dataFixtureClass($this->dataSource, $fixturesConfig);
		}
		catch (Exception $e) {
			throw new InvalidConfigurationException($e->getMessage());
		}
	}

	public function getDataSource(): DataSource
	{
		return $this->dataSource;
	}

	public function getDataFixture(): DataFixture
	{
		return $this->dataFixture;
	}



}