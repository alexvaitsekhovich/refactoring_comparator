<?php


namespace RRComparator\Datasource;

use Error;
use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;

class DataToolsContainer
{
	public $dataSource;
	public $dataFixture;

	public function __construct(Config $dataSourceConfig, Config $dbConfig)
	{
		$dataSourceClass = $dataSourceConfig->dataSourceImpl;
		$dataFixtureClass = $dataSourceConfig->dataFixtureImpl;

		$fixturesConfig = Config::init($dataSourceConfig->dataSourceConf);

		try {
			$this->dataSource = new $dataSourceClass($dbConfig);
			$this->dataFixture = new $dataFixtureClass($this->dataSource, $fixturesConfig);
		}
		catch (Error $e) {
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