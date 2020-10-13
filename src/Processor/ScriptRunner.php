<?php

namespace RRComparator\Processor;

use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Logger\ConsoleLogger;

/**
 * Run the shell script with arguments
 */
class ScriptRunner
{
	private $shellScript;

	public function __construct(Config $config, string $script)
	{
		if (empty($script)) {
			throw new InvalidConfigurationException("No script was defined");
		}

		if (!file_exists($script)) {
			throw new InvalidConfigurationException("Script not found: {$script}");
		}

		ConsoleLogger::log("ScriptRunner: init with script '{$script}");

		$this->shellScript = escapeshellarg($script);

		if (is_array($config->scriptCommandLineArgs) && count($config->scriptCommandLineArgs) > 0) {
			foreach ($config->scriptCommandLineArgs as $argument) {
				$this->shellScript .= " " . escapeshellarg($argument);
			}
		}
	}

	public function getShellScript(): string
	{
		return $this->shellScript;
	}

	public function run()
	{
		ConsoleLogger::log("ScriptRunner: executing '{$this->shellScript}");

		shell_exec("php " . $this->shellScript);
	}
}