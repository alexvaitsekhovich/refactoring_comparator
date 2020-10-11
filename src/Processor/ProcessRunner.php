<?php

namespace RRComparator\Processor;

use RRComparator\Configuration\Config;
use RRComparator\Datasource\DataToolsContainer;
use RRComparator\Exception\EmptyResultException;

/**
 * Trigger data fixtures, run the script and retrieve the data
 */
class ProcessRunner
{
	private $dataSource;
	private $dataFixture;
	private $config;
	private $script;

	private $resultData;

	public function __construct(DataToolsContainer $dataToolsContainer, Config $config, string $script)
	{
		$this->dataSource = $dataToolsContainer->getDataSource();
		$this->dataFixture = $dataToolsContainer->getDataFixture();
		$this->config = $config;
		$this->script = $script;
	}

	public function process()
	{
		$this->dataFixture->populateData();

		$scriptRunner = new ScriptRunner($this->config->getSubConfig('scriptArgs'), $this->script);
		$scriptRunner->run();

		$dataContainer = new DataCollector($this->dataSource, $this->config->getSubConfig('db'));
		$dataContainer->gatherData();

		$this->resultData = $dataContainer->getData();
	}

	public function getResultingData(): array
	{
		if (empty($this->resultData)) {
			throw new EmptyResultException("No data was retrieved after executing the script: " . $this->script);
		}
		return $this->resultData;
	}
}