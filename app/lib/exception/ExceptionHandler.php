<?php

namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;
use think\facade\Env;
use think\facade\Log;
use think\Response;
use Throwable;

class ExceptionHandler extends Handle
{
  private $code;
  private $msg;
  private $errorCode;

  public function render($request, Throwable $e): Response
  {
    // 需要返回客户端当前请求的 URL 路径

    if ($e instanceof BaseException) {
      // 如果是自定义的异常
      $this->code = $e->code;
      $this->msg = $e->msg;
      $this->errorCode = $e->errorCode;
    } else {
      // 当为开发模式时需要直接在页面输出错误
      if (Env::get('APP_DEBUG')) {
        return parent::render($request, $e);
      }
      $this->code = 500;
      $this->msg = '服务器内部错误';
      $this->errorCode = 999;
      $this->recordErrorLog($e);
    }
    $result = [
      'msg' => $this->msg,
      'error_code' => $this->errorCode,
      'request_url' => $request->url()
    ];
    return json($result, $this->code);
  }

  private function recordErrorLog(Exception $e)
  {
    Log::record($e->getMessage(), 'error');
  }
}
