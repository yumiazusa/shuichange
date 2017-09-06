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
			$WechatAuth = new WechatAuth(C('WEI_APPID'), C('WEI_SECRET'),$access_token['access_token']['access_token']);
			/* 获取请求信息 */
			$data = $wechat->request();
			$Wx_user = M('wx_user');

			$access_token['openid'] = $data['FromUserName'];

			if ($data['Event'] == 'subscribe') {
				// $response=json_encode($data);
				$wx_user_id = $Wx_user->where(array('openid' => $data['FromUserName']))->getField('id');
				if($wx_user_id) {
					$userinfo = A('Api')->getUserinfo($data['FromUserName']);
					$Wx_user->where(array('openid' => $data['FromUserName']))->save(array('subscribe' => 1, 'nickname' => $userinfo['nickname'], 'headimgurl' => $userinfo['headimgurl']));
				}else{
					$sceneArr = explode('_', $data['EventKey']);
					if ($sceneArr[0] == 'qrscene') {
						$save['scene'] = $sceneArr[2] ? $sceneArr[1] . '_' . $sceneArr[2] : $sceneArr[1]; //场景值  默认0
					}
					$userinfo = A('Api')->getUserinfo($data['FromUserName']);
					$response=json_encode($userinfo);
					$save['openid'] = $data['FromUserName'];
					$save['nickname'] = $userinfo['nickname'];
					$save['headimgurl'] = $userinfo['headimgurl'];
					$save['sex'] = $userinfo['sex'];
					$save['addtime'] = $userinfo['subscribe_time'];
					$save['status'] = 1;
					$save['user_id'] = 0;
					$save['subscribe'] = 1;
					$Wx_user->add($save);
				}
				$response=C('WEI_REPLAYWORD_SUBSCRIBE');

			} else if ($data['Event'] == 'unsubscribe') {
				$Wx_user->where(array('openid' => $data['FromUserName']))->setField('subscribe', 0);
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_TEST') {
				// $userinfo = A('Api')->getUserinfo($data['FromUserName']);
				// $MsgType = "text";
				// $WechatAuth->sendText($data['FromUserName'], $Content);

				// $openid = $data['FromUserName'];
				$title = '文君首頁';
				$discription = '文君官方';
			    $url = 'http://www.tattoowenjun.com/index.php/home/index/main.html';
				$picurl = 'http://www.tattoowenjun.com/Uploads/Picture/2017-04-19/58f73fe0eae57.jpg';
				$wechat->replyNewsOnce($title, $discription, $url, $picurl);

				// $WechatAuth->sendNewsOnce($openid, $title, $discription, $url, $picurl);
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_BOSS') {
				$response='文君·昆明客服：王老师 微信号，咨询请添加~';
				$WechatAuth->sendImage($data['FromUserName'],'2b5gmoi9AraTBBixGOu_aXFjNa_5a1FAWGy3wfDRDZw');

			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_INFORMATION') {
						$title = '彩云南：文君刺青·昆明 店面如是';
						$discription = '文君刺青·昆明，身纹精神，纹你满背满身';
			    		$url = 'http://mp.weixin.qq.com/s/HVAwlrkOiEQ3yx9O4e7j5Q';
						$picurl = 'http://www.tattoowenjun.com/Uploads/Weixin/data/a.jpg';
						$wechat->replyNewsOnce($title, $discription, $url, $picurl);
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_TATTOR') {
						$title = '彩云南：文君刺青·昆明 刺客如是';
						$discription = '文君刺青·昆明，身纹精神，七分线三分雾';
			    		$url = 'http://mp.weixin.qq.com/s/IbSE1_NNQq0k_8aTd8ILUA';
						$picurl = 'http://www.tattoowenjun.com/Uploads/Weixin/data/b.jpg';
						$wechat->replyNewsOnce($title, $discription, $url, $picurl);
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_ADDRESS') {
						$title = '彩云南：文君刺青·昆明 店面导航';
						$discription = '文君刺青·昆明，葡萄街区 天宇花园A栋 1104';
			    		$url = 'http://mp.weixin.qq.com/s/leVOsyPNuW5iLCOtK59AoQ';
						$picurl = 'http://www.tattoowenjun.com/Uploads/Weixin/data/c.jpg';
						$wechat->replyNewsOnce($title, $discription, $url, $picurl);
			} else if ($data['Event'] == 'SCAN') {

			}else if ($data && is_array($data) && $data['Event'] != 'LOCATION') {
				$Custom_reply= M('muyeindex');
				$keyword='%'.trim($data['Content']).'%';
				$map['name'] =array('like',$keyword);
				$map['discribe']=array('like',$keyword);
				$response_id = $Custom_reply->where($map)->find();
				if($response_id){
						$title = '文君刺青·昆明 图库';
						$discription = '文君图库：关于'.$response_id['name'].'的些许图片';
			    		$url = 'http://www.tattoowenjun.com/index.php/home/index/single/pid/'.$response_id['id'].'html';
						$picurl = get_cover($response_id['image']);
						$wechat->replyNewsOnce($title, $discription, $url, $picurl);
				}
				else if ($data['Content'] == '排期') {
					$manager=C('WEIXIN_MANAGER');
					if(in_array($data['FromUserName'],$manager)){
						$title = '客戶排期';
						$discription = '客戶排期表';
			    		$url = 'http://www.tattoowenjun.com/index.php/Home/Index/view.html';
						$picurl = 'http://www.tattoowenjun.com/Uploads/Picture/2017-08-09/598aec27ba97d.jpg';
						$wechat->replyNewsOnce($title, $discription, $url, $picurl);
					}


				}else if($data['Content'] == '测试'){
					$manager=C('WEIXIN_MANAGER');
					if(in_array($data['FromUserName'],$manager)){
						$response=json_encode($data);
					}
				}else if($data['MsgType'] == 'image'){
					// $filename=$this->lala();
					// $a=$WechatAuth->materialUpload($filename,'image');
					// $wechat->replyImage($data['MediaId']);
					// $response=$secret;
					// $content='lalala';
					// $a=$WechatAuth->sendText($data['FromUserName'], $content);
					
					// $response=json_encode($a);
				}
				// if(C('WEI_REPLAY_SWITCH')){
				// 	$response=C('WEI_REPLAYWORD');
				// }else{
				// 	exit;
				// }
			}else{
				$response='aaaa';
			}

			$test_str = C('WEI_REPLAYWORD_SUBSCRIBE');
			$index_response = $test_str;
			$text = isset($response) ? $response : $index_response;
			$wechat->response($text, 'text');

	}

	private function lala(){
		$data='./Uploads/Weixin/2code/wangyi.jpg';
		return $data;
	}


}