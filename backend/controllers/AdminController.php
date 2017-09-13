<?php

namespace backend\controllers;

use backend\models\Admin;
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

}
