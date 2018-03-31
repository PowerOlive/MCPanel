<?php
class Test extends CI_Controller {
	public function Get() {
		$this->load->library("BakaRPC", null, "rpc");
		$this->rpc->getInstance("http://127.0.0.1:8000/", "baka2333");
		$result = $this->rpc->APICall([
			"action" => "Players",
			"method" => "getInfo",
			"username" => "KagurazakaSatori",
			// "content" => "咕噜咕噜",
		]);
		return $result;
	}
}