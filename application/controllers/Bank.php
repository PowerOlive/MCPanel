<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	/**
	 * @api POST /Bakn/Transfer 用户转账
	 * @apiGroup Member
	 * @apiParam to string 转账接收方
	 * @apiParam balance string 转账金额
	 * @apiParam tokek string 用户Token
	 *
	 * @apiSuccess 200 OK
	 * @apiExample json
	 * {"code":200,"message":"user.transfer.success","amount":22.33}
	 * @apiError 401 Token Exipred
	 * @apiExample json
	 * {"code":401,"msg":"user.Token.Expired"}
	 */
	public function Transfer() {
		$to = @$_POST['to'];
		$balance = @$_POST['balance'];
		$token = @$_POST['token'];

		$this->load->driver('cache');
		$this->load->library("CommonUtil", null, "utils");
		if (!$name = $this->utils->CheckLogin($token)) {
			return [
				'code' => 401,
				'msg' => 'user.login.expired',
			];
		}
		$to_name = strtolower($to);
		if (!$this->utils->UserExists($to_name)) {
			return [
				'code' => 404,
				'msg' => 'user.not_exists',
			];
		}
		$self_balance = $this->db->select("username,balance")->get_where("Balance", ["username" => $name])->result()[0]->balance;
		$to_balance = $this->db->select("username,balance")->get_where("Balance", ["username" => $to_name])->result()[0]->balance;

		if ($self_balance - $balance < 0 || $balance < 0) {
			return [
				'code' => 403,
				'msg' => 'user.transfer.balance_not_enought',
			];
		}
		$amount = $self_balance - $balance;
		$self_transfer = [
			'balance' => $amount,
		];
		$to_transfer = [
			'balance' => $to_balance + $balance,
		];
		$this->db->where('username', $name);
		$this->db->update("Balance", $self_transfer);
		$this->db->where('username', $to_name);
		$this->db->update("Balance", $to_transfer);

		// $this->utils->sendMessage($name, '§a[银行] §f 您向 %s 转账了 %s 个 NekoCoin');
		// $this->utils->sendMessage($to_name, '§a[银行] §f %s 向您转账了 %s 个 NekoCoin');
		return [
			'code' => 200,
			'message' => 'user.transfer.success',
			'amount' => $amount,
		];
	}
}