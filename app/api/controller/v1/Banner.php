<?php

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use Exception;

/**
 * @id banner 的 id
 * @url /banner/:id
 * @http GET
 */
class Banner
{
  public function getBanner($id)
  {
    (new IDMustBePositiveInt())->goCheck();
    try {

      $banner = BannerModel::getBannerByID($id);
    } catch (Exception $ex) {
      $arr = [
        'error_code' => 10001,
        'msg' => $ex->getMessage()
      ];
      return json($arr);
    }
    return $banner;
  }
}
