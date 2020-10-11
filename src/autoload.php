<?php

spl_autoload_register(function ($classname) {

	$parts = explode('\\', $classname);
	$classname = end($parts);

	$rootPath = dirname(realpath(__FILE__));


	foreach (scandir($rootPath) as $subdir) {
		$localPath = $rootPath . DIRECTORY_SEPARATOR . $subdir;

		if (!is_dir($localPath) || in_array($subdir, ['.','..'])) continue;

		$file = $localPath . DIRECTORY_SEPARATOR . $classname . ".php";

		if (file_exists($file)) {
			require_once($file);
			break;
		}
	}
});
