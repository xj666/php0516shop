<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\filters\AccessControl;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        //实例化分页工具条
        $pager=new Pagination([
            //总页数
            'totalCount'=>$query->count(),
            //每页多少条
            'defaultPageSize'=>4,
        ]);
        //查询计算页面后的数据
        $model=$query->where(['>','status','-1'])->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$model,'pager'=>$pager]);
    }
    //添加功能
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功!');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        //根据id查询数据
        $model=ArticleCategory::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete(){
        $id=\Yii::$app->request->post('id');
        $model = ArticleCategory::findOne(['id' => $id]);
        if($model) {
            $model->status = -1;
            $model->save(false);
            return 'success';
            return $this->redirect(['article-category/index']);
        }
        return 'fail';
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilters::className(),
                'except'=>['login','logout','error','captcha','editpsd'],
            ]
        ];
    }
}
