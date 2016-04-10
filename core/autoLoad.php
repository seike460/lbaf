<?php
spl_autoload_register(
	function($_className) {
		//@todo file memory cache
		$dirs = array (
			'../core',
			'../app',
			'../app/extlib',
		);
		foreach ($dirs as $dir) {
			$file = "{$dir}/{$_className}.php";
			if (is_file($file) === true) {
				require $file;
				return;
			}
		}
	}
);
