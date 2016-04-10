<?php
Class lbaf_response extends lbaf_response_core {

	/**
	 * return process result @boolean
	 */

	public function _process ($pData) {
		if ($this->pContent['contentType'] == lbaf_const::CTYPE_IMAGE) {
			return $this->sendSampleImage();
		}
		elseif ($this->pContent['contentType'] == lbaf_const::CTYPE_VIDEO) {
			return $this->sendSampleVideo();
		}
		elseif ($this->pContent['contentType'] == lbaf_const::CTYPE_AUDIO) {
			return $this->sendSampleAudio();
		}
		elseif ($this->pContent['contentType'] == lbaf_const::CTYPE_LOCATION) {
			return $this->sendSampleLocation();
		}
		elseif ($this->pContent['contentType'] == lbaf_const::CTYPE_STICKER) {
			return $this->sendSampleSticker();
		}
		else {
			return $this->sendSampleText();
		}
	}

	/**
	 * Text Sample
	 */
	public function sendSampleText () {
		$displayName = $this->pSendUserInfo['contacts'][0]['displayName'];
		$msg = <<<MSG
Hello {$displayName}
Please send a content
	- if you send image
		- return LINE Corporation LOGO
	- if you send video 
		- return LINE Corporation VIDEO On youtube
		  But Not Proxy Line Server ...
		  Please on Youtube ...
	- if you send audio
		- sorry in preparation now ...
	- if you send location
		- return LINE Corporation Location
	- if you send sticker
		- return moon sticker

If you modify the app/lbaf_response, you can have more variety of things.
MSG;
		$post = $this->createPostText(array($this->pContent['from']), $msg);
		return $this->apiPostRequest("events", $post);
	}

	/**
	 * IMAGE Sample
	 */
	public function sendSampleImage () {
		$post = $this->createPostImage(
			array($this->pContent['from']),
			'http://static.naver.jp/line_lp/img/logo.png',
			'http://static.naver.jp/line_lp/img/logo.png'
		);
		return $this->apiPostRequest("events", $post);
	}

	/**
	 * VIDEO Sample @todo put VIDEO LineServer And Link
	 */
	public function sendSampleVideo () {
		$post = $this->createPostAudio(
			array($this->pContent['from']),
			'https://www.youtube.com/watch?v=EIr3IWjZIfo',
			'http://static.naver.jp/line_lp/img/logo.png'
		);
		return $this->apiPostRequest("events", $post);
	}

	/**
	 * AUDIO Sample @todo put AUDIO LineServer And Link
	 */
	public function sendSampleAudio () {
		$post = $this->createPostImage(
			array($this->pContent['from']),
			'https://www.youtube.com/watch?v=EIr3IWjZIfo',
			'http://static.naver.jp/line_lp/img/logo.png'
		);
		return $this->apiPostRequest("events", $post);
	}

	/**
	 * Location Sample
	 */
	public function sendSampleLocation () {
		$post = $this->createPostLocation(
			array($this->pContent['from']),
			"35.6590249",
			"139.7012843",
			"ＬＩＮＥ株式会社",
			"ＬＩＮＥ株式会社"
		);
		return $this->apiPostRequest("events", $post);
	}

	/**
	 * Sticker Sample
	 */
	public function sendSampleSticker () {
		$post = $this->createPostSticker(
			array($this->pContent['from']),
			1,
			1,
			1,
			'STKTXT'
		);
		return $this->apiPostRequest("events", $post);
	}
}
