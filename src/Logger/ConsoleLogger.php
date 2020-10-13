<?php


namespace RRComparator\Logger;


class ConsoleLogger
{
	public static function log(string $message): void
	{
		if (!empty($_SERVER['CONSOLE_LOG'])) {
			echo $message . "\n";
		}
	}
}