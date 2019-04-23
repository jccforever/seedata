<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;
use think\Db;
use think\Request;
/**
 * 会员中心
 */
class User extends Frontend
{

    protected $layout = 'default';
    protected $noNeedLogin = ['login', 'register', 'third','async_callback'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;

        if (!Config::get('fastadmin.usercenter')) {
            $this->error(__('User center already closed'));
        }

        $ucenter = get_addon_info('ucenter');
        if ($ucenter && $ucenter['state']) {
            include ADDON_PATH . 'ucenter' . DS . 'uc.php';
        }

        //监听注册登录注销的事件
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('hook_test',function(){
           Cookie::set('Myself','Test hook');
        });
    }

    /**
     * 空的请求
     * @param $name
     * @return mixed
     */
    public function _empty($name)
    {
        Hook::listen("user_request_empty", $name);
        return $this->view->fetch('user/' . $name);
    }

    /**
     * 会员中心
     */
    public function index()
    {
        $user = $this->auth->username;
        $uid = $this->auth->id;
        $level = $this->auth->level;
        $keywords_num = Db::name('links_keywords')->where('user_id',$uid)->count('keywords');
        $competitor_num = Db::name('competitor')->where('user_id',$uid)->count('competitor_url');
        $user_level = Db::name('user')->where('id',$uid)->field('level,expire_time')->find();
        $level_name = Db::name('user_level')->where('id',$user_level['level'])->value('level_name');
        $remark = $uid.mt_rand(10,99);
        $url = $_SERVER['SERVER_NAME'].'/user/register?remark='.$remark;
        $expire_time = date('Y-m-d',$user_level['expire_time']);
        if($level==2){
            $expire_time ="永久";
        }
        if($level==3 || $level==4){
            if(strtotime($expire_time)-time()<10){
                $expire_time = "已过期";
            }
        }
        $levelInfo = Db::name('user_level')->select();
        $level_name_array = [];
        $page_array = [];
        $monitor_crontabs = [];
        $contend_crontabs = [];
        foreach($levelInfo as $key=>$val){
            $level_name_array[$key] = $val['level_name'];
            $page_array[$key] = $val['page'];
            $monitor_crontabs[$key] = $val['monitor_crontab'];
            $contend_crontabs[$key] = $val['contend_crontab'];
        }
        $vip_price_info = Db::name('vip_price')->select();
        $this->assign('userName',$user);
        $this->assign('keywordsNum',$keywords_num);
        $this->assign('competitorNum',$competitor_num);
        $this->assign('level_name',$level_name);
        $this->assign('expire_time',$expire_time);
        $this->assign('url',$url);
        $this->assign('level_name_array',$level_name_array);
        $this->assign('page_array',$page_array);
        $this->assign('monitor_crontabs',$monitor_crontabs);
        $this->assign('contend_crontabs',$contend_crontabs);
        $this->assign('vip_price_info',$vip_price_info);
        return $this->view->fetch();
    }

