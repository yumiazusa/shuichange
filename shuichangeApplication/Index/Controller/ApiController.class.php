<?php
namespace Index\Controller;

class ApiController extends HomeController {


	private function appid(){
		$appid='wx37e5d97cd5c7ec90';
		return $appid;
	}

	private function secret(){
		$secret='c87aff3db0bef3794313a903e166290c';
		return $secret;
	}

	public function getAccessToken(){
		$Token=S('Token');
		if(!$Token){
		$appid=$this->appid();
		$secret=$this->secret();
		$info = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret);
		$Token = json_decode($info,true);
		S('Token',$Token,3600);
	    }
	    return $Token;
	}

}