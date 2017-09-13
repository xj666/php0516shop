<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 14:40
 */
namespace backend\models;

use yii\base\Model;

class AdminForm extends Model
{
    public $username;
    public $password;

    /* public $code;*/

    public function rules()
    {
        return [
            [['username', 'password'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',

        ];

    }

    public function login()
    {
        $user = Admin::findOne(['username' => $this->username]);
        if ($user) {
            if (\Yii::$app->security->validatePassword($this->password, $user->password_hash)) {
                \Yii::$app->user->login($user);
                //$ip= $_SERVER["REMOTE_ADDR"];
                $ip =\Yii::$app->request->userIP;
                $user -> last_login_ip=$ip;
                $user->save(false);
                return true;
            }
            $this->addError('password', '密码不正确');
        }
        $this->addError('username', '账户不存在');
        return false;
    }
}