<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	public function Transfer() {
		//$this->load->driver('cache');
		//$this->cache->redis->save('foo', 'bar', 10);
		echo json_encode(['status' => 233, 'id' => $_GET['id']]);
	}
}