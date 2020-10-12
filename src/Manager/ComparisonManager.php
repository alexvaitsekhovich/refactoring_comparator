<?php

namespace RRComparator\Manager;

use RRComparator\Configuration\Config;
use RRComparator\DataManagement\DataComparator;
use RRComparator\Datasource\DataToolsContainer;
use RRComparator\Processor\ProcessRunner;

/**
 * Instantiate the configurations, create and start process runners, get the mismatches
 * @codeCoverageIgnore
 */
class ComparisonManager
{
	private $appConfig;
	private $scriptsConfig;
	private $dbConfig;

	public function __construct(string $appConfigFile, string $scriptsConfigFile, string $dbConfigFile)
	{
		$this->appConfig = Config::init($appConfigFile);
		$this->scriptsConfig = Config::init($scriptsConfigFile);
		$this->dbConfig = Config::init($dbConfigFile);
	}

	public function process()
	{
		$dataSourceConfig = $this->getSubConfig('app', 'datasource');

		$dataToolsContainerLegacy = new DataToolsContainer($dataSourceConfig, $this->getSubConfig('db', 'legacydb'));
		$dataToolsContainerRefactored = new DataToolsContainer($dataSourceConfig, $this->getSubConfig('db', 'refactoreddb'));

		$legacyProcessRunner = new ProcessRunner($dataToolsContainerLegacy, $this->appConfig, $this->scriptsConfig->legacyScript);
		$refactoredProcessRunner = new ProcessRunner($dataToolsContainerRefactored, $this->appConfig, $this->scriptsConfig->refactoredScript);

		$legacyProcessRunner->process();
		$refactoredProcessRunner->process();

		$dataComparator = new DataComparator(
			$legacyProcessRunner->getResultingData(),
			$refactoredProcessRunner->getResultingData()
		);

		$mismatchResults = $dataComparator->getMismatchResult($this->getSubConfig('app', 'comparison')->limitDifferencesPerTable);

		if (count($mismatchResults) == 0) {
			echo "OK\n";
		} else {
			echo "Mismatch\n";
			print_r($mismatchResults);
		}
	}

	private function getSubConfig(string $mainConfig, string $name)
	{
		$config = $mainConfig . 'Config';
		return $this->$config->getSubConfig($name);
	}
}

