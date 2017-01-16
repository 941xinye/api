<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%common_verifycode}}".
 *
 * @property string $id
 * @property string $mobile
 * @property string $getip
 * @property string $verifycode
 * @property string $dateline
 * @property string $reguid
 * @property string $regdateline
 * @property integer $status
 */
class CommonVerifycode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%common_verifycode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'getip', 'verifycode'], 'required'],
            [['dateline', 'reguid', 'regdateline', 'status'], 'integer'],
            [['getip'], 'string', 'max' => 15],
            [['verifycode'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'getip' => 'Getip',
            'verifycode' => 'Verifycode',
            'dateline' => 'Dateline',
            'reguid' => 'Reguid',
            'regdateline' => 'Regdateline',
            'status' => 'Status',
        ];
    }
    /**
     * 失效指定手机验证码
     * @param $mobile
     */
    public function invalidCodeByCode($mobile, $code)
    {
        $this->updateAll(['status' => 0], ['mobile'=>$mobile, 'verifycode'=>$code]);
    }
    /**
     * 失效手机验证码
     * @param $mobile
     */
    public function invalidCode($mobile)
    {
        $this->updateAll(['status' => 0], 'mobile = :mobile', [':mobile' => $mobile]);
    }

    /**
     * 校验手机验证码
     * @param $mobile
     * @param $code
     * @return bool
     */
    public function checkCode($mobile, $code)
    {
        $result = $this->find()->andWhere(['mobile'=>$mobile, 'status'=>1, 'verifycode'=>$code])->orderBy(['id' => SORT_DESC])->one();

        if(count($result)>0){
            $time = $result->dateline + (3600*24) - time();
            if($time>0){
                return true;
            }else{
                self::invalidCode($mobile);
            }
        }
        return false;
    }
}
