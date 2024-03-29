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


  protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
  {
    if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
      return true;
    } else {
      return false;
    }
  }

  protected function isNotEmpty($value, $rule = '', $data = '', $field = '') {
    if (empty($value)) {
      return false;
    } else {
      return true;
    }
  }
}
