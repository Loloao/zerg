<?php


namespace app\api\service;


class Token
{
  public static function generateToken() {
    // 32 字符组成一组随机字符串
    $randChars = getRandChar(32);
    // 用三组字符串进行 md5 加密
    $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
  }
}