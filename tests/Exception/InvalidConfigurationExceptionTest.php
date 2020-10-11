<?php

namespace RRComparator\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers RRComparator\Exception\InvalidConfigurationException
 */
class InvalidConfigurationExceptionTest extends TestCase
{
	public function testConstruct()
	{
		$message = 'Test exception';
		$exception = new InvalidConfigurationException($message);

		$this->assertSame($message, $exception->getMessage());
	}
}
