<?php

namespace RRComparator\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers RRComparator\Exception\EmptyResultException
 */
class EmptyResultExceptionTest extends TestCase
{
	public function testConstruct()
	{
		$message = 'Test exception';
		$exception = new EmptyResultException($message);

		$this->assertSame($message, $exception->getMessage());
	}
}
