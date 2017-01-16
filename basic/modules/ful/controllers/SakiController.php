<?php
namespace app\modules\ful\controllers;
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
        $this->return->data = $res->getOpening();
        $this->return->state = 1;
        $this->return->message = 'ok';
        return $this->return;
    }

}
