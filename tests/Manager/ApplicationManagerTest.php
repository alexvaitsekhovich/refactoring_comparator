<?php

namespace RRComparator\Manager;


use PHPUnit\Framework\TestCase;

class ApplicationManagerTest extends TestCase
{
	public function testProcess()
	{
		$manager = new ApplicationManager(
			'tests/config/application.ini',
			'tests/config/scripts.ini',
			'tests/config/db.ini'
		);
		$manager->process();
		$this->expectOutputRegex('/OK/');
	}
}
