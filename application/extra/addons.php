<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'addons_pay_paid' => 
    array (
      0 => 'pay',
    ),
    'addons_pay_notify' => 
    array (
      0 => 'pay',
    ),
    'sms_send' => 
    array (
      0 => 'rlsms',
    ),
    'sms_notice' => 
    array (
      0 => 'rlsms',
    ),
    'sms_check' => 
    array (
      0 => 'rlsms',
    ),
  ),
  'route' => 
  array (
    '/qrcode$' => 'qrcode/index/index',
    '/qrcode/build$' => 'qrcode/index/build',
  ),
);