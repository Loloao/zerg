<?php


namespace app\api\model;


use think\facade\Config;
use think\Model;

class BaseModel extends Model
{

  public function prefixImgUrl($value, $data) {
    $finalUrl = $value;
    if ($data["from"] == 1) {
      $finalUrl = Config::get('web.img_prefix').$value;
    }
    return $finalUrl;
  }
}