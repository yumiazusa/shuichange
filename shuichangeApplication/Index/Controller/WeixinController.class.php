<?php
namespace Index\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WeixinController extends HomeController {

	public function index() {
		if (IS_POST) {
			$token = 'yumiazusa'; //微信后台填写的TOKEN
			/* 加载微信SDK */
			$wechat = new Wechat($token);
			$access_token['access_token'] = A('Ask/Api')->getAccessToken();
			$WechatAuth = new WechatAuth(C('APPID'), C('APPSECRET'), $access_token['access_token']);
			/* 获取请求信息 */
			$data = $wechat->request();
			$Wx_hdkc_user = M('wx_hdkc_user');
			$access_token['openid'] = $data['FromUserName'];
			$Wx_hdkc_user = M('wx_hdkc_user');
			$Wx_user_fans = M('user_fans');
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
	}

}