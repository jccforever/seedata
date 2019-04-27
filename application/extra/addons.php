<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'sms_send' => 
    array (
      0 => 'clsms',
    ),
    'sms_notice' => 
    array (
      0 => 'clsms',
    ),
    'sms_check' => 
    array (
      0 => 'clsms',
    ),
    'addons_pay_paid' => 
    array (
      0 => 'pay',
    ),
    'addons_pay_notify' => 
    array (
      0 => 'pay',
    ),
  ),
  'route' => 
  array (
    '/qrcode$' => 'qrcode/index/index',
    '/qrcode/build$' => 'qrcode/index/build',
  ),
);