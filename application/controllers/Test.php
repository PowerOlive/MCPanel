<?php
class Test extends CI_Controller {
	public function Get($username) {
		// $this->load->library('Qiniu');

		// $auth = new Qiniu\Auth($this->config->config['qiniu']['ak'], $this->config->config['qiniu']['sk']);
		// $bucketMgr = new Qiniu\Storage\BucketManager($auth);
		// $file_name = "skins/" . $username . ".png";
		// $token = $auth->uploadToken("satori", $file_name, 3600, null, true);
		// $uploadMgr = new Qiniu\Storage\UploadManager();

		// list($ret, $err) = $uploadMgr->putFile($token, $file_name, $_FILES['skins']['tmp_name']);
		// if (!$err) {
		// 	return [
		// 		'code' => 200,
		// 		'msg' => 'user.skin.change_success',
		// 	];
		// } else {
		// 	return [
		// 		'code' => 400,
		// 		'msg' => $err['error'],
		// 	];
		// }
		//$this->load->library("CommonUtil", null, "utils");
		//return $this->utils->sendMessage("KagurazakaSatori", '§a[服务器] §f当前时间: ' . date('Y-m-d H:i:s'));
	}
}