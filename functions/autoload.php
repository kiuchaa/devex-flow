<?php
/**
 * Autoload function files
 */

// Load all files in functions/hooks/
foreach (glob(__DIR__ . "/hooks/*.php") as $filename) {
	require_once $filename;
}

// Load any other php files in functions/
foreach(glob(__DIR__ . '/*.php') as $filename) {
	if (basename($filename) !== 'autoload.php') {
		require_once $filename;
	}
}