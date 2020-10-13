<?php

namespace RRComparator\Manager;

use RRComparator\Configuration\Config;
use RRComparator\DataManagement\DataComparator;
use RRComparator\DataManagement\DataToolsContainer;
use RRComparator\Processor\DataCollector;
use RRComparator\Processor\ProcessRunner;
use RRComparator\Processor\ScriptRunner;

/**
 * Instantiate the configurations, create and start process runners, get the mismatches
 */
class ApplicationManager
{
	private $legacyProcessRunner;
	private $refactoredProcessRunner;

	private $diffLimit;

	public function __construct(string $appConfigFile, string $scriptsConfigFile, string $dbConfigFile)
	{
		$appConfig = Config::init($appConfigFile);
		$scriptsConfig = Config::init($scriptsConfigFile);
		$this->dbConfig = Config::init($dbConfigFile);

		$this->diffLimit = $appConfig->getSubConfig('comparison')->limitDifferencesPerTable;

		$dataSourceConfig = $appConfig->getSubConfig('datasource');

		$dataToolsContainerLegacy = new DataToolsContainer($dataSourceConfig, $this->dbConfig->getSubConfig('legacydb'));
		$dataToolsContainerRefactored = new DataToolsContainer($dataSourceConfig, $this->dbConfig->getSubConfig('refactoreddb'));

		$legacyScriptRunner = new ScriptRunner($appConfig->getSubConfig('scriptArgs'), __DIR__ . '/' . $scriptsConfig->legacyScript);
		$refactoredScriptRunner = new ScriptRunner($appConfig->getSubConfig('scriptArgs'), __DIR__ . '/' . $scriptsConfig->refactoredScript);

		$legacyDataContainer = new DataCollector($dataToolsContainerLegacy->getDataSource(), $appConfig->getSubConfig('db'));
		$refactoredDataContainer = new DataCollector($dataToolsContainerRefactored->getDataSource(), $appConfig->getSubConfig('db'));

		$this->legacyProcessRunner = new ProcessRunner($legacyScriptRunner, $legacyDataContainer, $dataToolsContainerLegacy->getDataFixture());
		$this->refactoredProcessRunner = new ProcessRunner($refactoredScriptRunner, $refactoredDataContainer, $dataToolsContainerRefactored->getDataFixture());
	}

	public function process()
	{
		$this->legacyProcessRunner->process();
		$this->refactoredProcessRunner->process();

		$dataComparator = new DataComparator(
			$this->legacyProcessRunner->getResultingData(),
			$this->refactoredProcessRunner->getResultingData()
		);

		$mismatchResults = $dataComparator->getMismatchResult($this->diffLimit);

		if (count($mismatchResults) == 0) {
			echo "OK\n";
		} else {
			echo "Mismatch\n";
			print_r($mismatchResults);
		}
	}
}

