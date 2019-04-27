<?php

namespace addons\wechat\controller;

use app\common\model\WechatAutoreply;
use app\common\model\WechatContext;
use app\common\model\WechatResponse;
use app\common\model\WechatConfig;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use addons\wechat\library\Wechat as WechatService;
use addons\wechat\library\Config as ConfigService;
use think\Log;
use \think\Db;

/**
 * 微信接口
 */
class Index extends \think\addons\Controller
{

    public $app = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->app = new Application(ConfigService::load());
    }

    /**
     *
     */
    public function index()
    {
        $this->error("当前插件暂无前台页面");
    }

    /**
     * 微信API对接接口
     */
    public function api()
    {
        $this->app->server->setMessageHandler(function ($message) {

            $WechatService = new WechatService;
            $WechatContext = new WechatContext;
            $WechatResponse = new WechatResponse;

            $openid = $message->FromUserName;
            $to_openid = $message->ToUserName;
            $event = $message->Event;
            $eventkey = $message->EventKey ? $message->EventKey : $message->Event;

            $unknownmessage = WechatConfig::value('default.unknown.message');
            $unknownmessage = $unknownmessage ? $unknownmessage : "没找到对应指令!";

            switch ($message->MsgType) {
                case 'event': //事件消息
                    switch ($event) {
                        case 'subscribe'://添加关注
                           //当用户未关注公众号时 进行公众号的推送事件
                            $sql_search = Db::name('user')->where('openid',$openid)->find();
                            if($sql_search){
                                $arr = explode(',',$eventkey);
                                $session_id = trim(str_replace('qrscene_s=','',$arr[0]));
                                $timestamp = trim(str_replace('h=','',$arr[1])); 
                                $update = [
                                    'is_first'=>1,
                                  	'session_id'=>$session_id,
                                  	'uuid'=>$timestamp
                                ];
                                //存入数据库 更新用户消息
                                $sql = Db::name('user')->where('openid',$openid)->update($update);
                                return "您于".date('Y-m-d H:i:s').'进行了扫码登录操作';
                            }else{
                                return "你还没有绑定看数据网站的微信扫码登陆功能，<a href='http://www.63996.com/user/login.html?openid=".$openid."'>请点击进行绑定操作</a>";
                            }
                        case 'unsubscribe'://取消关注
                            return '';
                        case 'SCAN'://扫描已经关注的公众号
                            $arr = explode(',',$eventkey);
                            $session_id = trim(str_replace('s=','',$arr[0]));
                            $timestamp = trim(str_replace('h=','',$arr[1])); 
                           	$update = [
                                'is_first'=>0,
                              	'session_id'=>$session_id,
                              	'uuid'=>$timestamp
                            ];
                            $sql = Db::name('user')->where('openid',$openid)->update($update);   
                        	return "okok";
                        case 'LOCATION'://获取地理位置
                            return '';
                        case 'VIEW': //跳转链接,eventkey为链接
                            return '';
                        default:
                            break;
                    }

                    $response = $WechatResponse->where(["eventkey" => $eventkey, 'status' => 'normal'])->find();
                    if ($response) {
                        $content = (array)json_decode($response['content'], TRUE);
                        $context = $WechatContext->where(['openid' => $openid])->find();
                        $data = ['eventkey' => $eventkey, 'command' => '', 'refreshtime' => time(), 'openid' => $openid];
                        if ($context) {
                            $WechatContext->data($data)->where('id', $context['id'])->update();
                            $data['id'] = $context['id'];
                        } else {
                            $id = $WechatContext->data($data)->save();
                            $data['id'] = $id;
                        }
                        $result = $WechatService->response($this, $openid, $content, $data);
                        if ($result) {
                            return $result;
                        }
                    }
                    return $unknownmessage;
                case 'text': //文字消息
                case 'image': //图片消息
                case 'voice': //语音消息
                case 'video': //视频消息
                case 'location': //坐标消息
                case 'link': //链接消息
                default: //其它消息
                    //上下文事件处理
                    $context = $WechatContext->where(['openid' => ['=', $openid], 'refreshtime' => ['>=', time() - 1800]])->find();
                    if ($context && $context['eventkey']) {
                        $response = $WechatResponse->where(['eventkey' => $context['eventkey'], 'status' => 'normal'])->find();
                        if ($response) {
                            $WechatContext->data(array('refreshtime' => time()))->where('id', $context['id'])->update();
                            $content = (array)json_decode($response['content'], TRUE);
                            $result = $WechatService->command($this, $openid, $content, $context);
                            if ($result) {
                                return $result;
                            }
                        }
                    }
                    //自动回复处理
                    if ($message->MsgType == 'text') {
                        $wechat_autoreply = new WechatAutoreply();
                        $autoreply = $wechat_autoreply->where(['text' => $message->Content, 'status' => 'normal'])->find();
                        if ($autoreply) {
                            $response = $WechatResponse->where(["eventkey" => $autoreply['eventkey'], 'status' => 'normal'])->find();
                            if ($response) {
                                $content = (array)json_decode($response['content'], TRUE);
                                $context = $WechatContext->where(['openid' => $openid])->find();
                                $result = $WechatService->response($this, $openid, $content, $context);
                                if ($result) {
                                    return $result;
                                }
                            }
                        }
                    }
                    return $unknownmessage;
            }
            return ""; //SUCCESS
        });
        $response = $this->app->server->serve();
        // 将响应输出
        $response->send();
    }

    /**
     * 登录回调
     */
    public function callback()
    {

    }

    /**
     * 支付回调
     */
    public function notify()
    {
        Log::record(file_get_contents('php://input'), "notify");
        $response = $this->app->payment->handleNotify(function ($notify, $successful) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $orderinfo = Order::findByTransactionId($notify->transaction_id);
            if ($orderinfo) {
                //订单已处理
                return true;
            }
            $orderinfo = Order::get($notify->out_trade_no);
            if (!$orderinfo) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态,已经支付成功了就不再更新了
            if ($orderinfo['paytime']) {
                return true;
            }
            // 用户是否支付成功
            if ($successful) {
                // 请在这里编写处理成功的处理逻辑

                return true; // 返回处理完成
            } else { // 用户支付失败
                return true;
            }
        });

        $response->send();
    }

}
