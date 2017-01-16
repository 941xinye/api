<?php
namespace app\modules\ful\controllers;
use app\backend\libs\Common;
use app\models\Members;
use app\models\Saki;
use Yii;

//use yii\rest\UrlRule;
class SakiController extends BaseController
{
    /**
     * 开局
     * @return \app\models\ApiReturn
     */
    public function actionOpening(){
        $res = Saki::Instance(Saki::TYPE_SAKI);
        return $res->getOpening();
    }

}
