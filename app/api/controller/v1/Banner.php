<?php

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use Exception;
use think\facade\Config;

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
    $banner = BannerModel::getBannerByID($id);
    $banner->hidden(['update_time', 'delete_time']);
    if (!$banner) {
      throw new BannerMissException();
    }
    return $banner;
  }
}
