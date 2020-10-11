<?php

namespace RRComparator\Processor;


use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RRComparator\Configuration\Config;
use RRComparator\Datasource\DataFixture;
use RRComparator\Datasource\DataToolsContainer;
use RRComparator\Exception\EmptyResultException;

/**
 * @covers RRComparator\Processor\ProcessRunner
 */
class ProcessRunnerTest extends TestCase
{
	private $mockDataToolsContainer;
	private $mockConfig;
	private $mockDataFixture;
	private $script = 'tests/test_data/testScript.php';

	protected function setUp(): void
	{
		$this->mockDataToolsContainer = $this->createMock(DataToolsContainer::class);
		$this->mockConfig = $this->createMock(Config::class);
		$this->mockDataFixture = $this->createMock(DataFixture::class);
	}


	public function testRunMethodsOnConstruct()
	{
		$this->mockDataToolsContainer->expects($this->once())->method('getDataSource');
		$this->mockDataToolsContainer->expects($this->once())->method('getDataFixture');

		new ProcessRunner($this->mockDataToolsContainer, $this->mockConfig, $this->script);
	}

	public function testGetResultingData()
	{
		$this->mockDataToolsContainer->method('getDataFixture')->willReturn($this->mockDataFixture);

		$this->mockDataFixture->expects($this->once())->method('populateData');
		$this->mockConfig->expects($this->exactly(2))->method('getSubConfig');

		$processRunner = new ProcessRunner($this->mockDataToolsContainer, $this->mockConfig, $this->script);
		$processRunner->process();
	}

	public function testNoData(){
		$processRunner = new ProcessRunner($this->mockDataToolsContainer, $this->mockConfig, $this->script);

		$reflectionClass = new ReflectionClass('RRComparator\Processor\ProcessRunner');
		$reflectionProperty = $reflectionClass->getProperty('resultData');
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($processRunner, []);

		$this->expectException(EmptyResultException::class);
		$processRunner->getResultingData();
	}

	public function testData(){
		$processRunner = new ProcessRunner($this->mockDataToolsContainer, $this->mockConfig, $this->script);
		$data = ['A', 'B'];

		$reflectionClass = new ReflectionClass('RRComparator\Processor\ProcessRunner');
		$reflectionProperty = $reflectionClass->getProperty('resultData');
		$reflectionProperty->setAccessible(true);
		$reflectionProperty->setValue($processRunner, $data);

		$this->assertSame($data, $processRunner->getResultingData());
	}

}
