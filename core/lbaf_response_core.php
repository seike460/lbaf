<?php
Class lbaf_response_core extends lbaf_content_core {
	// userCreateProcess
	public function _process ($pData) {}

	//@todo should output config
	public $processLogging = true;
	public function __construct () {
		parent::__construct();
		// @todo readConfig
	}

	public $pContent = null;
	public $pSendUserInfo = null;

	public function processLog ($pData) {
		// @todo should make Utils::Log()
		error_log(date('Y-m-d H:i:s') . "\n", 3, '../log/process.log');
		error_log(var_export($pData, true), 3, '../log/process.log');
	}

	public function setProcessData($pData) {
		$this->pContent = $pData['content'];
		$this->pContent['text'] = trim($this->pContent['text']);
		$this->pSendUserInfo = $this->apiGetUserRequest($this->pContent['from']);
	}

	public function process($row, $storage) {
		$json = json_decode($row['json'] ,true);
		$processDatas = $json['result'];
		if ($processDatas === null) {
			$storage->complete($row['id']);
			return;
		}
		foreach ($processDatas as $pNum => $pData) {
			if ($this->processLogging === true) {
				$this->processLog($pData);
			}
			$this->setProcessData($pData);
			if ($this->_process($pData) === true) {
				$storage->complete($row['id']);
				if ($this->processLogging === true) {
					$this->processLog('complete');
				}
			}
			else {
				if ($this->processLogging === true) {
					$this->processLog("{$row['id']} ng !!");
					$this->processLog($pData);
					// @todo Count Error And Stop Judge And Notification
					die('end process ... ');
				}
			}
		}
	}
}
