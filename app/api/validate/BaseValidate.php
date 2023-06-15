<?php

namespace app\api\validate;

use app\lib\exception\ParameterException;
use Exception;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
  public function goCheck()
  {
    // 获取 Http 传入的参数再进行校验
    $params = Request::param();

    // 可以对多个规则进行验证
    $result = $this->batch()->check($params);
    if (!$result) {
      $e = new ParameterException([
        'msg' => $this->error,
      ]);
      throw $e;
    } else {
      return true;
    }
  }
}
