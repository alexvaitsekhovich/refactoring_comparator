<?php

namespace RRComparator\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers RRComparator\Exception\DataComparisonException
 */
class DataComparisonExceptionTest extends TestCase
{
	public function testConstruct()
	{
		$message = 'Test exception';
		$exception = new DataComparisonException($message);

		$this->assertSame($message, $exception->getMessage());
	}
}
