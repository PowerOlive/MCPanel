<?php
class CommonUtil {
	public $CI;
	public function __construct() {
		$CI = &get_instance();
		$this->CI = $CI;
	}
	public function UserExists($username = '') {
		if (@$this->CI->db->select("id,name,realname")->get_where("Member", ['name' => $username])->result()[0]) {
			return true;
		} else {
			return false;
		}
	}
	public function GetUserData($username = '') {
		return $this->CI->db->select("*")->get_where("Member", ['name' => $username])->result()[0];
	}
	public function fetchAPI($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_HTTPHEADER, []);
		$data = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			return false;
		}
		$result = (object) [];
		$result->code = $httpCode;
		$result->body = $data;
		return $result;
	}
	public function getUserInfo($username = '') {
		$this->CI->load->library("BakaRPC", null, "rpc");
		$this->CI->rpc->getInstance($this->CI->config->config['mcpanel']['url'], $this->CI->config->config['mcpanel']['key']);
		$result = $this->CI->rpc->APICall([
			"action" => "Players",
			"method" => "getInfo",
			"username" => $username,
		]);
		if (is_array($result) && isset($result['status'])) {
			return $result;
		} else {
			return false;
		}
	}
	public function isOnline($username = '') {
		$this->CI->load->library("BakaRPC", null, "rpc");
		$this->CI->rpc->getInstance($this->CI->config->config['mcpanel']['url'], $this->CI->config->config['mcpanel']['key']);
		$result = $this->CI->rpc->APICall([
			"action" => "Players",
			"method" => "getOnline",
		]);
		if (is_array($result) && in_array($username, $result['online'])) {
			return true;
		} else {
			return false;
		}
	}
	public function create_uuid($prefix = "") {
		$str = md5(uniqid(mt_rand(), true));
		$uuid = substr($str, 0, 8) . '-';
		$uuid .= substr($str, 8, 4) . '-';
		$uuid .= substr($str, 12, 4) . '-';
		$uuid .= substr($str, 16, 4) . '-';
		$uuid .= substr($str, 20, 12);
		return $prefix . $uuid;
	}
	public function kickPlayer($username = '', $content = '') {
		$this->CI->load->library("BakaRPC", null, "rpc");
		$this->CI->rpc->getInstance($this->CI->config->config['mcpanel']['url'], $this->CI->config->config['mcpanel']['key']);
		$result = $this->CI->rpc->APICall([
			"action" => "Players",
			"method" => "kickPlayer",
			"username" => $username,
			"content" => $content,
		]);
		if (is_array($result) && isset($result['status'])) {
			return true;
		} else {
			return false;
		}
	}
	public function sendMessage($username = '', $content = '') {
		$this->CI->load->library("BakaRPC", null, "rpc");
		$this->CI->rpc->getInstance($this->CI->config->config['mcpanel']['url'], $this->CI->config->config['mcpanel']['key']);
		$result = $this->CI->rpc->APICall([
			"action" => "Players",
			"method" => "sendMessage",
			"username" => $username,
			"content" => $content,
		]);
		if (is_array($result) && isset($result['status'])) {
			return true;
		} else {
			return false;
		}
	}
}