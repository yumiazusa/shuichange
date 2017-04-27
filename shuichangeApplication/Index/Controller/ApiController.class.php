<?php
namespace Index\Controller;

class ApiController extends HomeController {

	public function getAccessToken(){
		// S('Token',null);
		$Token = array();
		if (S('Token')) {
			$Token = S('Token');
		} else {
			S('Token',null);
			$appid=C('WEI_APPID');
			$secret=C('WEI_SECRET');
			$info = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
			$Token = json_decode($info,true);

			if ($Token['access_token']) {

				S('Token', $Token, 3600);
			}
		}
		return $Token;
	}

	/**
	 * 获取微信用户信息
	 */
	public function getUserinfo($openid){
		$access_token = $this->getAccessToken();
		$info = file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');		
		return json_decode($info,true);
	}

}