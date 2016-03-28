<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Index\Controller;
use OT\DataDictionary;
use Com\Wechat;
use Com\WechatAuth;

class IndexController extends HomeController {

	//系统首页
    public function index(){
    	$Token=A('Api')->getAccessToken();
    	// $url="https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$Token["access_token"];
    	$url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$Token["access_token"];
        $data='{
    		"type":"image",
    		"offset":0,
    		"count":10
			}';
		$res=$this->httpsPost($url,$data);
		$res=json_decode($res);
		dump($res);
		die;
        $this->display();
    }


    public function uploadImage(){
    	$appid=C('WEI_APPID');
		$secret=C('WEI_SECRET');
		$Token=A('Api')->getAccessToken();
		$WechatAuth = new WechatAuth($appid, $secret, $Token["access_token"]);
		$userimg = './1.jpg';
	    $type = 'image';
		$media = $WechatAuth->mediaUpload($userimg,$type);
		dump($media);
		die;
		$res=json_decode($media);
		dump($res);
		die;
    }



    public function getStuff($type){
    	$Token=A('Api')->getAccessToken();
    	$url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$Token["access_token"];
        $data='{
    		"type":"'.$type.'",
    		"offset":0,
    		"count":10
			}';
		$res=$this->httpsPost($url,$data);
		$res=json_decode($res);
		dump($res);
		die;
		return $res;
    }

    public function totalStuff(){
    	$Token=A('Api')->getAccessToken();
    	$url="https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$Token["access_token"];
		$res=$this->httpsPost($url,$data);
		$res=json_decode($res);
		dump($res);
		die;
		return $res;
    }




    private function httpsPost($url, $data = null) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
			return 'Errno' . curl_error($curl);
		}
		curl_close($curl);
		return $result;
	}

	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}