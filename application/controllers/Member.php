<?php
class Member extends CI_Controller
{
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
    public function getSelf($token = '')
    {
        $this->load->driver('cache');
        $this->load->library("CommonUtil", null, "utils");
        if (!$name = $this->utils->CheckLogin($token)) {
            return [
                'code' => 401,
                'msg' => 'user.login.expired',
            ];
        }

        $user = $this->db->select("id,name,lastip,realname,skin,web_name")->get_where("Member", ['name' => $name])->result()[0];
        $balance = $this->db->select("username,balance")->get_where("Balance", ["username" => $name])->result()[0];
        $user->id = intval($user->id);
        $user->balance = $balance->balance;
        $user->code = 200;
        if ($user->skin == "0") {
            $user->skin_url = sprintf("https://static.iadata.cn/skins/Steve.png");
        } else {
            $user->skin_url = sprintf("https://static.iadata.cn/skins/%s.png", $user->realname);
        }
        unset($user->skin);
        $user->online = $this->utils->isOnline($user->realname);
        if ($user->online) {
            $user_game_data = $this->utils->getUserInfo($user->realname);
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
     * @api GET /Member/Login 登录
     * @apiGroup Member
     * @apiParam username string 用户名
     * @apiParam password string 密码
     *
     * @apiSuccess 200 OK
     * @apiExample json
     * {"code":200,"msg":"user.Login.success","token":"30dfa17547dfa00364e4f94b1756460c"}
     * @apiError 403 Wrong Password
     * @apiExample json
     * {"code":403,"msg":"user.Password.notValid"}
     */
    public function Login()
    {
        $username = @$_REQUEST['username'];
        $password = @$_REQUEST['password'];
        $this->load->driver('cache');
        $this->load->library("CommonUtil", null, "utils");
        $name = strtolower($username);
        if (!$this->utils->UserExists($name)) {
            return [
                'code' => 404,
                'msg' => 'user.not_exists',
            ];

        }
        if ($this->utils->GetUserData($name)->password !== hash("sha512", $password)) {
            return [
                'code' => 403,
                'msg' => 'user.password.not_valid',
            ];
        }
        $token = $this->utils->create_uuid();
        if (!$rtoken = $this->cache->redis->get($name)) {
            $this->cache->redis->save($name, $token, 3600);
            $this->cache->redis->save($token, $name, 3600);
            return [
                'code' => 200,
                'msg' => "user.login.success",
                'token' => $token,
            ];
        } else {
            $this->cache->redis->save($name, $rtoken, 3600);
            $this->cache->redis->save($rtoken, $name, 3600);
            return [
                'code' => 200,
                'msg' => "user.login.success",
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
    public function Register()
    {
        $this->load->driver('cache');
        $this->load->library("CommonUtil", null, "utils");
        $username = @$_REQUEST['username'];
        $password = @$_REQUEST['password'];
        $lower_name = strtolower($username);
        if (!preg_match($this->config->config['username_preg'], $lower_name)) {
            return [
                'code' => 403,
                'msg' => 'user.name.not_valid',
            ];
        }
        if (strlen($password) > 20 || strlen($password) < 6) {
            return [
                'code' => 403,
                'msg' => 'user.name.not_valid',
            ];
        }
        $encrypt_password = hash("sha512", $password);
        if ($this->utils->UserExists($username)) {
            return [
                'code' => 403,
                'msg' => 'user.exists',
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

        $token = $this->utils->create_uuid();

        $this->cache->redis->save($lower_name, $token, 3600);
        $this->cache->redis->save($token, $lower_name, 3600);

        return [
            'code' => 200,
            'msg' => "user.register.success",
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
     * {"code":401,"msg":"user.Token.Expired"}
     */
    public function ResetPassword($token = '')
    {
        $this->load->library("CommonUtil", null, "utils");
        $this->load->driver('cache');
        $old_password = @$_REQUEST['old_password'];
        $new_password = @$_REQUEST['new_password'];

        if (!$name = $this->utils->CheckLogin($token)) {
            return [
                'code' => 401,
                'msg' => 'user.login.expired',
            ];
        }
        $user_data = $this->utils->GetUserData($name);
        if ($user_data->password !== hash("sha512", $old_password)) {
            return [
                'code' => 403,
                'msg' => 'user.password.wrong',
            ];
        }
        if (strlen($new_password) > 20 || strlen($new_password) < 6) {
            return [
                'code' => 403,
                'msg' => 'user.password.not_valid',
            ];
        }
        $data = [
            'password' => hash("sha512", $new_password),
        ];
        $this->db->where('id', $user_data->id);
        $this->db->update("Member", $data);

        $this->cache->redis->delete($token);
        $this->cache->redis->delete($name);

        $this->utils->kickPlayer($name, "由于密码变更,您已被系统强制下线");

        return [
            'code' => 200,
            'msg' => 'user.password.reset_success',
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
    public function Logout($token = '')
    {
        $this->load->driver('cache');
        $this->load->library("CommonUtil", null, "utils");
        if (!$name = $this->utils->CheckLogin($token)) {
            return [
                'code' => 401,
                'msg' => 'user.login.expired',
            ];
        } else {
            $this->cache->redis->delete($token);
            $this->cache->redis->delete($name);
            return [
                'code' => 200,
                'msg' => 'user.logout.success',
            ];
        }
    }
    /**
     * @api POST /Member/setName/:token 更新用户名字
     * @apiGroup Member
     *
     * @apiSuccess 200 OK
     * @apiExample json
     * {"code":200,"msg":"user.name.set_success"}
     * @apiError 403 User Name Not Valid
     * @apiExample json
     * {"code":403,"msg":"user.name.not_valid"}
     */
    public function setName($token)
    {
        $this->load->library("CommonUtil", null, "utils");
        if (!$username = $this->utils->CheckLogin($token)) {
            return [
                'code' => 401,
                'msg' => 'user.login.expired',
            ];
        }
        $set_name = @$_POST['name'];
        if (iconv_strlen($set_name, "UTF-8") > 10) {
            return [
                'code' => 403,
                'msg' => 'user.name.not_valid',
            ];
        }
        if ($this->utils->UserNameExists($set_name)) {
            return [
                'code' => 403,
                'msg' => 'user.name.exists',
            ];
        }
        $data = [
            'web_name' => $set_name,
        ];
        $this->db->where('name', $username);
        $this->db->update("Member", $data);
        return [
            'code' => 200,
            'msg' => 'user.name.set_success',
        ];
    }
    /**
     * @api GET /Member/uploadSkin/:token 更新用户皮肤
     * @apiGroup Member
     *
     * @apiSuccess 200 OK
     * @apiExample json
     * {"code":200,"msg":"user.skin.change_success"}
     * @apiError 413 Request Entity Too Large
     * @apiExample json
     * {"code":403,"msg":"user.upload.file_too_big"}
     */
    public function uploadSkin($token)
    {
        $this->load->library('Qiniu');
        $this->load->driver('cache');
        $this->load->library("CommonUtil", null, "utils");
        if (!$username = $this->utils->CheckLogin($token)) {
            return [
                'code' => 401,
                'msg' => 'user.login.expired',
            ];
        }
        if (!@$_FILES['skins']) {
            return [
                'code' => 404,
                'msg' => 'user.upload.empty_skin',
            ];
        }
        if (mime_content_type($_FILES['skins']['tmp_name']) !== 'image/png') {
            return [
                'code' => 403,
                'msg' => 'user.upload.not_a_png_file',
            ];
        }
        if ($_FILES['skins']['size'] > 256000) {
            return [
                'code' => 413,
                'msg' => 'user.upload.file_too_big',
            ];
        }
        $user_data = $this->utils->GetUserData($username);
        $username = $user_data->realname;

        $auth = new Qiniu\Auth($this->config->config['qiniu']['ak'], $this->config->config['qiniu']['sk']);
        $bucketMgr = new Qiniu\Storage\BucketManager($auth);
        $file_name = "skins/" . $username . ".png";
        $token = $auth->uploadToken($this->config->config['qiniu']['bucket'], $file_name, 3600, null, true);
        $uploadMgr = new Qiniu\Storage\UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token, $file_name, @$_FILES['skins']['tmp_name']);
        if (!$err) {
            if ($user_data->skin == "0") {
                $data = [
                    'skin' => "1",
                ];
                $this->db->where('id', $user_data->id);
                $this->db->update("Member", $data);
            }
            return [
                'code' => 200,
                'msg' => 'user.skin.change_success',
            ];
        } else {
            return [
                'code' => 400,
                'msg' => $err['error'],
            ];
        }
    }
}
