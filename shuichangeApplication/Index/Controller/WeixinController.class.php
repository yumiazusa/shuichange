<?php
namespace Index\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WeixinController extends HomeController {

	public function index() {
			$token = 'yumiazusa'; //微信后台填写的TOKEN
			$appid=$this->appid();
		    $secret=$this->secret();
			/* 加载微信SDK */
			$wechat = new Wechat($token);
			$access_token['access_token']=$Token=A('Api')->getAccessToken();
			$WechatAuth = new WechatAuth($appid, $secret, $access_token['access_token']);
			/* 获取请求信息 */
			$data = $wechat->request();

			$access_token['openid'] = $data['FromUserName'];

			if ($data['Event'] == 'subscribe') {


			} else if ($data['Event'] == 'unsubscribe') {
				
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_CUSTOMERMESSAGE') {
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_TAKEMEDICAL') {
				
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_POSTERCREATE') {
				
			} else if ($data['Event'] == 'SCAN') {
			
			}
			if ($data && is_array($data) && $data['Event'] != 'LOCATION') {

				

			}
	}

	private function appid(){
		$appid='wx37e5d97cd5c7ec90';
		return $appid;
	}

	private function secret(){
		$secret='c87aff3db0bef3794313a903e166290c';
		return $secret;
	}

}