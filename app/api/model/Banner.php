<?php

namespace app\api\model;

class Banner extends BaseModel
{
  protected $hidden = ['update_time', 'delete_time'];
  public function items() {
    return $this->hasMany('BannerItem', 'banner_id', 'id');
  }
  public static function getBannerByID($id)
  {
    // TODO: 根据 Banner ID 号获取 Banner 信息
    return self::with(['items', 'items.img'])->find($id);
  }
}
