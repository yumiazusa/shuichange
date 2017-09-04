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
			$WechatAuth = new WechatAuth($appid,$secret);
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
				

			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_POSTERCREATE') {

			} else if ($data['Event'] == 'SCAN') {

			}else if ($data && is_array($data) && $data['Event'] != 'LOCATION') {
				if ($data['Content'] == '排期') {
					$manager=C('WEIXIN_MANAGER');
					if(in_array($data['FromUserName'],$manager)){
						$title = '客戶排期';
						$discription = '客戶排期表';
			    		$url = 'http://www.tattoowenjun.com/index.php/Home/Index/view.html';
						$picurl = 'http://www.tattoowenjun.com/Uploads/Picture/2017-08-09/598aec27ba97d.jpg';
						$wechat->replyNewsOnce($title, $discription, $url, $picurl);
					}


				}else if($data['Content'] == '测试'){
					$response=json_encode($data);
				}else if($data['MsgType'] == 'image'){
					
					// $wechat->replyImage($data['MediaId']);
					$response=$secret;
					// $content='lalala';
					// $WechatAuth->sendText($data['FromUserName'], $content);
					// $response=json_encode($data);
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


}