    /**
     * 注册会员
     */
    public function register()
    {
        session_start();
        $url = $this->request->request('url');
        $fid = $this->request->request('remark');
        if($fid){
            $fid = substr($fid,0,-2);
            Session::set('fid',$fid);
        }
        if($fid ==null){
            $fid = Session::get('fid');
        }
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), $url);
        if ($this->request->isPost()) {
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $email = $this->request->post('email');
            $mobile = $this->request->post('mobile', '');
            $captcha = $this->request->post('captcha');
            $friendid = $this->request->post('fid');
            $token = $this->request->post('__token__');
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:6,30',
                'email'     => 'require|email',
                'mobile'    => 'regex:/^1\d{10}$/',
                'captcha'   => 'require|captcha',
                '__token__' => 'token',
            ];

            $msg = [
                'username.require' => 'Username can not be empty',
                'username.length'  => 'Username must be 3 to 30 characters',
                'password.require' => 'Password can not be empty',
                'password.length'  => 'Password must be 6 to 30 characters',
                'captcha.require'  => 'Captcha can not be empty',
                'captcha.captcha'  => 'Captcha is incorrect',
                'email'            => 'Email is incorrect',
                'mobile'           => 'Mobile is incorrect',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                'email'     => $email,
                'mobile'    => $mobile,
                'captcha'   => $captcha,
                '__token__' => $token,
            ];
            $validate = new Validate($rule, $msg);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
            }
            if ($this->auth->register($username, $password, $email, $mobile,['friendid'=>$friendid,'level'=>2])) {
                $synchtml = '';
                ////////////////同步到Ucenter////////////////
                if (defined('UC_STATUS') && UC_STATUS) {
                    $uc = new \addons\ucenter\library\client\Client();
                    $synchtml = $uc->uc_user_synregister($this->auth->id, $password);
                }
                //更新被推广者的信息
                if($friendid){
                    $pusher = Db::name('user')->where('id',$friendid)->find();
                    $level = $pusher['level'];
                    $expire_time = $pusher['expire_time'];
                    if($level==2){
                        $update = update_pusher($level,$expire_time,$friendid,$duration='5day',time());
                        $sql_update = Db::name('user')->update($update);
                        $insert = [
                            'parent_id'=>$friendid,
                            'son_name'=>$username,
                            'memo'=>'推广用户获得5天高级会员',
                            'add_time'=>date('Y-m-d H:i:s',time())
                        ];
                        $sql_insert = Db::name('invite_list')->insert($insert);
                    }
                    if($level==3){
                        $update = update_pusher($level,$expire_time,$friendid,$duration='5day',time());
                        $sql_update = Db::name('user')->update($update);
                        $insert = [
                            'parent_id'=>$friendid,
                            'son_name'=>$username,
                            'memo'=>'推广用户获得5天高级会员',
                            'add_time'=>date('Y-m-d H:i:s',time())
                        ];
                        $sql_insert = Db::name('invite_list')->insert($insert);
                    }
                    if($level==4){
                        $update = update_pusher($level,$expire_time,$friendid,$duration='2day',time());
                        $sql_update = Db::name('user')->update($update);
                        $insert = [
                            'parent_id'=>$friendid,
                            'son_name'=>$username,
                            'memo'=>'推广用户获得2天VIP会员',
                            'add_time'=>date('Y-m-d H:i:s',time())
                        ];
                        $sql_insert = Db::name('invite_list')->insert($insert);
                    }
                }
                $this->success(__('Sign up successful') . $synchtml, $url ? $url : url('user/index'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        //判断来源
        $referer = $this->request->server('HTTP_REFERER');
        if (!$url && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
            && !preg_match("/(user\/login|user\/register)/i", $referer)) {
            $url = $referer;
        }
        $this->view->assign('fid', $fid);
        $this->view->assign('url', $url);
        $this->view->assign('title', __('Register'));
        return $this->view->fetch();
    }

    /**
     * 会员登录
     */
    public function login()
    {
        $url = $this->request->request('url');
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), '/user/index.html');
        if ($this->request->isPost()) {
            $account = $this->request->post('account');
            $password = $this->request->post('password');
            $keeplogin = (int)$this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $rule = [
                'account'   => 'require|length:3,50',
                'password'  => 'require|length:6,30',
                '__token__' => 'token',
            ];

            $msg = [
                'account.require'  => 'Account can not be empty',
                'account.length'   => 'Account must be 3 to 50 characters',
                'password.require' => 'Password can not be empty',
                'password.length'  => 'Password must be 6 to 30 characters',
            ];
            $data = [
                'account'   => $account,
                'password'  => $password,
                '__token__' => $token,
            ];
            $validate = new Validate($rule, $msg);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                return FALSE;
            }
            if ($this->auth->login($account, $password)) {
                $synchtml = '';
                ////////////////同步到Ucenter////////////////
                if (defined('UC_STATUS') && UC_STATUS) {
                    $uc = new \addons\ucenter\library\client\Client();
                    $synchtml = $uc->uc_user_synlogin($this->auth->id);
                }
                $this->success(__('Logged in successful') . $synchtml, $url ? $url : url('user/index'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        //判断来源
        $referer = $this->request->server('HTTP_REFERER');
        if (!$url && (strtolower(parse_url($referer, PHP_URL_HOST)) == strtolower($this->request->host()))
            && !preg_match("/(user\/login|user\/register)/i", $referer)) {
            $url = $referer;
        }
        $this->view->assign('url', $url);
        $this->view->assign('title', __('Login'));
        return $this->view->fetch();
    }

    /**
     * 注销登录
     */
    function logout()
    {
        //注销本站
        $this->auth->logout();
        $synchtml = '';
        ////////////////同步到Ucenter////////////////
        if (defined('UC_STATUS') && UC_STATUS) {
            $uc = new \addons\ucenter\library\client\Client();
            $synchtml = $uc->uc_user_synlogout();
        }
        $this->success(__('Logout successful') . $synchtml, url('user/index'));
    }

    /**
     * 个人信息
     */
    public function profile()
    {
        $this->view->assign('title', __('Profile'));
        return $this->view->fetch();
    }

    /**
     * 修改密码
     */
    public function changepwd()
    {
        if ($this->request->isPost()) {
            $oldpassword = $this->request->post("oldpassword");
            $newpassword = $this->request->post("newpassword");
            $renewpassword = $this->request->post("renewpassword");
            $token = $this->request->post('__token__');
            $rule = [
                'oldpassword'   => 'require|length:6,30',
                'newpassword'   => 'require|length:6,30',
                'renewpassword' => 'require|length:6,30|confirm:newpassword',
                '__token__'     => 'token',
            ];

            $msg = [
            ];
            $data = [
                'oldpassword'   => $oldpassword,
                'newpassword'   => $newpassword,
                'renewpassword' => $renewpassword,
                '__token__'     => $token,
            ];
            $field = [
                'oldpassword'   => __('Old password'),
                'newpassword'   => __('New password'),
                'renewpassword' => __('Renew password')
            ];
            $validate = new Validate($rule, $msg, $field);
            $result = $validate->check($data);
            if (!$result) {
                $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                return FALSE;
            }

            $ret = $this->auth->changepwd($newpassword, $oldpassword);
            if ($ret) {
                $synchtml = '';
                ////////////////同步到Ucenter////////////////
                if (defined('UC_STATUS') && UC_STATUS) {
                    $uc = new \addons\ucenter\library\client\Client();
                    $synchtml = $uc->uc_user_synlogout();
                }
                $this->success(__('Reset password successful') . $synchtml, url('user/login'));
            } else {
                $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
            }
        }
        $this->view->assign('title', __('Change password'));
        return $this->view->fetch();
    }
    /**
     * 消费记录
     *
     * @return void
     */
    public function money_log()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('user_money_log')
                ->where($where)
                ->order($sort, $order)
                ->count();
            $list = Db::name('user_money_log')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 会员充值
     */
    public function pay(Request $request)
    {
        if($request->isPost()){
            $ids = input('post.ids');//产品id
            $secretkey = '123456';//秘钥
            $user_id = $this->auth->id;
            $vip_info = Db::name('vip_price')->where('id',$ids)->find();
            $expire_time = $vip_info['duration_time'];//过期时间
            $price = $vip_info['price'];//价格
            $pay_type = $vip_info['pay_type'];//付款方式
            $returnurl = "http://sd.com/user/index";
            $notifyurl = "http://sd.com/user/async_callback";//异步回调地址
            $extend = $user_id.','.$ids;//额外参数
            $out_order_id = date('Ymdhis',time()).rand(100,999).$user_id;//第三方单号
            $product_id = '';
            $format = 'html';
            $sign = md5(md5($price . $out_order_id . 'alipay' .$product_id. $notifyurl . $returnurl . $extend) . $secretkey);
            $params = [
                'price'        => $price,
                'out_order_id' => $out_order_id,
                'type'         => 'alipay',
                'product_id'   => $product_id,
                'notifyurl'    => $notifyurl,
                'returnurl'    => $returnurl,
                'extend'       => $extend,
                'sign'         => $sign,
                'format'       => $format,
            ];
            $url = "/addons/pay/api/create"."?".http_build_query($params);
            return ['code'=>1,'msg'=>'OK','url'=>$url];
        }    
    }
    /**
     * 异步回调通知
     */
    public function async_callback(Request $request)
    {
        if($request->isPost()){
            $order_id = $this->request->request('order_id', '');//订单号
            $out_order_id = $this->request->request('out_order_id', '');//外部订单号
            $price = $this->request->request('price', '');//订单价格
            $realprice = $this->request->request('realprice', '');//真是付款价格
            $type = $this->request->request('type', '');//交易类型
            $paytime = $this->request->request('paytime', '');//付款时间
            $extend = $this->request->request('extend', '');//业务参数
            $sign = $this->request->request('sign', '');//签名
            $secretkey = '123456';
            if ($sign != md5(md5($order_id . $out_order_id . $price . $realprice . $type . $paytime . $extend) . $secretkey)) {
                $this->error('签名错误了！');
            }
            //处理业务
            $extend = explode(',',$extend);
            $user_id = $extend[0];//用户id
            $vip_price_id = $extend[1];//会员价格id
            //会员价格信息
            $vip_info = Db::name('vip_price')->where('id',$vip_price_id)->find();
            $duration_time = strtotime($vip_info['date']);//会员时长
            $level = $vip_info['level_id'];//会员等级
            //用户信息
            $user_info = Db::name('user')->where('id',$user_id)->find();
            $user_level = $user_info['level'];//用户等级
            $expire_time = $user_info['expire_time'];//过期时间
            if($user_level==2 || $user_level==3){
                $expire_time = $duration_time;
            }
            if($user_level==4 ){
                if($expire_time-time()>10){
                    $expire_time = $expire_time-time();
                    $expire_time = $expire_time+$duration_time;
                }else{
                    $expire_time = 0;
                    $expire_time = $expire_time+$duration_time;
                }
            }
            $update = [
                'id'=>$user_id,
                'level'=>$level,
                'expire_time'=>$expire_time
            ];
            //更新用户信息
            $sql_update = Db::name('user')->update($update);
            //写入消费记录
            $insert = [
                'user_id'=>$user_id,
                'money'=>$price,
                'memo'=>'购买了'.$vip_info['duration_time'].'vip会员',
                'createtime'=>time()
            ];
            $sql_insert = Db::name('user_money_log')->insert($insert);
            echo "success";
        }else{
            echo '非法请求';
        }
    }
    /**
     * 邀请列表
     */
    public function invite_list(Request $request)
    {
        if($request->isAjax()){
            $id = $this->auth->id;
            $father_level = $this->auth->level;
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = Db::name('invite_list')
                    ->where('parent_id',$id)
                    ->order($sort, $order)
                    ->count();
            $list = Db::name('invite_list')
                    ->where('parent_id',$id)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);
            return json($result);
        }
        return $this->view->fetch();
    }
}
