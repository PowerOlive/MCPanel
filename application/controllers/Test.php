<?php
class Test extends CI_Controller {
	public function Get() {
		$this->load->library("CommonUtil", null, "utils");
		return $this->utils->sendMessage("KagurazakaSatori", '§a[服务器] §f当前时间: ' . date('Y-m-d H:i:s'));
	}
}