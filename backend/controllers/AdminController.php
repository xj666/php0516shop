<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Admin;
use backend\models\AdminForm;
use Codeception\Template\Acceptance;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

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
        $model = new Admin(['scenario'=>Admin::SCENARIO_ADD]);
        //$model->scenario = User::SCENARIO_ADD;//指定当前场景为SCENARIO_ADD场景
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
           $auth = \Yii::$app->authManager;
            if($model->rolesName != null){
                foreach ($model->rolesName as $role_name){
                    $role_name = $auth->getRole($role_name);
                    $auth->assign($role_name,$model->getId());
                }
            }

        }

        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id)
    {
        $model = Admin::findOne(['id' => $id]);
        if ($model == null) {
            throw new NotFoundHttpException('用户不存在');
        }
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //修改密码
                //确认旧密码是否一致
                if (\Yii::$app->security->validatePassword($model->password, $model->password_hash)) {//密码验证正确
                    //验证确认密码
                    if ($model->newpassword == $model->repassword) {//两次密码一致
                        $model->save();
                    } else {
                        //    $model->addError('repassword','两次密码不一致');
                        throw new NotFoundHttpException('两次密码不一致');
                    }
                } else {//密码验证不正确
                    throw new NotFoundHttpException('密码不正确');
                }

                \Yii::$app->session->setFlash('success', '修改成功');
                $this->redirect(['admin/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        //只能修改自己的信息
        $id = \Yii::$app->user->id;
        if ($model->id != $id) {
            throw new NotFoundHttpException('只有管理员才能修改');
        }
        $model->password_hash = \Yii::$app->security->passwordHashStrategy;
        return $this->render('edit', ['model' => $model]);
    }

    public function actionDelete(){
        $id=\Yii::$app->request->post('id');
        $admin=Admin::findOne(['id'=>$id]);
        if($admin) {
            $admin->delete();
            return 'success';
            return $this->redirect(['admin/index']);
        }
        return 'fail';
    }

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
    public function actionLogout(){
       \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);

    }

   /* public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilters::className(),
                'except'=>['login','logout','error','captcha','editpsd'],
            ]
        ];
    }*/
   }



