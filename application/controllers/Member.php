<?php
class Member extends CI_Controller {
	/**
	 * @api GET /Member/getSelf/:token 获取当前用户信息
	 * @apiGroup Member
	 *
	 * @apiSuccess 200 OK
	 * @apiExample json
	 * {"id":1,"name":"kagurazakasatori","realname":"KagurazakaSatori","balance":"2.33","code":200}
	 * @apiError 401 Token Exipred
	 * @apiExample json
	 * {"code":401,"msg":"user.Token.Expired"}
	 */
	public function getSelf($token = '') {
		$this->load->driver('cache');

		if (!$name = $this->cache->redis->get($token)) {
			return [
				'code' => 401,
				'msg' => 'user.Token.Expired',
			];
		}

		$user = $this->db->select("id,name,realname")->get_where("Member", ['name' => $name])->result()[0];
		$balance = $this->db->select("username,balance")->get_where("Balance", ["username" => $name])->result()[0];
		$user->id = intval($user->id);
		$user->balance = $balance->balance;
		$user->code = 200;
		$user->online = $this->isOnline($user->realname);
		if ($user->online) {
			$user_game_data = $this->getUserInfo($user->realname);
			if ($user_game_data) {
				$user->level = $user_game_data['level'];
				$user->health = $user_game_data['health'];
				$user->location = $user_game_data['location'];
			}
		} else {
			$user->level = null;
			$user->health = null;
			$user->location = [];
		}

		return $user;
	}
	/**
	 * @api GET /Member/Login/:username/:password 登录
	 * @apiGroup Member
	 *
	 * @apiSuccess 200 OK
	 * @apiExample json
	 * {"code":200,"msg":"user.Login.success","token":"30dfa17547dfa00364e4f94b1756460c"}
	 * @apiError 403 Wrong Password
	 * @apiExample json
	 * {"code":403,"msg":"user.Password.notValid"}
	 */
	public function Login($username = '', $password = '') {
		$this->load->driver('cache');
		$name = strtolower($username);
		if (!$this->UserExists($name)) {
			return [
				'code' => 404,
				'msg' => 'user.NotExists',
			];

		}
		if ($this->GetUserData($name)->password !== hash("sha512", $password)) {
			return [
				'code' => 403,
				'msg' => 'user.Password.notValid',
			];
		}
		$token = md5(uniqid());
		if (!$rtoken = $this->cache->redis->get($name)) {
			$this->cache->redis->save($name, $token, 3600);
			$this->cache->redis->save($token, $name, 3600);
			return [
				'code' => 200,
				'msg' => "user.Login.success",
				'token' => $token,
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
	/**
	 * @api POST /Member/Register 注册
	 * @apiGroup Member
	 * @apiParam username string 用户名
	 * @apiParam password string 密码
	 *
	 * @apiSuccess 200 OK
	 * @apiExample json
	 * {"code":200,"msg":"user.Register.success","token":"30dfa17547dfa00364e4f94b1756460c"}
	 * @apiError 403 User Not Exists
	 * @apiExample json
	 * {"code":403,"msg":"user.Name.notValid"}
	 */
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
	/**
	 * @api POST /Member/ResetPassword/:token 重置密码
	 * @apiGroup Member
	 * @apiParam old_password string 旧密码
	 * @apiParam new_password string 新密码
	 *
	 * @apiSuccess 200 OK
	 * @apiExample json
	 * {"code":200,"msg":"user.Password.ResetSuccess"}
	 * @apiError 401 Token Exipred
	 * @apiExample json
	 * {"code":403,"msg":"user.Token.Expired"}
	 */
	public function ResetPassword($token = '') {
		$this->load->driver('cache');
		$old_password = @$_REQUEST['old_password'];
		$new_password = @$_REQUEST['new_password'];

		if (!$name = $this->cache->redis->get($token)) {
			return [
				'code' => 401,
				'msg' => 'user.Token.Expired',
			];
		}
		$user_data = $this->GetUserData($name);
		if ($user_data->password !== hash("sha512", $old_password)) {
			return [
				'code' => 403,
				'msg' => 'user.Password.Wrong',
			];
		}
		if (strlen($new_password) > 20 || strlen($new_password) < 6) {
			return [
				'code' => 403,
				'msg' => 'user.Password.notValid',
			];
		}
		$data = [
			'password' => hash("sha512", $new_password),
		];
		$this->db->where('id', $user_data->id);
		$this->db->update("Member", $data);

		$this->cache->redis->delete($token);
		$this->cache->redis->delete($name);

		$this->kickPlayer($name, "由于密码变更,您已被系统强制下线");

		return [
			'code' => 200,
			'msg' => 'user.Password.ResetSuccess',
		];
	}
	/**
	 * @api GET /Member/Logout/:token 退出登录
	 * @apiGroup Member
	 *
	 * @apiSuccess 200 OK
	 * @apiExample json
	 * {"code":200,"msg":"user.Logout.success"}
	 * @apiError 401 Token Exipred
	 * @apiExample json
	 * {"code":403,"msg":"user.Token.Expired"}
	 */
	public function Logout($token = '') {
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

	private function getSkin() {
		$get_uuid = "https://api.mojang.com/users/profiles/minecraft/%s";

	}
	private function getUserInfo($username = '') {
		$this->load->library("BakaRPC", null, "rpc");
		$this->rpc->getInstance($this->config->config['mcpanel']['url'], $this->config->config['mcpanel']['key']);
		$result = $this->rpc->APICall([
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
	private function isOnline($username = '') {
		$this->load->library("BakaRPC", null, "rpc");
		$this->rpc->getInstance($this->config->config['mcpanel']['url'], $this->config->config['mcpanel']['key']);
		$result = $this->rpc->APICall([
			"action" => "Players",
			"method" => "getOnline",
		]);
		if (is_array($result) && in_array($username, $result['online'])) {
			return true;
		} else {
			return false;
		}
	}
	private function kickPlayer($username = '', $content = '') {
		$this->load->library("BakaRPC", null, "rpc");
		$this->rpc->getInstance($this->config->config['mcpanel']['url'], $this->config->config['mcpanel']['key']);
		$result = $this->rpc->APICall([
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
}