<?php

namespace RRComparator\Exception;

use Exception;

class EmptyResultException extends Exception
{

	public function __construct($message)
	{
		parent::__construct($message);
	}
}