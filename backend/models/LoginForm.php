<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $img_code;
    public $times = 0;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'message' => '请输入正确的手机号或密码'],
            ['username', 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => '请输入正确的手机号或密码'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['username', 'validateMobile'],
            ['img_code', 'validateVerify'],
            ['password', 'validateMemberPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户登录名',
            'password' => '密码',
            'rememberMe' => '自动登录',
            'img_code' => '图形验证码',
        ];
    }

    /**
     * Validates the img_code.
     * @param $attribute
     * @param $params
     */
    public function validateVerify($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $verify = new Verify();
            $result = $verify->check($this->img_code);
            if (!$result && $this->times > 2) {
                $this->addError($attribute, '图形验证码错误');
            }
        }
    }

    public function validateMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $verify = new Members();
            $result = $verify->getByMobile($this->username);
            if (!$result) {
                $this->addError($attribute, '您输入的手机号还未注册，暂无法登录');
            }
        }
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMemberPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getMember();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '您输入的密码有误，请重新输入');
            }
        }
    }

    public function getMember()
    {
        if ($this->_user === false) {
            $this->_user = Members::findByUsername($this->username);
        }
        return $this->_user;
    }

    public function userMobileAndPasswordLogin($mobile = "", $password = "", $pushId = "", $plat = "")
    {
        $result = ['state' => 0, 'message' => '未知错误', 'data' => ['message' => '未知错误']];
        $LoginForm = new LoginForm();
        $LoginForm->username = $mobile;
        $LoginForm->password = $password;
        $LoginForm->validate();
        if ($LoginForm->hasErrors()) {
            $wrong = $LoginForm->getErrors();
            if (!empty($wrong['username'])) {
                $result['message'] = $wrong['username'][0];
            } elseif (!empty($wrong['password'])) {
                $result['message'] = $wrong['password'][0];
            } else {
                $result['message'] = "登录失败，请确认登录信息";
            }
        } else {
            $result['state'] = 1;
            $result['message'] = "登录成功";

            //获取登录信息
            $data = Members::find()
                ->select(["member.mem_id", "member.mem_name", "member.mem_mobile", "member.access_token", "member.is_guide"])
                ->from(['member' => Members::tableName()])
                ->andWhere(['member.mem_mobile' => $mobile])
                ->asArray()->one();
            //极光推送绑定
            if ($pushId != '') {
                //删除此用户原来绑定的pushid
                PushBinding::deleteAll([
                    'mem_id'=>$data['mem_id'],
                    'plat'=>1
                ]);
                $res = PushBinding::find()->where(['pushid' => $pushId, 'plat' => $plat])->one();
                if (empty($res)) {
                    $res1 = PushBinding::find()->where(['mem_id' => $data['mem_id'], 'plat' => $plat])->one();
                    if (empty($res1)) {
                        $model = new PushBinding();
                        $model->pushid = $pushId;
                        $model->mem_id = $data['mem_id'];
                        $model->plat = $plat;
                        $model->inputtime = $model->updatetime = date('Y-m-d H:i:s');
                        $model->save();
                    } else {
                        $model = PushBinding::findOne($res1->id);
                        $model->pushid = $pushId;
                        $model->updatetime = date('Y-m-d H:i:s');
                        $model->save();
                    }

                } else {
                    $model = PushBinding::findOne($res->id);
                    $model->mem_id = $data['mem_id'];
                    $model->updatetime = date('Y-m-d H:i:s');
                    $model->save();
                }
            }
            //更新token
            $token = md5(time() . $data['salt']);
            $model = Members::findOne($data['mem_id']);
            $model->access_token = $token;
            $model->lastlogintime = time();
            $model->lastloginip = Yii::$app->request->getUserIP();
            $model->save();
            $data['access_token'] = $token;
            $price = Yii::$app->redis->get('mem_account'.$data['mem_id']);
            if(empty($price))
                $price = 0;
            $data['money'] = $price;
            $result['data'] = $data;
        }
        return $result;
    }

}
