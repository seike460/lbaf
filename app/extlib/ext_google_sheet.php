<?php
Class ext_google_sheet {
	public function __construct ($sheetId) {
		$this->sheetId = $sheetId;
		$this->getPath = sprintf(
			'https://spreadsheets.google.com/feeds/cells/%s/od6/public/values?alt=json',
			$sheetId
		);
	}
	public function getsheetsVal () {
		return json_decode(file_get_contents($this->getPath),true);
		$val = rtrim($spreadsheetsVal['feed']['entry'][0]['content']['$t']);
	}
}
