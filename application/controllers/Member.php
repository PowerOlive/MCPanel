<?php
class Member extends CI_Controller {
	// public function get($username = '', $token = '') {
	// 	$this->load->driver('cache');
	// 	$name = strtolower($username);
	// 	if ($this->cache->redis->get($name) !== $token) {
	// 		exit(json_encode([
	// 			'code' => 401,
	// 		]));
	// 	}
	// 	$user = $this->db->select("id,name,realname")->get_where("Member", ['name' => $name])->result()[0];
	// 	$balance = $this->db->select("username,balance")->get_where("Balance", ["username" => $name])->result()[0];
	// 	$user->id = intval($user->id);
	// 	$user->balance = $balance->balance;
	// 	$user->status = 200;
	// 	exit(json_encode($user));
	// }
	public function getSelf($token = '') {
		$this->load->driver('cache');

		if (!$name = $this->cache->redis->get($token)) {
			return [
				'code' => 401,
			];
		}

		$user = $this->db->select("id,name,realname")->get_where("Member", ['name' => $name])->result()[0];
		$balance = $this->db->select("username,balance")->get_where("Balance", ["username" => $name])->result()[0];
		$user->id = intval($user->id);
		$user->balance = $balance->balance;
		$user->status = 200;
		exit(json_encode($user));
	}
	public function Login($username = '', $password = '') {
		$this->load->driver('cache');
		$name = strtolower($username);
		if (!$this->UserExists($name)) {
			return [
				'code' => 404,
			];

		}
		if ($this->GetUserData($name)->password !== hash("sha512", $password)) {
			return [
				'code' => 403,
			];
		}
		$token = md5(uniqid());
		if (!$rtoken = $this->cache->redis->get($name)) {
			$this->cache->redis->save($name, $token, 3600);
			$this->cache->redis->save($token, $name, 3600);
			return [
				'code' => 200,
				'msg' => "user.Login.success",
				'token' => $rtoken,
			];
		} else {
			$this->cache->redis->save($name, $rtoken, 3600);
			$this->cache->redis->save($rtoken, $name, 3600);
			return [
				'code' => 200,
				'msg' => "user.Login.success",
				'token' => $rtoken,
			];
		}
	}
	public function Register() {
		$this->load->driver('cache');
		$username = @$_REQUEST['username'];
		$password = @$_REQUEST['password'];
		$lower_name = strtolower($username);
		if (!preg_match($this->config->config['username_preg'], $lower_name)) {
			return [
				'code' => 403,
				'msg' => 'user.Name.notValid',
			];
		}
		if (strlen($password) > 20 || strlen($password) < 6) {
			return [
				'code' => 403,
				'msg' => 'user.Password.notValid',
			];
		}
		$encrypt_password = hash("sha512", $password);
		if ($this->UserExists($username)) {
			return [
				'code' => 403,
				'msg' => 'user.Exits',
			];
		}
		$user_data = [
			'name' => $lower_name,
			'realname' => $username,
			'password' => $encrypt_password,
			'lastip' => $_SERVER['REMOTE_ADDR'],
			'lastlogin' => time(),
		];
		$balance_data = [
			'username' => $lower_name,
			'balance' => "0.00",
			'status' => 0,
		];
		$this->db->insert('Balance', $balance_data);
		$this->db->insert('Member', $user_data);

		$token = md5(uniqid());

		$this->cache->redis->save($lower_name, $token, 3600);
		$this->cache->redis->save($token, $lower_name, 3600);

		return [
			'code' => 200,
			'msg' => "user.Register.success",
			'token' => $token,
		];
	}
	public function Logout($token) {
		$this->load->driver('cache');
		if (!$name = $this->cache->redis->get($token)) {
			return [
				'code' => 401,
				'msg' => 'user.Token.Expired',
			];
		} else {
			$this->cache->redis->delete($token);
			$this->cache->redis->delete($name);
			return [
				'code' => 200,
				'msg' => 'user.Logout.success',
			];
		}
	}
	private function UserExists($username = '') {
		if (@$this->db->select("id,name,realname")->get_where("Member", ['name' => $username])->result()[0]) {
			return true;
		} else {
			return false;
		}
	}
	private function GetUserData($username = '') {
		return $this->db->select("*")->get_where("Member", ['name' => $username])->result()[0];
	}
}