<?php

namespace RRComparator\Processor;

use RRComparator\DataManagement\DataFixture;
use RRComparator\Exception\EmptyResultException;

/**
 * Trigger data fixtures, run the script and retrieve the data
 */
class ProcessRunner
{
	private $dataFixture;
	private $scriptRunner;
	private $dataCollector;

	private $resultData;

	public function __construct(ScriptRunner $scriptRunner, DataCollector $dataCollector, DataFixture $dataFixture)
	{
		$this->scriptRunner = $scriptRunner;
		$this->dataCollector = $dataCollector;
		$this->dataFixture = $dataFixture;
	}

	public function process()
	{
		$this->dataFixture->populateData();

		$this->scriptRunner->run();

		$this->dataCollector->gatherData();

		$this->resultData = $this->dataCollector->getData();
	}

	public function getResultingData(): array
	{
		if (empty($this->resultData)) {
			throw new EmptyResultException("No data was retrieved after executing the script");
		}
		return $this->resultData;
	}
}