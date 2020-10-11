<?php

namespace RRComparator\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers RRComparator\Exception\InvalidMethodCallException
 */
class InvalidMethodCallExceptionTest extends TestCase
{
	public function testConstruct()
	{
		$message = 'Test exception';
		$exception = new InvalidMethodCallException($message);

		$this->assertSame($message, $exception->getMessage());
	}
}
