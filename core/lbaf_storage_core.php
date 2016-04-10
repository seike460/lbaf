<?php
Class lbaf_storage_core {

	private $conInfo = false;
	public static $type	= false;
	public static $mcon = false;

	// @todo make Slave Connection
	public static $con_s = false;
	public function getConSlave () {
	}

	public function __construct () {
		//@todo should config memory Cache
		$this->conInfo = require_once('../config/storage.php');
		if (lbaf_storage_core::$mcon === false) {
			$this->getCon();
		}
		$this::$mcon = lbaf_storage_core::$mcon;
	}
	public function getCon () {
		if (lbaf_storage_core::$mcon !== false) {
			return lbaf_storage_core::$mcon;
		}
		$dsn = $this->getDsn();
		$db = new PDO(
			$dsn,
			$this->getUser(),
			$this->getPass()
		);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		lbaf_storage_core::$mcon = $db;
		return $db;
	}

	public function complete ($id) {
		$db = $this->getCon();
		$sql = <<<SQL
DELETE FROM
	line_api_json
WHERE
	id = {$id}
SQL;
		$stmt = $db->prepare($sql);
		try {
			$stmt->execute();
			unset($stmt);
		}
		catch (Exception $e) {
			error_log(var_export($e, true));
			unset($stmt);
			return false;
		}
		return true;
	}

	public function getJson () {
		$db = $this->getCon();
		$sql = <<<SQL
SELECT
	id,
	json
FROM
	line_api_json
SQL;
		$stmt = $db->prepare($sql);
		try {
			$stmt->execute();
			$jsons = $stmt->fetchAll();
			unset($stmt);
		}
		catch (Exception $e) {
			error_log(var_export($e, true));
		}
		return $jsons;
	}

	/**
	 * must not use Transaction
	 */
	public function store ($data) {
		$db = $this->getCon();
		$sql = <<<SQL
		INSERT INTO line_api_json (
		  json
		)
		VALUES (
		  '{$data}'
		)
SQL;
		$stmt = $db->prepare($sql);
		try {
			$stmt->execute();
			unset($stmt);
		}
		catch (Exception $e) {
			error_log(var_export($e, true));
			return false;
		}
		return true;
	}

	public function getDsn () {
		$endpoint	= $this->getEndPoint();
		$port		= $this->getPort();
		$target		= $this->getTarget();
		return "mysql:host={$endpoint};port={$port};dbname={$target};";
	}
	public function getEndPoint () {
		return $this->conInfo['connect_info']['endpoint'];
	}
	public function getPort () {
		return $this->conInfo['connect_info']['port'];
	}
	public function getTarget () {
		return $this->conInfo['connect_info']['target'];
	}
	public function getUser () {
		return $this->conInfo['connect_info']['user'];
	}
	public function getPass () {
		return $this->conInfo['connect_info']['pass'];
	}
}
