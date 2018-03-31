<?php
class BakaRPC {
	public $timeout = 3;
	public $api_address;
	public $key;

	public function getInstance($api_address = '', $key = '') {
		$this->api_address = $api_address;
		$this->key = $key;
	}

	private function getSign($arr, $secret) {
		ksort($arr);
		return strtoupper(md5($this->Encode($arr) . "@" . $secret));
	}

	private function Encode($array) {
		$paramsJoined = array();
		foreach ($array as $param => $value) {
			$paramsJoined[] = "$param=$value";
		}
		$query = implode('&', $paramsJoined);
		return $query;
	}

	public function APICall($data) {
		$sign = $this->getSign($data, $this->key);
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->api_address . "?" . $this->Encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$headers = array();
		$headers[] = "Content-Type: application/json";
		$headers[] = "X-AuthorizeToken: " . $sign;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			return false;
		}
		curl_close($ch);
		return json_decode($result, true);
	}
}