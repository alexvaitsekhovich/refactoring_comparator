<?php

namespace RRComparator\Datasource;

use PHPUnit\Framework\TestCase;
use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;

/**
 * @covers RRComparator\Datasource\DataToolsContainer
 */
class DataToolsContainerTest extends TestCase
{
	private $dataSourceConfig;

	public function testConstructor()
	{
		$this->dataSourceConfig->dataSourceConf = 'tests/test_data/test1.ini';

		$this->dataSourceConfig->expects($this->atLeastOnce())
			->method('__get')
			->withConsecutive(
				['dataSourceImpl'],
				['dataFixtureImpl'],
				['dataSourceConf']
			);

		try {
			new DataToolsContainer($this->dataSourceConfig, $this->createMock(Config::class));
		} catch (InvalidConfigurationException $e) {
		}
	}

	protected function setUp(): void
	{
		$this->dataSourceConfig = $this->createMock(Config::class);
	}
}
