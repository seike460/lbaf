<?php
// @todo daemon lock
require_once('../core/autoLoad.php');
$storage = new lbaf_storage();
$response = new lbaf_response();
while (1 == 1) {
	$jsons = $storage->getJson();
	// @todo process fork
	foreach ($jsons as $json) {
		$storage::$mcon->beginTransaction();
		try {
			$response->process($json, $storage);
			$storage::$mcon->commit();
		}
		catch (Exception $e) {
			// @todo should make Utils::Log()
			error_log(var_export($e, true), 3, '../log/process.log');
			error_log(var_export($json, true), 3, '../log/process.log');
			$storage::$mcon->rollback();
		}
	}
	unset($jsons);
	sleep(1);
}
