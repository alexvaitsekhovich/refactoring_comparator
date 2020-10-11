<?php

namespace RRComparator\DataManagement;

use PHPUnit\Framework\TestCase;
use RRComparator\Exception\DataComparisonException;

/**
 * @covers RRComparator\DataManagement\DataComparator
 */
class DataComparatorTest extends TestCase
{

	public function testTablesCountMismatch()
	{
		$this->expectException(DataComparisonException::class);
		$this->expectExceptionMessageMatches('/^Tables count mismatch/');

		$legacyData = [
			'table1' => [],
			'table2' => [],
		];

		$refactoredData = [
			'table1' => []
		];

		$dataComparator = new DataComparator($legacyData, $refactoredData);
		$dataComparator->getMismatchResult();
	}

	public function testRowsCountMismatch()
	{
		$mismatchTable = 'table1';

		$this->expectException(DataComparisonException::class);
		$this->expectExceptionMessageMatches('/^Rows count mismatch/');
		$this->expectExceptionMessageMatches("/{$mismatchTable}/");

		$legacyData = [
			$mismatchTable => [[], []],
			'table2' => [[], []]
		];

		$refactoredData = [
			$mismatchTable => [[]],
			'table2' => [[], []]
		];

		$dataComparator = new DataComparator($legacyData, $refactoredData);
		$dataComparator->getMismatchResult();
	}

	public function testDataMismatch()
	{
		$mismatchTable = 'table1';

		$legacyData = [
			$mismatchTable => [
				['id' => 1, 'name' => 'A'],
				['id' => 2, 'name' => 'B']
			],
		];

		$refactoredData = [
			$mismatchTable => [
				['id' => 1, 'name' => 'A'],
				['id' => 2, 'name' => 'X']
			],
		];

		$dataComparator = new DataComparator($legacyData, $refactoredData);
		$mismatchResult = $dataComparator->getMismatchResult();

		$this->assertSame($legacyData[$mismatchTable], $mismatchResult[$mismatchTable]['expected']);
		$this->assertSame($refactoredData[$mismatchTable], $mismatchResult[$mismatchTable]['actual']);
	}

	public function testDataMismatchLimited()
	{
		$mismatchTable = 'table1';

		$legacyData = [
			$mismatchTable => [
				['id' => 1, 'name' => 'A'],
				['id' => 2, 'name' => 'B']
			],
		];

		$refactoredData = [
			$mismatchTable => [
				['id' => 1, 'name' => 'A'],
				['id' => 2, 'name' => 'X']
			],
		];

		$dataComparator = new DataComparator($legacyData, $refactoredData);
		$mismatchResult = $dataComparator->getMismatchResult(1);

		$this->assertSame($legacyData[$mismatchTable][1], $mismatchResult[$mismatchTable]['expected'][0]);
		$this->assertSame($refactoredData[$mismatchTable][1], $mismatchResult[$mismatchTable]['actual'][0]);
	}

	public function testDataMatches()
	{
		$data = [
			'table1' => [
				['id' => 1, 'name' => 'A'],
				['id' => 2, 'name' => 'B']
			],
			'table2' => [
				['id' => 10, 'amount' => 100],
				['id' => 11, 'amount' => 200]
			],
		];

		$legacyData = $data;
		$refactoredData = $data;

		$dataComparator = new DataComparator($legacyData, $refactoredData);
		$mismatchResult = $dataComparator->getMismatchResult();

		$this->assertEmpty($mismatchResult, 'Mismatch was found!');
	}
}
