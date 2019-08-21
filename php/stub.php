<?php
spl_autoload_register(function ($name) {
	$file = dirname(__FILE__) . '/' . str_replace('\\', '/', $name) . '.php';
	if (file_exists($file))
		require_once $file;
});
