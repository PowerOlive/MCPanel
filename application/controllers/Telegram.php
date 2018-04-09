<?php
class Telegram extends CI_Controller
{
    public function Webhook()
    {
        $chat_data = $this->parse_chat_data(json_decode(file_get_contents("php://input"), true));
        $command_arg = explode(" ", $chat_data['text'], 2);
        $this->load->library("CommonUtil", null, "utils");
        switch ($command_arg[0]) {
            case '/start':
                if ($chat_data['type'] !== 'private') {
                    return $this->APICall("sendMessage", [
                        'chat_id' => $chat_data['uid'],
                        'text' => "Chat Type Not Private",
                    ]);
                }
                if (!$username = $this->utils->CheckLogin($command_arg[1])) {
                    return $this->APICall("sendMessage", [
                        'chat_id' => $chat_data['uid'],
                        'text' => "User Token Expired",
                    ]);
                }
                $user_data = $this->utils->GetUserData($username);
                //var_dump($user_data->telegram_uid);
		if (!is_null($user_data->telegram_uid)) {
                    return $this->APICall("sendMessage", [
                        'chat_id' => $chat_data['uid'],
                        'text' => "Game Profile: " . $user_data->realname . PHP_EOL . "Status: Already Bind",
                    ]);
                }
                $data = [
                    'telegram_uid' => $chat_data['uid'],
                ];
                $this->db->where('id', $user_data->id);
                $this->db->update("Member", $data);
                return $this->APICall("sendMessage", [
                    'chat_id' => $chat_data['uid'],
                    'text' => "Game Profile: " . $user_data->realname . PHP_EOL . "Status: Bind Success",
                ]);
                break;
	case '/getMe':
                $username = @$this->utils->GetUserDataByTGID($chat_data['uid'])->realname;
                if (!$username) {
                    return $this->APICall("sendMessage", [
                        'chat_id' => $chat_data['chat_id'],
			'reply_to_message_id' => $chat_data['message_id'],
                        'text' => "Game Account Not Bind Yet",
                    ]);
                }
                if ($this->utils->isOnline($username)) {
                    $data = $this->utils->getUserInfo($username);
                    $message = "Player Name: " . $username . PHP_EOL;
                    $message .= "Level: " . $data['level'] . PHP_EOL;
                    $message .= "Health: " . $data['health'] . PHP_EOL;
                    $message .= "Online: " . ($data['online'] ? "Yes" : "No");
                    return $this->APICall("sendMessage", [
                        'chat_id' => $chat_data['chat_id'],
			'reply_to_message_id' => $chat_data['message_id'],
                        'text' => $message,
                    ]);
                }
                break;
        }
    }
    private function parse_chat_data($data)
    {
        $uid = @$data['message']['from']['id'];
        $username = @$data['message']['from']['username'];
        $message_id = @$data['message']['message_id'];
        $chat_id = @$data['message']['chat']['id'];
        $chat_type = @$data['message']['chat']['type'];
        $date = @$data['message']['date'];
        $chat_text = @$data['message']['text'];
        $location = @$data['message']['location'];
        return [
            'uid' => $uid,
            'message_id' => $message_id,
            'chat_id' => $chat_id,
            'username' => $username,
            'date' => $date,
            'type' => $chat_type,
            'location' => $location,
            'text' => $chat_text,
        ];
    }
    private function APICall($method = [], $body = [])
    {
        $url = 'https://api.telegram.org/' . $this->config->config['bot_token'] . '/' . $method;
         $option = [
             'proxy' => '120.51.211.108:8080',
         ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($ch, CURLOPT_POST, 1);
        // if (PHP_OS == "Darwin") {
        curl_setopt($ch, CURLOPT_PROXY, $option['proxy']);
        // }
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}
