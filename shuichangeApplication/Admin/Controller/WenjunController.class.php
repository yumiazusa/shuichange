<?php

namespace Admin\Controller;
use Com\Wechat;
use Com\WechatAuth;

class WenjunController extends AdminController {


		public function index(){
			$db=M('muyeindex');
			$data=$db->order('orderlist ASC')->select();
			$this->assign('data',$data);
			$this->display();
		}

		public function addIndex(){
			$this->display('addIndex');
		}

		public function doAddindex(){
			$data['name']=I('post.name');
			$data['image']=I('post.image');
			$data['orderlist']=I('post.orderlist');
			$data['describe']=I('describe');
			$catdb=M('muyeindex');

			$res= $catdb->add($data);

			if($res !== false){
	          $this->success('提交成功',U('index'),3);
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function editIndex(){
			$id=I('get.id');
			$data=M('muyeindex')->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$this->display('editIndex');
		}

		public function doEditindex(){
			$data=I('post.');
			$db=M('muyeindex');
			$res=$db->where(array('id'=>$data['id']))->save($data);
	        if($res !== false){
	          $this->success('提交成功',U('index'),3);
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function delIndex(){
			$id=I('get.id');
			$res=M('muyeindex')->where(array('id'=>$id))->delete();
			if($res !== false){
	          $this->success('提交成功');
	        }else{
	          $this->success('提交失败');
	        }
		}


		public function single(){
			$data=array();
			$data['pid']=$pid=I('get.pid');
			$db=M('muyesingle');
			$data=$db->where(array('pid'=>$pid))->order('orderlist ASC')->select();
			$this->assign(
				array('data'=>$data,
					  'pid'=>$pid)
					);
			$this->display();
		}


		public function addSingle(){
			$pid=I('get.pid');
			$this->assign('pid',$pid);
			$this->display('addSingle');
		}


		public function doAddsingle(){
			$data['pid']=I('post.pid');
			$data['name']=I('post.name');
			$data['image']=I('post.image');
			$data['orderlist']=I('post.orderlist');
			$data['describe']=I('describe');

			$catdb=M('muyesingle');

			$res= $catdb->add($data);

			if($res !== false){
	          $this->success('提交成功',U('single',array('pid'=>$data['pid'])),3);
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function editSingle(){
			$id=I('get.id');
			$data=M('muyesingle')->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$this->display('editSingle');
		}

		public function doEditsingle(){
			$data=I('post.');
			$db=M('muyesingle');
			$res=$db->where(array('id'=>$data['id']))->save($data);
	        if($res !== false){
	          $this->success('提交成功',U('single',array('pid'=>$data['pid'])),3);
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function delSingle(){
			$id=I('get.id');
			$res=M('muyesingle')->where(array('id'=>$id))->delete();
			if($res !== false){
	          $this->success('提交成功');
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function statistic(){
		// 查询总记录数
		$db=M('customer');
		$map =array();
		if (isset($_GET)) {
			$get=$_GET;
			foreach($get as$k => $v){
				if($v== 1 && $k =='status'){
					$map['finish']=1;
				}elseif($v == 0 && $k =='status'){
					$map['finish']=0;
				}

				if($k=='name'){
					$v=str_replace('，',',',$v);
                    $v=trim($v,',');
                    $v=explode(',',$v);
                    $val1=array();
                   	foreach($v as $key=>$val){
                        $val1[$key]='%'.trim($val).'%';
                        }
                    $map[$k] =array('like',$val1);
                    // $map['phone']=array('like',$val1,'OR');
					$parameter .= 'name='.urlencode($map['name']);
				}
				if($k=='phone'){
					$v=str_replace('，',',',$v);
                    $v=trim($v,',');
                    $v=explode(',',$v);
                    $val2=array();
                   	foreach($v as $key=>$val){
                        $val2[$key]='%'.trim($val).'%';
                        }
                    $map[$k] =array('like',$val2);
                    $parameter .= 'name='.urlencode($map['phone']);
				}

					if($k=='timestart' && $k==!''){
						$map1[$k]=strtotime($v);
					}
					if($k=='timeend' && $k==!''){
						$map1[$k]=strtotime($v);
					}
					if($k=='timestart1' && $k==!''){
						$map3[$k]=strtotime($v);
					}
					if($k=='timeend1' && $k==!''){
						$map3[$k]=strtotime($v);
					}

			}
		}
		if(!empty($map1)){
			if( count($map1)==2){
				$map2=array();
				foreach($map1 as $k1=>$v1){
					Array_push($map2,$v1);
				}
				$map['endingtime']=array('between',$map2);
			}else{
				foreach($map1 as $k1=>$v1){
					if($k1 =='timestart'){
						$map['endingtime']=array('gt',$v1);
					}elseif($k1 =='timeend'){
						$map['endingtime']=array('lt',$v1);
					}
				}
			}
		}
		if(!empty($map3)){
			if( count($map3)==2){
				$map4=array();
				foreach($map3 as $k1=>$v1){
					Array_push($map4,$v1);
				}
				$map['reservetime']=array('between',$map4);
			}else{
				foreach($map3 as $k1=>$v1){
					if($k1 =='timestart1'){
						$map['reservetime']=array('gt',$v1);
					}elseif($k1 =='timeend1'){
						$map['reservetime']=array('lt',$v1);
					}
				}
			}
		}
		// dump($map);
		// die;
        $total=$db->count();
        $getPageCounts = $db->where($map)->count();
        // 每页显示 $pageSize 条数据
        $pageSize = 15;
        // 实例化分页类
        
        $page = new \Think\Page($getPageCounts, $pageSize);

        foreach($map as $key=>$val) {
   		$Page->parameter[$key]   =   urlencode($val);
		}
        $data = $db->where($map)
                    ->order('id ASC')->limit($page->firstRow, $page->listRows)
                    ->select();
                            // echo $db->getLastSql();
        $pageShow = $page->show();
        $this->assign('page', $pageShow);
		$this->assign(
				array('data'=>$data,
					'count'=>$getPageCounts,
					'total'=>$total,
					  )
					);
			$this->display();
		}

		public function addStatistic(){
			$this->display('addStatistic');
		}


		public function doAddstatistic(){
			// $data['name']=I('post.name');
			// $data['image']=I('post.image');
			// $data['comeintime']=I('post.comeintime');
			// $data['reservetime']=I('post.reservetime');
			// $time=strtotime($data['reservetime']);
			// $data['orderlist']=I('post.orderlist');
			// $data['describe']=I('describe');
			$data=I('post.');

			
			if($data['reservetime']){
				$data['reservetime']=$data['reservetime']. ':00';
				$data['reservetime']=strtotime($data['reservetime']);
			}
			if($data['endingtime']){
				$data['endingtime']=$data['endingtime']. ':00';
				$data['endingtime']=strtotime($data['endingtime']);
			}

			$catdb=M('customer');

			$res= $catdb->add($data);

			if($res !== false){
	          $this->success('提交成功',U('statistic'),3);
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function editStatistic(){
			$id=I('get.id');
			$data=M('customer')->where(array('id'=>$id))->find();
			
			if($data['reservetime']){
				$data['reservetime']=date('Y-m-d H:i',$data['reservetime']);
			}
			if($data['endingtime']){
				$data['endingtime']=date('Y-m-d H:i',$data['endingtime']);
			}
			$this->assign('data',$data);
			$this->display('editStatistic');
		}

		public function doEditstatistic(){
			$data=I('post.');
			
			if($data['reservetime']){
				$data['reservetime']=$data['reservetime']. ':00';
				$data['reservetime']=strtotime($data['reservetime']);
			}
			if($data['endingtime']){
				$data['endingtime']=$data['endingtime']. ':00';
				$data['endingtime']=strtotime($data['endingtime']);
				$data['finish']=1;
			}
			$db=M('customer');
			$res=$db->where(array('id'=>$data['id']))->save($data);
	        if($res !== false){
	          $this->success('提交成功',U('statistic',array('pid'=>$data['pid'])),3);
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function delStatistic(){
			$id=I('get.id');
			$res=M('customer')->where(array('id'=>$id))->delete();
			if($res !== false){
	          $this->success('提交成功');
	        }else{
	          $this->success('提交失败');
	        }
		}

		public function viewStatistic(){
			$id=I('get.id');
			$data=M('customer')->where(array('id'=>$id))->find();
			$this->assign('data',$data);
			$this->display('viewStatistic');
		}

		public function schedul(){
			if (isset($_GET)) {
			$get=$_GET;
			foreach($get as$k => $v){
			if($k == 'status' && $v=='monthly'){
				$time=date('Y-m-d'). '00:00:00';
				$time=strtotime($time);
				$timeafter=date("Y-m-d",strtotime("+1 month")).' 23:59:59';
				$timeafter=strtotime($timeafter);
				$map['reservetime']=array('between',array($time,$timeafter));
					}else{
				$time=date('Y-m-d'). '00:00:00';
				$time=strtotime($time);
				$timeafter=date("Y-m-d",strtotime("+1 week")).' 23:59:59';
				$timeafter=strtotime($timeafter);
				$map['reservetime']=array('between',array($time,$timeafter));
					}
		   	 	}
			}
			$data=M('customer')->where($map)->select();
			$count=count($data);
			$this->assign('data',$data);
			$this->assign('count',$count);
			$this->display();
		}

		public function news(){
			$list=M('newscategory')->order('status ASC')->select();
			$this->assign('list',$list);

			if(isset($_GET['category'])){
				$where=array('category'=>$_GET['category']);
			   }

			$db=M('news');

			$getPageCounts = $db->where($where)->count();
        	$pageSize = 10;
        	$page = new \Think\Page($getPageCounts, $pageSize, $where);
        	$data = $db->where($where)->order('create_time DESC')->limit($page->firstRow, $page->listRows)
                       ->select();

            foreach($data as $k=>$v){
            	foreach($list as $key => $vo){
            		if($v['category'] == $vo['id']){
            			$data[$k]['cat']=$vo['title'];
            		}
            	}
            }

            $pageShow = $page->show();
        	$this->assign('page', $pageShow);
			$this->assign('data',$data);
			$this->display();
		}


		public function editNews(){
			$list=M('newscategory')->order('status ASC')->select();
			$this->assign('list',$list);
			$id=$_GET['id'];
			$data=M('news')->where(array('id'=>$id))->find();
			$this->assign('data',$data);

			$this->display('editNews');
		}

		public function doEditnewscategory(){
			$data=I('post.');
			$res=M('news')->where(array('id'=>$data['id']))->save($data);
			if($res !== false){
	          $this->success('提交成功','javascript:history.back(-1);',3);
	        }else{
	          $this->success('提交失败');
	        }
		}


		public function delNews(){
			$id=I('get.id');
			$res=M('news')->where(array('id'=>$id))->delete();
			if($res !== false){
	          $this->success('提交成功');
	        }else{
	          $this->success('提交失败');
	        }
		}






		public function getNews(){
				$Token=A('Index/Api')->getAccessToken();
    			$count=$this->totalStuff();
    			$page=floor($count['news_count']/20);
    			$url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$Token["access_token"];
    			$data1=array();
    			for($i=0;$i<=$page;$i++){
    				$offset=$i*20;
    				$data='{
    			 	"type":"news",
    			 	"offset":'.$offset.',
    		     	"count":20
			      	}';
				$res=$this->httpsPost($url,$data);
		        $news=json_decode($res,true);
		        $vv=$news['item'];
		        foreach($vv as $k=>$v){
		        	array_push($data1,$v);
		           }
		        }
		        rsort($data1);
		        S('weixin2',$data1);
		        $lala=S('weixin2');
		        if($lala){
		        	echo 'ok';
		        }
   		}


   		public function updateNews(){
   			$Token=A('Index/Api')->getAccessToken();
   			$new=M('news')->group('media_id')->select();
   			$news=count($new);
   			$count=$this->totalStuff();
   			if($count['news_count']>$news){
   				$offset=intval($count['news_count']-$news);
   				$page=floor($offset/20);
   				$url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$Token["access_token"];
    			$data1=array();
    			for($i=0;$i<=$page;$i++){
    				$offsets=$i*20;
    				if($page == 0 && $i==$page){
    					$data='{
    			 		"type":"news",
    			 		"offset":'.$offsets.',
    		     		"count":'.$offset.'
			      		}';
    				}elseif($page > 0 && $i<$page){
    					$data='{
    			 		"type":"news",
    			 		"offset":'.$offsets.',
    		     		"count":20
			      		}';
    				}elseif($page>0 && $i==$page){
    					$data='{
    			 		"type":"news",
    			 		"offset":'.$offsets.',
    		     		"count":'.$offset.'
			      		}';
    				}
			    $res=$this->httpsPost($url,$data);
		        $news=json_decode($res,true);
		        $vv=$news['item'];
		        foreach($vv as $k=>$v){
		        	array_push($data1,$v);
		           }
		        }
		        rsort($data1);
		        if($data1){
		        	$data2=array();
		        	foreach($data1 as $k=>$v){
		        		$data2['media_id']=$v['media_id'];
		        		$data2['create_time']=$v['update_time'];
		        		foreach($v['content']['news_item'] as $key=>$vo){
		        		$data2['title']=$vo['title'];
		        		$data2['url']=$vo['url'];
		        		$data2['thumb_url']=$this->getNewsimg($vo['thumb_media_id']);
		        		$res=M('news')->add($data2);
   			  				}
		        	}
		        }
		        if($res){
		        	echo 1;
		        }else{
		        	echo 0;
		        }
   			}
   		}


   		public function saveNews(){
	   			$data1=S('weixin2');
	   			$db=M('news');
	   			$data2=array();
	   			foreach($data1 as $k => $v){
   					$data2['media_id']=$v['media_id'];
		        	$data2['create_time']=$v['update_time'];
		        	foreach($v['content']['news_item'] as $key=>$vo){
		        		$data2['title']=$vo['title'];
		        		$data2['url']=$vo['url'];
		        		$data2['thumb_url']=$this->getNewsimg($vo['thumb_media_id']);
		        		$res=$db->add($data2);
   			  }
   		  	}
   	    }


   		// public function lala(){
   		// 	$data=S('weixin');
   		// 	$data1=array();
   		// 	foreach($data as $k => $v){
   		// 		foreach($v as $key => $vo){
   		// 			array_push($data1, $vo);
   		// 		}
   		// 	}
   		// 	dump($data1);
   		// }

   		
   		public function checkUpdate(){
   			$new=M('news')->group('media_id')->select();
   			$news=count($new);
   			$count=$this->totalStuff();
   			if($count['news_count']>$news){
   				$offset=$count['news_count']-$news;
   				echo $offset;
   			}elseif($count['news_count']==$news){
   				echo 0;
   			}else{
   				echo -1;
   			}
   		}



   		public function getStuff($type){
    	$Token=A('Index/Api')->getAccessToken();
    	$url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$Token["access_token"];
        $data='{
    		"type":"'.$type.'",
    		"offset":0,
    		"count":10
			}';
		$res=$this->httpsPost($url,$data);
		$res=json_decode($res,true);
		dump($res);
		die;
		return $res;
   		}

   		public function totalStuff(){
    	$Token=A('Index/Api')->getAccessToken();
    	$url="https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$Token["access_token"];
		$res=$this->httpsPost($url,$data);
		$res=json_decode($res,true);
		return $res;
   		}

    	public function getNewsimg($media_id){
    		$Token=A('Index/Api')->getAccessToken();
    		$url="https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$Token["access_token"];
    		$data='{
					"media_id":"'.$media_id.'"
					}';
			$res=$this->httpsPost($url,$data);
			$file=$this->savenewImg($res,$media_id);
			return $file;
    	}


    	private function savenewImg($res,$media_id){
		$path='./Uploads/Weixin/Newsimg';
		if(!file_exists($path)){
			mkdir($path,0777,true);
		}
		$filename=$media_id.'.jpg';
		$file=$path.'/'.$filename;
		$local_file=fopen($file,'w');
		if($local_file){
		 	fwrite($local_file,$res);
			fclose($local_file);
		   }
		  return $file;
		}

		

		public function uploadImage(){
	    	$appid=C('WEI_APPID');
			$secret=C('WEI_SECRET');
			$Token=A('Api')->getAccessToken();
			$WechatAuth = new WechatAuth($appid, $secret, $Token["access_token"]);
			$userimg = './1.jpg';
		    $type = 'image';
			$media = $WechatAuth->materialUpload($userimg,$type);
			$res=json_decode($media);
			dump($res);
			die;
    }


    	public function replayWords(){
    		
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