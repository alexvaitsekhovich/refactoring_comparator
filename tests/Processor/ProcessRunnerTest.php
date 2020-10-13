<?php

namespace RRComparator\Processor;


use PHPUnit\Framework\TestCase;
use RRComparator\DataManagement\DataFixture;
use RRComparator\Exception\EmptyResultException;

/**
 * @covers RRComparator\Processor\ProcessRunner
 */
class ProcessRunnerTest extends TestCase
{
	private $mockScriptRunner;
	private $mockDataCollector;
	private $mockDataFixture;

	protected function setUp(): void
	{
		$this->mockScriptRunner = $this->createMock(ScriptRunner::class);
		$this->mockDataCollector = $this->createMock(DataCollector::class);
		$this->mockDataFixture = $this->createMock(DataFixture::class);
	}

	public function testProcess()
	{
		$this->mockScriptRunner->expects($this->once())->method('run');
		$this->mockDataCollector->expects($this->once())->method('gatherData');
		$this->mockDataCollector->expects($this->once())->method('getData');
		$this->mockDataFixture->expects($this->once())->method('populateData');

		(new ProcessRunner($this->mockScriptRunner,
			$this->mockDataCollector, $this->mockDataFixture))->process();
	}

	public function testGetResultingData()
	{
		$data = ['A' => 'Test'];

		$this->mockDataCollector->method('getData')->willReturn($data);

		$processRunner = new ProcessRunner($this->mockScriptRunner, $this->mockDataCollector, $this->mockDataFixture);
		$processRunner->process();

		$this->assertSame($data, $processRunner->getResultingData());
	}

	public function testNoData()
	{
		$this->mockDataCollector->method('getData')->willReturn([]);

		$processRunner = new ProcessRunner($this->mockScriptRunner, $this->mockDataCollector, $this->mockDataFixture);
		$processRunner->process();

		$this->expectException(EmptyResultException::class);
		$processRunner->getResultingData();
	}
}
