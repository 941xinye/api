<?php
namespace app\modules\ful\controllers;
use app\models\Members;
use Yii;

//use yii\rest\UrlRule;
class UserController extends BaseController
{
    public function actionInfo()
    {
        $memId = Yii::$app->user->identity->mem_id;
        $this->return->data = Members::findOne($memId);
        $this->return->state = 1;
        $this->return->message = 'ok';
        return $this->return;
    }

}
