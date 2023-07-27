<?php


namespace app\api\controller\v1;


use app\api\model\BaseModel;
use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme extends BaseModel
{
  /**
   * @url /theme?ids=id1,id2,id3
   * @return 一组 theme 模型
   */
  public function getSimpleList($ids='') {
    (new IDCollection())->goCheck();
    $ids=explode(',', $ids);
    $result = ThemeModel::with(['topicImg', 'headImg'])->select($ids);
    if ($result->isEmpty()) {
      throw new ThemeException();
    }
    return $result;
  }

  /**
   * @param $id
   * @url /theme/:id
   */
  public function getComplexOne($id) {
    (new IDMustBePositiveInt())->goCheck();
    $theme=ThemeModel::getThemeWithProducts($id);
    if (!$theme) {
      throw new ThemeException();
    }
    return $theme;
  }
}