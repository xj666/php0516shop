<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminForm;
use yii\data\Pagination;
class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=Admin::find();
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            //每页多少条
            'defaultPageSize'=>5,
        ]);
        //计算后
        $models=$query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model = new Admin();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);

            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model =Admin::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save();
                return $this->redirect(['admin/index']);

            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelte($id){
        $model = Admin::findOne($id);
        $model->delete();
        return $this->redirect(['admin/index']);

    }
        //1.显示登录表单(使用表单模型,不要用活动记录)
        //2 表单提交
        //3 验证用户名和密码
        //3.1 根据用户名查找用户
    //$member = User::findOne(['username'=>'zhangsan']);
        //3.2 对比密码
    //$user = Yii::$app->user;
        //4 保存登录标识到session
    //$user->login($member);
    //echo '登录成功';
  public function actionLogin()
    {
        //显示登录表单
       $model = new AdminForm();
        $request = \Yii::$app->request;
        //var_dump($ip);exit();
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //认证
                if ($model->login()) {
                    \Yii::$app->session->setFlash('success', '登录成功');
                    return $this->redirect(['admin/index']);
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }
}
