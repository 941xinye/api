<?php
namespace app\modules\ful\controllers;

use app\models\AppVersion;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
//use yii\rest\UrlRule;
class VersionController extends Controller{
    public $modelClass = 'app\models\Members';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_HTML,

            ],
        ];
        return $behaviors;
    }
    public function actionIndex()
    {
        $ver = AppVersion::find()->where(['platform'=>1,'status'=>1])->orderBy(['id'=>SORT_DESC])->one();
        $notice = $ver['desc'];

        $version = ['state'=>$ver['is_force_update'], 'version'=>$ver['version'], 'url'=>$ver['package'],'notice'=>$notice];
        return $version;
    }
    public function actionAndroid()
    {
        $version = ['state'=>1, 'version'=>Yii::$app->params['androidVersion']];
        return $version;
    }
}
