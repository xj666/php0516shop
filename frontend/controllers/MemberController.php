<?php

namespace frontend\controllers;
use Codeception\Module\Redis;
use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\SmsDemo;

class MemberController extends \yii\web\Controller
{
    public function actionRegister(){
        $model=new Member();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
//            var_dump($model->password);die;
            if($model->validate()){
                $model->save();
                $this->redirect(['member/login']);
            } else{
                var_dump($model->getErrors());
                exit;
            }
        }
        return $this->renderPartial('register');
    }
    //验证用户名是否存在
    public function actionValidateMember($username){
        $member=Member::findOne(['username'=>$username]);
        if($member){
            return 'false';
        } else{
            return 'true';
        }
    }
    public function actionLogin(){
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
           // var_dump($model);exit;
            if($model->validate()){
                //认证
                if($model->Login()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['index/index']);
                }
            }
        }
        return $this->renderPartial('login');
    }
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);

    }
    public function actionSms(){
        //$this->enableCsrfValidation = false;
        //frontend\models\SmsDemo==>        @frontend\models\SmsDemo.php
        //Aliyun\Core\Config  ==> @Aliyun\Core\Config.php
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $phone = \Yii::$app->request->post('phone');
        $code = rand(1000,9999);
       $redis->set('code_'.$phone,$code);
        echo $code;
    }
   /* //测试
        public function actionSms(){

            $demo = new SmsDemo(
                "LTAIcobYFF5M4U6F",
                "0zSNuwtF7nFU0MtBzMVNRCz9ZZ5Bmf"
            );
            echo "SmsDemo::sendSms\n";
            $response = $demo->sendSms(
                "谢氏茶馆", // 短信签名
                "SMS_97805015", // 短信模板编号
                "15183192749", // 短信接收者
                Array(  // 短信模板中字段的值
                    "code"=>rand(1000,9999),
                )
            );
            print_r($response);
        }*/

  /*  public function actionRedis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('name','张三');
        echo 'OK';
    }*/
    public function actionValidateSms($phone,$sms){
        //$code = \Yii::$app->session->get('code_'.$phone);
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $code = $redis->get('code_'.$phone);
        if($code==null || $code != $sms){
            return 'false';
        }
        //
        return 'true';
    }
}
