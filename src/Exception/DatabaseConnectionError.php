<?php

namespace RRComparator\Exception;

use Error;

class DatabaseConnectionError extends Error
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}