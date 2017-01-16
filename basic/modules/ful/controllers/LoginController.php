<?php
namespace app\modules\ful\controllers;


use app\backend\libs\Common;
use app\models\LoginForm;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
//use yii\rest\UrlRule;
class LoginController extends Controller{
    public $modelClass = 'app\models\Members';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    private $params;
    public function behaviors()
    {
        $this->params = Yii::$app->request->queryParams;
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,

            ],
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        return ['msg'=>'fail'];
    }
    public function actionLogin()
    {
        $loginForm = new LoginForm();
        return $loginForm->userMobileAndPasswordLogin($this->params['mobile'],$this->params['password'],$this->params['pushid'],$this->params['plat']);
    }

    /**
     *
     * @return mixed
     */
    public function actionSendMsg()
    {
        $common = new Common();
        return $common->sendNotifyCode($this->params['mobile']);
    }
}
