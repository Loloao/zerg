<?php


namespace app\api\model;

use app\api\model\BaseModel;

class Theme extends BaseModel
{
  protected $hidden = ['topic_img_id','head_img_id', 'update_time', 'delete_time'];

  public function topicImg() {
    return $this->belongsTo('Image', 'topic_img_id', 'id');
  }

  public function headImg() {
    return $this->belongsTo('Image', 'head_img_id', 'id');
  }

  public function products() {
    return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
  }

  public static function getThemeWithProducts($id) {
    $theme = self::with(['products','topicImg','headImg'])->find($id);
    return $theme;
  }
}