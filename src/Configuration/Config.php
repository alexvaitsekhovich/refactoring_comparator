<?php

namespace RRComparator\Configuration;

use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Exception\InvalidMethodCallException;
use RRComparator\Logger\ConsoleLogger;

/**
 * Configuration from the config file or data from only one section
 */
class Config
{
	private $configData = [];

	private function __construct()
	{
	}

	public static function init($configFile): Config
	{
		if (!file_exists($configFile)) {
			throw new InvalidConfigurationException("Config file not found: {$configFile}");
		}

		ConsoleLogger::log("Config: creating config from file '{$configFile}''");

		$config = new Config();
		$config->configData = parse_ini_file($configFile, true);
		return $config;
	}

	public function getSubConfig(string $section): Config
	{
		$subConfig = new Config();

		ConsoleLogger::log("Config: getting subconfig '{$section}'" );

		try {
			$subConfig->configData = $this->$section;
		} catch (InvalidMethodCallException $e) {
			throw new InvalidMethodCallException("No such subsection found: '{$section}'");
		}

		return $subConfig;
	}

	public function __get(string $name)
	{
		if (array_key_exists($name, $this->configData)) {
			return $this->configData[$name];
		}

		throw new InvalidMethodCallException("Undefined property: '{$name}'");
	}
}