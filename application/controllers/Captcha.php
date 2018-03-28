<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha extends CI_Controller {
	public function Check() {
		$this->load->library("GeetestLib", null, "Gtlib");
		$this->Gtlib->Init($this->config->config['geetest']['id'], $this->config->config['geetest']['key']);
		echo json_encode(['status' => $this->Gtlib->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'])]);
	}
	public function Start() {
		$this->load->library("GeetestLib", null, "Gtlib");
		$this->Gtlib->Init($this->config->config['geetest']['id'], $this->config->config['geetest']['key']);
		$this->Gtlib->pre_process();
		echo $this->Gtlib->get_response_str();
	}
}