<?php

namespace RRComparator\Exception;

use Exception;

class InvalidMethodCallException extends Exception
{

	public function __construct($message)
	{
		parent::__construct($message);
	}
}