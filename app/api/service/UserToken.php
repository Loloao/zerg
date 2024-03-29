<?php


namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
  protected $code;
  protected $wxAppID;
  protected $wxAppSecret;
  protected $wxLoginUrl;

  function __construct($code) {
    $this->code = $code;
    $this->wxAppID = config('wx.app_id');
    $this->wxAppSecret = config('wx.app_secret');
    $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
  }

  public function get($code) {
    $result = curl_get($this->wxLoginUrl);
    $wxResult = json_decode($result, true);
    if (empty($wxResult)) {
      throw new Exception('获取session_key及openID时异常，微信内部错误');
    } else {
      $loginFail = array_key_exists('errcode', $wxResult);
      if ($loginFail) {
        $this->processLoginError($wxResult);
      } else {
        $this->grantToken($wxResult);
      }
    }
  }

  private function grantToken($wxResult) {
    // 拿到 openid
    // 看数据库，这个 openid 是否存在
    // 如果存在则不处理，如果不存在则新增一条 user 记录
    // 生成令牌，准备缓存数据，写入缓存
    // 把令牌返回到客户端去
    $openid = $wxResult['openid'];
    $user = UserModel::getByOpenID($openid);
    if ($user) {
      $uid = $user->id;
    } else {
      $uid = $this->newUser($openid);
    }
    $cachedValue = $this->prepareCachedValue($wxResult, $uid);
  }

  private function saveToCache($cachedValue) {
    $key = generateToken();
  }

  private function newUser($openid) {
    $user = UserModel::create([
      'openid' => $openid
    ]);
    return $user->id;
  }

  private function prepareCachedValue($wxResult, $uid) {
    $cachedValue = $wxResult;
    $cachedValue['uid'] = $uid;
    $cachedValue['scope'] = 16;
    return $cachedValue;
  }

  private function processLoginError($wxResult) {
    throw new WeChatException([
      'msg' => $wxResult['errmsg'],
      'errorCode' => $wxResult['errcode']
    ]);
  }
}