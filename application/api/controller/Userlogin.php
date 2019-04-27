<?php
namespace app\api\controller;
use app\common\controller\Api;
use think\Db;
use think\Request;
/**
 * 公众号扫码登录
 */
class User extends Api
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }
    //接收微信端返回的数据
    public function is_wechat_login(Request $request)
    {
        if($request->isPost())
        {
            $userName = input('post.userName');
            $is_user = Db::name('user')->where('username',$userName)->find();
            if($is_user){
                
            }
        }
    }
}