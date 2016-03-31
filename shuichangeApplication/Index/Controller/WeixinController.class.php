<?php
namespace Index\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WeixinController extends HomeController {

	public function index() {
			$token = 'yumiazusa'; //微信后台填写的TOKEN
			$appid=C('WEI_APPID');
		    $secret=C('WEI_SECRET');
			/* 加载微信SDK */
			$wechat = new Wechat($token);
			$access_token['access_token']=$Token=A('Api')->getAccessToken();
			$WechatAuth = new WechatAuth($appid, $secret, $access_token['access_token']);
			/* 获取请求信息 */
			$data = $wechat->request();

			$access_token['openid'] = $data['FromUserName'];

			if ($data['Event'] == 'subscribe') {
				$response="亲！服务号正在升级开发中，敬请期待~";
				
			} else if ($data['Event'] == 'unsubscribe') {
				
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_CUSTOMERMESSAGE') {
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_TAKEMEDICAL') {
				
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_POSTERCREATE') {
				
			} else if ($data['Event'] == 'SCAN') {
			
			}
			if ($data && is_array($data) && $data['Event'] != 'LOCATION') {
				$response="欢迎关注水禅阁！";
			}
			$test_str = "欢迎关注水禅阁！";
			$index_response = $test_str;
			$text = isset($response) ? $response : $index_response;
			$wechat->response($text, 'text');

	}


}