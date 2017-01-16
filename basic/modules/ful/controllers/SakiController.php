<?php
namespace app\modules\ful\controllers;
use app\backend\libs\Curl;
use app\models\Saki;
use Yii;
use yii\helpers\Url;

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

    public function actionTt(){
        $curl = new Curl();
        return $curl->curl_get(Url::to(['/api/saki/opening']),['access-token: feafc705c0ea25457467df804040f55f']);
    }
}
