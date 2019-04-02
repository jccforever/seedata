<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use app\common\library\Token;
use think\Db;
class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $newslist = Db::name('news')->where('category_id',1)->order('id desc')->limit(6)->select();
        $glist = Db::name('news')->where('category_id',0)->order('id desc')->limit(6)->select();
        $this->view->assign('newslist', $newslist);
        $this->view->assign('glist', $glist);
		return $this->view->fetch();	

    }
    public function news($ids = NULL)
    {
		$data = Db::name('news')->where('id',$ids)->find();
		if(!$data){$this->error("请求数据有误");}
		$newslist = Db::name('news')->order('id desc')->limit(10)->select();
		
		$this->view->assign('data', $data);
		$this->view->assign('newslist', $newslist);
        return $this->view->fetch();
    }
    public function lists($ids = NULL)
    {

        if($ids ==0){
            $title = '会员公告';
        }elseif ($ids ==1){
            $title = '帮助说明';
        }else{
            $this->error("请求数据有误".$ids);
        }
        $newslist = Db::name('news')->where('category_id',$ids)->order('id desc')->paginate(10);
        $page = $newslist->render();
        $this->assign('page', $page);
        $this->view->assign('newslist', $newslist);
        $this->assign('title', $title);
        return $this->view->fetch();
    }

}
