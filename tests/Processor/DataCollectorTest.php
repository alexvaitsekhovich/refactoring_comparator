<?php

namespace RRComparator\Processor;

use PHPUnit\Framework\TestCase;
use RRComparator\Configuration\Config;
use RRComparator\Datasource\DataSource;
use RRComparator\Exception\InvalidMethodCallException;
use function PHPUnit\Framework\assertSame;

/**
 * @covers RRComparator\Processor\DataCollector
 */
class DataCollectorTest extends TestCase
{
	private $mockConfig;
	private $mockDataSource;

	protected function setUp(): void
	{
		$this->mockConfig = $this->createMock(Config::class);
		$this->mockDataSource = $this->createMock(DataSource::class);
	}

	public function testDataSourceMethodsCalledWithoutTables()
	{
		$this->mockDataSource->expects($this->once())->method('getAllTables');
		$this->mockDataSource->expects($this->never())->method('getAllColumns');
		$this->mockDataSource->expects($this->never())->method('getData');

		$dataCollector = new DataCollector($this->mockDataSource, $this->mockConfig);
		$dataCollector->gatherData();
	}

	public function testDataSourceMethodsCalledWithTables()
	{
		$tableNames = ['table1', 'table2'];

		$this->mockDataSource->method('getAllTables')->willReturn($tableNames);

		$this->mockDataSource->expects($this->once())->method('getAllTables');
		$this->mockDataSource->expects($this->exactly(count($tableNames)))
			->method('getAllColumns')
			->withConsecutive(
				[$tableNames[0]],
				[$tableNames[1]],
				);

		$this->mockDataSource->expects($this->never())->method('getData');

		$dataCollector = new DataCollector($this->mockDataSource, $this->mockConfig);
		$dataCollector->gatherData();
	}

	public function testDataSourceMethodsCalledWithColumns()
	{
		$tableNames = ['table0', 'table1'];
		$columnNames = ['column0', 'column1', 'column2', 'column3'];

		$this->mockDataSource->method('getAllTables')->willReturn($tableNames);
		$this->mockDataSource->method('getAllColumns')->with($this->isType('string'))->willReturn($columnNames);

		$this->mockDataSource->expects($this->exactly(count($tableNames)))->method('getData');

		$dataCollector = new DataCollector($this->mockDataSource, $this->mockConfig);
		$dataCollector->gatherData();
	}

	public function testGetData()
	{
		$tableNames = ['table0', 'table1'];
		$columnNames = ['column0', 'column1', 'column2', 'column3'];
		$data = ['x', 'x'];

		$this->mockDataSource->method('getAllTables')->willReturn($tableNames);
		$this->mockDataSource->method('getAllColumns')->with($this->isType('string'))->willReturn($columnNames);
		$this->mockDataSource->method('getData')->willReturn($data);

		$dataCollector = new DataCollector($this->mockDataSource, $this->mockConfig);
		$dataCollector->gatherData();

		$expectedData = [];
		foreach ($tableNames as $tableName) {
			$expectedData[$tableName] = $data;
		}

		assertSame($expectedData, $dataCollector->getData());
	}

	public function testNoExcludedColumn()
	{
		$this->mockConfig->excludedColumns = ['table0' => 'column2, column3'];

		$tableNames = ['table0'];
		$columnNames = ['column0', 'column1', 'column2', 'column3'];

		$this->mockDataSource->method('getAllTables')->willReturn($tableNames);
		$this->mockDataSource->method('getAllColumns')->with($this->isType('string'))->willReturn($columnNames);

		$this->mockDataSource->expects($this->once())->method('getData')->with('table0', ['column0', 'column1']);

		$dataCollector = new DataCollector($this->mockDataSource, $this->mockConfig);
		$dataCollector->gatherData();
	}

	public function testExcludedColumn()
	{
		$this->mockConfig->method('__get')->willThrowException(new InvalidMethodCallException(''));

		$tableNames = ['table0'];
		$columnNames = ['column0', 'column1', 'column2', 'column3'];

		$this->mockDataSource->method('getAllTables')->willReturn($tableNames);
		$this->mockDataSource->method('getAllColumns')->with($this->isType('string'))->willReturn($columnNames);

		$this->mockDataSource->expects($this->once())->method('getData')->with('table0', ['column0', 'column1', 'column2', 'column3']);

		$dataCollector = new DataCollector($this->mockDataSource, $this->mockConfig);
		$dataCollector->gatherData();
	}
}
