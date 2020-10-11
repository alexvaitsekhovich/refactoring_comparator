<?php

namespace RRComparator\Processor;

use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;

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

		$this->shellScript = escapeshellarg($script);

		if (!empty($config->scriptCommandLineArgs)) {
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
		shell_exec("php " . $this->shellScript);
	}
}