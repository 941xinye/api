<?php
namespace app\modules\api\controllers;


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

    }
    public function actionLogin()
    {
        $loginForm = new LoginForm();
        $this->return = $loginForm->userMobileAndPasswordLogin($this->params['mobile'],$this->params['password'],$this->params['pushid'],$this->params['plat'],$this->return);
        return $this->return;
    }

    /**
     * 
     * @return mixed
     */
    public function actionSendMsg()
    {
        $common = new Common();
        $res = $common->sendNotifyCode($this->params['mobile']);
        if($res['code']){
            $this->return['state'] = 1;
            $this->return['message'] = 'ok';
        }else{
            $this->return['state'] = $res['code'];
            $this->return['message'] = $res['msg'];
        }
        return $this->return;
    }
}
