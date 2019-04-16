<?php

return array (
  0 => 
  array (
    'name' => 'rechargetips',
    'title' => '充值提示文字',
    'type' => 'text',
    'content' => 
    array (
    ),
    'value' => '充值没有金额限制，请根据情况充值，余额不可提现',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'moneylist',
    'title' => '充值金额列表',
    'type' => 'array',
    'content' => 
    array (
    ),
    'value' => 
    array (
      '￥30pyu' => '30',
      '￥80' => '80',
      '￥160' => '160',
      '￥300' => '300',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'defaultmoney',
    'title' => '默认充值金额',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '30',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  3 => 
  array (
    'name' => 'iscustommoney',
    'title' => '是否开启任意金额',
    'type' => 'radio',
    'content' => 
    array (
      1 => '开启',
      0 => '关闭',
    ),
    'value' => '1',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  4 => 
  array (
    'name' => 'paytypelist',
    'title' => '支付方式',
    'type' => 'checkbox',
    'content' => 
    array (
      'wechat' => '微信支付',
      'alipay' => '支付宝支付',
    ),
    'value' => 'alipay',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  5 => 
  array (
    'name' => 'defaultpaytype',
    'title' => '默认支付方式',
    'type' => 'radio',
    'content' => 
    array (
      'wechat' => '微信支付',
      'alipay' => '支付宝支付',
    ),
    'value' => 'alipay',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);
