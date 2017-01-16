<?php

namespace app\models;
use app\service\member\MemberService;
use Yii;
use app\backend\libs;
use app\service\sms;
/**
 * pre_members 会员基本信息表的model
 */
class Members extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%members}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_provice', 'mem_city', 'status_code', 'created', 'updated'], 'integer'],
            [['mem_name', 'mem_true_name', 'mem_identify'], 'string', 'max' => 50],
            [['mem_mobile'], 'string', 'max' => 30],
            [['password', 'mem_email', 'invite_code'], 'string', 'max' => 100],
            [['salt'], 'string', 'max' => 20],
            [['mem_address'], 'string', 'max' => 255],
            [['mem_mobile'], 'unique'],
            [['mem_email'], 'email'],
            ['is_effective','safe'],
            // 属性过滤htmlspecialchars
            [['mem_name', 'mem_mobile', 'password', 'salt', 'mem_address', 'mem_true_name', 'mem_identify', 'mem_email', 'invite_code', 'reg_channel', 'source'], 'filter', 'filter' => 'htmlspecialchars'],
            //属性过滤intval
            [['mem_provice', 'mem_city', 'status_code', 'created', 'updated', 'invite_user_id', 'api_user_id', 'registerd', 'last_course_time', 'last_classroom_time'], 'filter', 'filter' => 'intval'],
        ];
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    /**
     * 根据手机号查询会员
     * @param $mobile
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getByMobile($mobile)
    {
        $res = self::find()->where(['mem_mobile' => $mobile])->one();
        return $res;
    }
    /**
     * 根据手机号查询会员
     * @param $mobile
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsername($mobile,$is_array = true)
    {
        $user = self::find()->where(['mem_mobile' => $mobile]);
        if($is_array){
            $user = $user->asArray()->one();
            if ($user) {
                return new static($user);
            }
        }else{
            $user = $user->one();
            if($user){
                return $user;
            }
        }
        return null;
    }
    /**
     * Validates password
     *
     * @param  string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->e_password($password,$this->salt)===$this->password;
    }
    /**
     * 重置密码
     * @param $mobile
     * @param $password
     * @return bool
     */
    public function resetPwd($mobile,$password){
        $member = self::findByUsername($mobile,false);
        if(!$member->salt){
            $member->salt = rand(100000,999999).'';
        }
        $member->password = self::e_password($password,$member->salt);
        return $member->save();
    }
    /**
     * 返回加密后的密码
     * @param $str
     * @param $salt
     * @return mixed
     */
    public static function e_password($str, $salt)
    {
        return md5(md5($str) . $salt);
    }
}
