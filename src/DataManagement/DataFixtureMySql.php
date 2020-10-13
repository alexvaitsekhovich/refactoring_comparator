<?php

namespace RRComparator\DataManagement;

use RRComparator\Logger\ConsoleLogger;

/**
 * DataFixture implementation for MySQL
 */
class DataFixtureMySql extends DataFixture
{
	public function populateData(): void
	{
		$resetScriptsDir = $this->config->dbInitDir;

		ConsoleLogger::log("DataFixtureMySql: found init script {$resetScriptsDir}");

		foreach (scandir($resetScriptsDir) as $f) {
			$sqlFile = $resetScriptsDir . DIRECTORY_SEPARATOR . $f;

			ConsoleLogger::log("DataFixtureMySql: found file {$sqlFile}");

			if (!$this->isFileAndExpectedType($sqlFile, 'sql')) {
				continue;
			}

			$query = file_get_contents($sqlFile);

			ConsoleLogger::log("DataFixtureMySql: found queries in file {$sqlFile}");

			$sqlCommands = explode(';', $query);

			foreach ($sqlCommands as $sqlCommand) {
				$sqlCommand = trim($sqlCommand);
				if (strlen($sqlCommand) == 0) {
					continue;
				}

				ConsoleLogger::log("DataFixtureMySql: executing query {$sqlCommand}");

				$this->dataConnection->executeRawQuery($sqlCommand);
			}
		}

		$datasetDir = $this->config->dbDatasetDir;

		foreach (scandir($datasetDir) as $ds) {
			$datasetFile = $datasetDir . DIRECTORY_SEPARATOR . $ds;

			ConsoleLogger::log("DataFixtureMySql: getting dataset from file {$datasetFile}");

			if (!$this->isFileAndExpectedType($datasetFile, 'csv')) {
				continue;
			}

			$tableName = pathinfo($datasetFile)['filename'];

			$datasetCommand = "LOAD DATA INFILE '" . realpath($datasetFile) . "' INTO TABLE `" . $tableName . "` FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\\n' IGNORE 1 LINES;";

			ConsoleLogger::log("DataFixtureMySql: executing query {$datasetCommand}");

			$this->dataConnection->executeRawQuery($datasetCommand);
		}
	}

	private function isFileAndExpectedType(string $path, string $type): bool
	{
		if (is_dir($path)) {
			return false;
		}

		$path_parts = pathinfo($path);
		if ($path_parts['extension'] != $type) {
			return false;
		}

		return true;
	}

}