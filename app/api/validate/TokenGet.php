<?php


namespace app\api\validate;


class TokenGet extends BaseValidate
{
  protected $rule = [
    'code' => 'require|isNotEmpty'
  ];

  protected $message = [
    'code' => '没有token不能获取code'
  ];
}