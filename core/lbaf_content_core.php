<?php
Class lbaf_content_core {

	//@todo maybe should output coreConfig
	public static $toChannel		='1383378250';
	public static $basicEventType	='138311608800106203';
	public static $multiEventType	='140177271400161403';

	protected $bot	= false;
	protected function __construct () {
		$this->bot = require_once('../config/lineBot.php');
	}

	protected function getApiUrl ($pathAndQuery) {
		return sprintf($this->bot['apiBase'], $pathAndQuery);
	}

	protected function getBotchannelId () {
		return $this->bot['channelId'];
	}

	protected function getBotchannelSecret () {
		return $this->bot['channelSecret'];
	}

	protected function getBotMid () {
		return $this->bot['mid'];
	}

	protected function createPostHeaders () {
		return array (
			"Content-Type: application/json",
			"X-Line-ChannelID: {$this->getBotchannelId()}",
			"X-Line-ChannelSecret: {$this->getBotchannelSecret()}",
			"X-Line-Trusted-User-With-ACL: {$this->getBotMid()}"
		);
	}

	protected function curlSet ($url, $headers, $post=false) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if ($post !== false) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		return $curl;
	}

	public function apiPostRequest($path, $post) {
		$url		= $this->getApiUrl ($path);
		$headers	= $this->createPostHeaders();
		$curl		= $this->curlSet ($url, $headers, $post);
		$output = curl_exec($curl);
		if ($this->processLogging === true) {
			$this->processLog($output);
		}
		return true;
	}

	public function apiGetUserRequest($mid) {
		$pathAndQuery = "profiles?mids={$mid}";
		return $this->apiGetRequest($pathAndQuery);
	}

	public function apiGetRequest($pathAndQuery) {
		$url		= $this->getApiUrl ($pathAndQuery);
		$headers	= $this->createPostHeaders();
		$curl		= $this->curlSet ($url, $headers);
		$output = curl_exec($curl);
		return json_decode($output, true);
	}

	public function createPost ($to, $content) {
		$post = array(
			"to"		=>	$to,
			"toChannel"	=>	lbaf_content_core::$toChannel,
			"eventType"	=>	lbaf_content_core::$basicEventType,
			"content"	=>	$content,
		);
		return json_encode(
			$post,
			JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
		);
	}

	public function createPostText ($to, $text) {
		$content = array(
			"contentType"	=>	lbaf_const::CTYPE_TEXT,
			"toType"		=>	1,
			"text"			=>	$text,
		);
		return $this->createPost($to, $content);
	}

	public function createPostImage ($to, $originalContentUrl, $previewImageUrl) {
		$content = array(
			"contentType"			=>	lbaf_const::CTYPE_IMAGE,
			"toType"				=>	1,
			"originalContentUrl"	=>	"{$originalContentUrl}",
			"previewImageUrl"		=>	"{$previewImageUrl}",
		);
		return $this->createPost($to, $content);
	}

	public function createPostVideo ($to, $originalContentUrl, $previewImageUrl) {
		$content = array(
			"contentType"			=>	lbaf_const::CTYPE_VIDEO,
			"toType"				=>	1,
			"originalContentUrl"	=>	"{$originalContentUrl}",
			"previewImageUrl"		=>	"{$previewImageUrl}",
		);
		return $this->createPost($to, $content);
	}

	public function createPostAudio ($to, $originalContentUrl) {
		$content = array(
			"contentType"			=>	lbaf_const::CTYPE_AUDIO,
			"toType"				=>	1,
			"originalContentUrl"	=>	"{$originalContentUrl}",
			"contentMetadata"		=>	array (
				"AUDLEN"	=>	"240000",
			),
		);
		return $this->createPost($to, $content);
	}

	public function createPostLocation ($to, $lat, $lon, $title='', $text='') {
		$content = array(
			"contentType"			=>	lbaf_const::CTYPE_LOCATION,
			"toType"				=>	1,
			"text"	=>	"{$text}",
			"location"		=>	array (
				"title"		=>	"$title",
				"latitude"	=>	"$lat",
				"longitude"	=>	"$lon",
			)
		);
		return $this->createPost($to, $content);
	}

	public function createPostSticker ($to, $stkid, $stkpkgid, $stkver) {
		$content = array(
			"contentType"		=>	lbaf_const::CTYPE_STICKER,
			"toType"			=>	1,
			"contentMetadata"	=>	array (
				"STKID"		=>	"$stkid",
				"STKPKGID"	=>	"$stkpkgid",
				"STKVER"	=>	"$stkver",
			)
		);
		return $this->createPost($to, $content);
	}
}
