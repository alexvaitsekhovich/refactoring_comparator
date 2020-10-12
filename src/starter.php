<?php

use RRComparator\Manager\ComparisonManager;

include 'autoload.php';

$manager = new ComparisonManager(
	'../config/application.ini',
	'../config/scripts.ini',
	'../config/db.ini'
);
$manager->process();
