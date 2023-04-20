<?php

namespace app\api\validate;

use Exception;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
  public function goCheck()
  {
    // 获取 Http 传入的参数再进行校验
    $params = Request::param();

    $result = $this->check($params);
    if (!$result) {
      $error = $this->error;
      throw new Exception($error);
    } else {
      return true;
    }
  }
}
