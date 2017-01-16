<?php
namespace app\modules\api\controllers;
use app\models\Members;
use app\modules\ful\controllers\BaseController;
use Yii;

//use yii\rest\UrlRule;
class UserController extends BaseController
{
    public function actionInfo()
    {
        $memId = Yii::$app->user->identity->mem_id;
        $this->return['data'] = Members::findOne($memId);
        $this->return['state'] = 1;
        $this->return['message'] = 'ok';
        return $this->return;
    }

}
