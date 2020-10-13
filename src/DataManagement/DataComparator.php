<?php

namespace RRComparator\DataManagement;

use RRComparator\Exception\DataComparisonException;
use RRComparator\Logger\ConsoleLogger;

/**
 * Compare the database data resulting from legacy and from refactored scripts. If mismatch was found,
 * show only $limit rows per table, or all rows if $limit=0
 */
class DataComparator
{
	private $legacyData;
	private $refactoredData;

	public function __construct(array $legacyData, array $refactoredData)
	{
		$this->legacyData = $legacyData;
		$this->refactoredData = $refactoredData;
	}

	/*
	 * @param $limit - maximal amount of mismatching entries per table
	 */
	public function getMismatchResult($limit = 0): array
	{
		$mismatchResult = [];

		ConsoleLogger::log(sprintf("DataComparator: comparing data with data count %d vs %d",
			count($this->legacyData), count($this->refactoredData)));

		if (count($this->legacyData) != count($this->refactoredData)) {
			throw new DataComparisonException(sprintf('Tables count mismatch: %d elements in legacy, %d elements in refactored',
				count($this->legacyData), count($this->refactoredData)));
		}

		foreach ($this->legacyData as $table => $legacyRows) {
			ConsoleLogger::log("DataComparator: comparing rows of table '{$table}'");

			$refactoredRows = $this->refactoredData[$table];

			if (count($legacyRows) != count($refactoredRows)) {
				throw new DataComparisonException(sprintf('Rows count mismatch in table "%s": %d elements in legacy, %d elements in refactored',
					$table, count($legacyRows), count($refactoredRows)));
			}

			if ($legacyRows !== $refactoredRows) {
				ConsoleLogger::log("DataComparator: found mismatch in table '{$table}'");

				if ($limit == 0) {
					$mismatchResult[$table] = [
						'expected' => $legacyRows,
						'actual' => $refactoredRows
					];
				}
				else {
					$mismatchResult[$table] = [
						'expected' => [],
						'actual' => []
					];

					foreach ($legacyRows as $ind => $legacyRow) {
						if ($legacyRow != $refactoredRows[$ind]) {
							$mismatchResult[$table]['expected'][] = $legacyRow;
							$mismatchResult[$table]['actual'][] = $refactoredRows[$ind];
						}
					}
				}
			}
		}

		return $mismatchResult;
	}
}