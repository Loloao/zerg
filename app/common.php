<?php
// 应用公共文件
/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */

function curl_get($url, $httpCode = 0) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  // 不做证书校验，部署在 linux 环境下请改为 true
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  $file_contents = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return $file_contents;
}

function  getRandChar($length) {
  $str = null;
  $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
  $max = strlen($strPol) - 1;

  for ($i = 0;$i < $length;$i++) {
    $str .= $strPol[rand(0, $max)];
  }

  return $str;
}