<?php

namespace RRComparator\Exception;

use Exception;

class InvalidConfigurationException extends Exception
{

	public function __construct($message)
	{
		parent::__construct($message);
	}
}