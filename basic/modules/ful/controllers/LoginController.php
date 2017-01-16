<?php
namespace app\modules\ful\controllers;


use app\backend\libs\Common;
use app\models\LoginForm;
use Yii;
use yii\helpers\Url;
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

    public function actionTt(){
        $url = Url::to(['/api/saki/opening']);
        $header = array('access-token:feafc705c0ea25457467df804040f55f');
        $content = array(
            'name' => 'fdipzone'
        );
        $ch = curl_init();
        if(substr($url,0,5)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
        $response = curl_exec($ch);
        if($error=curl_error($ch)){
            die($error);
        }
        curl_close($ch);
        return $response;
    }
}
