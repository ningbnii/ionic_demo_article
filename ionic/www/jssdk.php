<?php

class JSSDK
{
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }


    /**
     * 网页授权获取用户openid
     */
    public function author()
    {
        $redirect_url = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        if (isset($_GET['code'])) {
            $state = $_GET['state'];
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId . '&secret=' . $this->appSecret . '&code=' . $_GET['code'] . '&grant_type=authorization_code';

            $json = json_decode($this->https_request($url));

            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $json->access_token . '&openid=' . $json->openid . '&lang=zh_CN';
            $info = json_decode($this->https_request($url));

            if (isset($info->errmsg)) {
                header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appId . '&redirect_uri=' . $redirect_url . '&response_type=code&scope=snsapi_userinfo&state=' . $state . '#wechat_redirect');
            } else {

                $params = array(
                    'openid' => $json->openid,
                    'name' => $info->nickname,
                    'thumb' => $info->headimgurl,
                    'sex'=>$info->sex,
                );
                $result = $this->https_request('http://wxbuluoapi.tunnel.hteen.cn/wxuser/save', $params);
                $result = json_decode($result, true);
                if($result['status'] == 200){
                    setcookie('openid', $json->openid, time()+24*3600);
                }
                header('location:' . $state);
            }
        } else {
            $state = $_SERVER['REQUEST_URI'];
            header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appId . '&redirect_uri=' . $redirect_url . '&response_type=code&scope=snsapi_userinfo&state=' . $state . '#wechat_redirect');
        }
    }

    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $host = array("Host:wxbuluoapi.tunnel.hteen.cn");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $host);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}

