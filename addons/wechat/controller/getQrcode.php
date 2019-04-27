<?php
namespace addons\wechat\controller;

use EasyWeChat\Foundation\Application;
class getQrcode 
{
    public function get_qrcode($param)
    {
        $app = new Application(get_addon_config('wechat'));
        $qrcode = $app->qrcode;
        $result = $qrcode->temporary($param, 6 * 24 * 3600);
        $ticket = $result->ticket;// 或者 $result['ticket']
        $expireSeconds = $result->expire_seconds; // 有效秒数
        $url = $result->url; // 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片
        return $url;
    }
}