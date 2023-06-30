<?php

namespace app\api\model;

use think\facade\Db;

class Banner
{
  public static function getBannerByID($id)
  {
    // TODO: 根据 Banner ID 号获取 Banner 信息
    $result = Db::table('banner_item')->where('banner_id', '=', $id)->select();
    return $result;
  }
}
