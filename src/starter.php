<?php

use RRComparator\Exception\InvalidConfigurationException;
use RRComparator\Manager\ComparisonManager;

include 'autoload.php';

try {
	$manager = new ComparisonManager(
		'../config/application.ini',
		'../config/scripts.ini',
		'../config/db.ini'
	);
	$manager->process();
} catch (InvalidConfigurationException $e) {

}
