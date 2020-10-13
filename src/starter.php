<?php

use RRComparator\Manager\ApplicationManager;

include 'autoload.php';

$manager = new ApplicationManager(
	'../config/application.ini',
	'../config/scripts.ini',
	'../config/db.ini'
);
$manager->process();
