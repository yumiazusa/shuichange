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

	//首页
    public function index(){
        $db=M('muyeindex');
        $data=$db->order('orderlist ASC')->select();
        $this->assign('data',$data);
        $this->display();
    }

    //單頁
    public function single(){
        $pid=I('get.pid');
        $db=M('muyesingle');
        $data=$db->where(array('pid'=>$pid))->where(array('status'=>1))->order('orderlist ASC')->select();
        $this->assign('data',$data);
        $this->display();
    }

}