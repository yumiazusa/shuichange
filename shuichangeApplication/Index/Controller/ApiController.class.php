<?php
namespace Index\Controller;

class ApiController extends HomeController {

	public function getAccessToken(){
		// S('Token',null);
		$Token=S('Token');
		dump($Token);
		die;
		if(!$Token){
		S('Token',null);
		$appid=C('WEI_APPID');
		$secret=C('WEI_SECRET');
		$info = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
		$Token = json_decode($info,true);
		S('Token',$Token,600);
	    }
	    return $Token;
	}

}