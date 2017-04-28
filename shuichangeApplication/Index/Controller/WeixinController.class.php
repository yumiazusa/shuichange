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
			$Wx_user = M('wx_user');

			$access_token['openid'] = $data['FromUserName'];

			if ($data['Event'] == 'subscribe') {
				// $response=json_encode($data);
				$wx_user_id = $Wx_user->where(array('openid' => $data['FromUserName']))->getField('id');
				if ($wx_user_id > 0) {
					$userinfo = A('Api')->getUserinfo($data['FromUserName']);
					$Wx_user->where(array('openid' => $data['FromUserName']))->save(array('subscribe' => 1, 'nickname' => $userinfo['nickname'], 'headimgurl' => $userinfo['headimgurl']));
				}else{
					// $sceneArr = explode('_', $data['EventKey']);
					// if ($sceneArr[0] == 'qrscene') {
					// 	$save['scene'] = $sceneArr[2] ? $sceneArr[1] . '_' . $sceneArr[2] : $sceneArr[1]; //场景值  默认0
					// }
					// $userinfo = A('Api')->getUserinfo($data['FromUserName']);
					// $response=json_encode($userinfo);
					$response=json_encode($data);
					$save['openid'] = $data['FromUserName'];
					$save['nickname'] = $userinfo['nickname'];
					$save['headimgurl'] = $userinfo['headimgurl'];
					$save['sex'] = $userinfo['sex'];
					$save['addtime'] = $userinfo['subscribe_time'];
					$save['status'] = 1;
					$save['user_id'] = 0;
					$save['subscribe'] = 1;
					$Wx_user_fans->add($save2);
				}
				// $response=C('WEI_REPLAYWORD_SUBSCRIBE');

			} else if ($data['Event'] == 'unsubscribe') {
				$Wx_user->where(array('openid' => $data['FromUserName']))->setField('subscribe', 0);
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_TEST') {
				$userinfo = A('Api')->getUserinfo($data['FromUserName']);
				// $response=json_encode($data);
				// $response=json_encode($access_token);
				$response=json_encode($userinfo);
				// $response=$userinfo;
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_TAKEMEDICAL') {
				
			} else if ($data['Event'] == 'CLICK' && $data['EventKey'] == 'MENU_KEY_POSTERCREATE') {
				
			} else if ($data['Event'] == 'SCAN') {
			
			}else if ($data && is_array($data) && $data['Event'] != 'LOCATION') {
				if(C('WEI_REPLAY_SWITCH')){
					$response=C('WEI_REPLAYWORD');
				}else{
					exit;
				}
			}

			$test_str = C('WEI_REPLAYWORD_SUBSCRIBE');
			$index_response = $test_str;
			$text = isset($response) ? $response : $index_response;
			$wechat->response($text, 'text');

	}


}