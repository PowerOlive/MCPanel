<?php
class Test extends CI_Controller
{
    public function Get($username)
    {
        // $this->load->library('Qiniu');

        // $auth = new Qiniu\Auth($this->config->config['qiniu']['ak'], $this->config->config['qiniu']['sk']);
        // $bucketMgr = new Qiniu\Storage\BucketManager($auth);
        // $file_name = "skins/" . $username . ".png";
        // $token = $auth->uploadToken("satori", $file_name, 3600, null, true);
        // $uploadMgr = new Qiniu\Storage\UploadManager();

        // list($ret, $err) = $uploadMgr->putFile($token, $file_name, $_FILES['skins']['tmp_name']);
        // if (!$err) {
        //     return [
        //         'code' => 200,
        //         'msg' => 'user.skin.change_success',
        //     ];
        // } else {
        //     return [
        //         'code' => 400,
        //         'msg' => $err['error'],
        //     ];
        // }
        //$this->load->library("CommonUtil", null, "utils");
        //return $this->utils->sendMessage("KagurazakaSatori", '§a[服务器] §f当前时间: ' . date('Y-m-d H:i:s'));
    }
    /**
     * @api GET /Member/getSkin/:token 获取用户皮肤
     * @apiGroup Member
     *
     * @apiSuccess 200 OK
     * @apiExample json
     * {"code":200,"no_skin":false,"cache_hit":true,"cache_time":1522469253,"textures":"iVBO....."}
     * @apiError 204 Skin Not Found
     * @apiExample json
     * {"code":204,"msg":"user.Skin.notFound","no_skin": true,"cache_hit": false,"cache_time": 1522469253}
     */
    private function getSkin($token = '', $refresh = false)
    {
        return [
            'code' => 404,
            'msg' => 'server.controller.not_found',
        ];
        //暂时废弃接口
        $this->load->library("CommonUtil", null, "utils");
        $this->load->driver('cache');
        if (!$username = $this->cache->redis->get($token)) {
            return [
                'code' => 401,
                'msg' => 'user.token.expired',
            ];
        }
        $get_uuid = "https://api.mojang.com/users/profiles/minecraft/%s";
        $get_session = "https://sessionserver.mojang.com/session/minecraft/profile/%s";
        $textures = "";
        $result = [];
        $result['code'] = 200;
        if ($this->cache->redis->get(sprintf("%s|skin", $username)) && $refresh == false) {
            $textures_data = $this->db->select("textures,timestamp")->order_by("timestamp", "DESC")->get_where("Skins", ['username' => $username])->result()[0];
            $textures = $textures_data->textures;
            $this->cache->redis->save(sprintf("%s|skin", $username), "1", 3600);
            $result['no_skin'] = false;
            $result['cache_hit'] = true;
            $result['cache_time'] = intval($textures_data->timestamp);
        } else {
            $results = $this->utils->fetchAPI(sprintf($get_uuid, $username));
            switch ($results->code) {
                case 200:
                    $uuid = json_decode($results->body, true)['id'];
                    $session_data = json_decode($this->utils->fetchAPI(sprintf($get_session, $uuid))->body, true);
                    $textures_url = json_decode(base64_decode($session_data['properties'][0]['value']), true)['textures']['SKIN']['url'];
                    $textures = base64_encode($this->utils->fetchAPI($textures_url)->body);
                    $skin_data = [
                        'textures' => $textures,
                        'timestamp' => time(),
                        'username' => $username,
                    ];
                    $this->db->insert('Skins', $skin_data);
                    $this->cache->redis->save(sprintf("%s|skin", $username), "1", 3600);
                    $result['no_skin'] = false;
                    $result['cache_hit'] = false;
                    $result['cache_time'] = $skin_data['timestamp'];
                    break;
                case 204:
                    $result['code'] = 204;
                    $result['no_skin'] = true;
                    $result['cache_hit'] = false;
                    $result['msg'] = 'user.skin.not_found';
                    $result['cache_time'] = time();
                    break;
            }
        }
        $result['textures'] = $textures;
        return $result;
    }
}
