<?php

namespace RRComparator\Configuration;

use PHPUnit\Framework\TestCase;
use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Exception\InvalidMethodCallException;

/**
 * @covers RRComparator\Configuration\Config
 */
class ConfigTest extends TestCase
{
	private $config1;
	private $config2;

	public function setUp(): void
	{
		$this->config1 = Config::init('tests/test_data/test1.ini');
		$this->config2 = Config::init('tests/test_data/test2.ini');
	}

	public function testInitSuccess()
	{
		$this->assertInstanceOf(Config::class, $this->config1);
	}

	public function testInitFailure()
	{
		$this->expectException(InvalidConfigurationException::class);
		Config::init('tests/testconf/notfound.ini');
	}

	public function testGetPropertySuccess()
	{
		$singleParam = $this->config1->singleParam;
		$this->assertSame('TestParam', $singleParam);

		$multParams = $this->config1->multParams;
		$expectedMultParams = ['!', 'A', '1'];

		$this->assertSame(sort($expectedMultParams), sort($multParams));
	}

	public function testGetPropertyFailure()
	{
		$this->expectException(InvalidMethodCallException::class);
		$this->config1->noSuchProperty;
	}

	public function testNotExistingSubConfig()
	{
		$this->expectException(InvalidMethodCallException::class);
		$this->config2->getSubConfig('not-existing');
	}

	public function testGetSubConfigSingleParameter()
	{
		$configSub2 = $this->config2->getSubConfig('sub3');

		$singleParam = $configSub2->singleParam;
		$this->assertSame('TestParam', $singleParam);
	}

	/**
	 * @dataProvider subconfigDataProvider
	 */
	public function testGetSubConfigMultipleParameters(string $subconfig, array $expected)
	{
		$configSub = $this->config2->getSubConfig($subconfig);
		$multParams = $configSub->multipleParams;
		$this->assertSame(sort($expected), sort($multParams));
	}

	public function subconfigDataProvider()
	{
		return [
			['sub1', ['!', 'A', '1']],
			['sub2', ['A' => 'xyz', 'B' => 'xyz', 'C' => 'abc']],
		];
	}
}
