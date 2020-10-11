<?php

namespace RRComparator\Exception;

use Exception;

class DataComparisonException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}