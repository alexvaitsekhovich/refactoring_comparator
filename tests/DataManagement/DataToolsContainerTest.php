<?php

namespace RRComparator\DataManagement;

use PHPUnit\Framework\TestCase;
use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;

/**
 * @covers RRComparator\DataManagement\DataToolsContainer
 */
class DataToolsContainerTest extends TestCase
{
	private $dataSourceConfig;
	private $dbConfig;

	protected function setUp(): void
	{
		$this->dataSourceConfig = $this->createMock(Config::class);
		$this->dbConfig = $this->createStub(Config::class);

		$this->dataSourceConfig->dataSourceImpl = 'RRComparator\DataManagement\DataSourceArray';
		$this->dataSourceConfig->dataFixtureImpl = 'RRComparator\DataManagement\DataFixtureArray';
		$this->dataSourceConfig->dataSourceConf = 'tests/test_data/test1.ini';
	}

	public function testGetDataSource()
	{
		$dataToolsContainer = new DataToolsContainer($this->dataSourceConfig, $this->dbConfig);
		$this->assertInstanceOf(DataSourceArray::class, $dataToolsContainer->getDataSource());
	}

	public function testGetDataFixture()
	{
		$dataToolsContainer = new DataToolsContainer($this->dataSourceConfig, $this->dbConfig);
		$this->assertInstanceOf(DataFixtureArray::class, $dataToolsContainer->getDataFixture());
	}

	public function testInvalidConfigurationException()
	{
		$this->expectError(InvalidConfigurationException::class);
		$this->dataSourceConfig->dataSourceConf = 'badConf.ini';
		new DataToolsContainer($this->dataSourceConfig, $this->dataSourceBadConf);
	}

}
