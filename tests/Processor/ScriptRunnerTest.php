<?php

namespace RRComparator\Processor;

use PHPUnit\Framework\TestCase;
use RRComparator\Configuration\Config;
use RRComparator\Exception\InvalidConfigurationException;

/**
 * @covers RRComparator\Processor\ScriptRunner
 */
class ScriptRunnerTest extends TestCase
{
	private $mockConfig;

	protected function setUp(): void
	{
		$this->mockConfig = $this->createMock(Config::class);
	}

	public function testInitNoScript()
	{
		$this->expectException(InvalidConfigurationException::class);
		$this->expectExceptionMessage('No script was defined');

		new ScriptRunner($this->mockConfig, "");
	}

	public function testScriptDoesNotExist()
	{
		$this->expectException(InvalidConfigurationException::class);
		$this->expectExceptionMessageMatches('/^Script not found/');

		new ScriptRunner($this->mockConfig, "no_such_file.php");
	}

	public function testShellArgs()
	{
		$script = 'tests/test_data/testScript.php';
		$arg1 = 1;
		$arg2 = 'dev';
		$this->mockConfig->scriptCommandLineArgs = [$arg1, $arg2];

		$scriptRunner = new ScriptRunner($this->mockConfig, $script);

		$scriptCommand = $scriptRunner->getShellScript();

		$escScript = escapeshellarg($script);
		$escArg1 = escapeshellarg($arg1);
		$escArg2 = escapeshellarg($arg2);
		$this->assertSame("$escScript $escArg1 $escArg2", $scriptCommand);
	}
}
