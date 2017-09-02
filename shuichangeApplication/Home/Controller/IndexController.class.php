<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

    function __construct(){ //构造方法 
    parent::__construct();
    }

	//首页
    public function index(){
        $mobile = parent::isMobile();
        if($mobile=="true"){
           $db=M('muyeindex');
            $data=$db->order('orderlist DESC')->select();
            $this->assign('data',$data);
            $this->display('main');
        }else{
            $db=M('video');
            $data=$db->order('rand()')->limit(1)->find();
            $this->assign('data',$data);
            $this->display();
        }

    }

    //單頁
    public function single(){
        $pid=I('get.pid');
        $db=M('muyesingle');
        $data=$db->where(array('pid'=>$pid))->where(array('status'=>1))->order('orderlist ASC')->select();
        $this->assign('data',$data);
        $this->display();
    }

    public function main(){
        $db=M('muyeindex');
        $data=$db->order('orderlist DESC')->select();
        $this->assign('data',$data);
        $this->display('main');
    }

    public function view(){
        $time=date('Y-m-d'). '00:00:00';
            $time=strtotime($time);
            $timeafter=date("Y-m-d",strtotime("+1 month")).' 23:59:59';
            $timeafter=strtotime($timeafter);
            $map['reservetime']=array('between',array($time,$timeafter));
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

            $data=M('customer')->where($map)->order('reservetime ASC')->select();
            $count=count($data);
            $this->assign('data',$data);
            $this->assign('count',$count);
            $this->display();
    }

}