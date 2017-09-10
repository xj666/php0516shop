<?php
namespace frontend\controllers;

use frontend\models\Author;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class AuthorController extends Controller{
    public function actionIndex(){
        //获取所有作者数据
        $query=Author::find();
        //每页多少条,总条数
        //实例化分页工具条
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2,
        ]);
        $authors=$query->limit($pager->limit)->offset($pager->offset)->all();
        //显示页面
        return $this->render('index',['authors'=>$authors,'pager'=>$pager]);
    }

    //添加作者
    public function actionAdd(){
        $request=new Request();
        $model=new Author();
        if($request->isPost){
            // 接受表单提交的数据
            $model->load($request->post());
            //处理上传文件,实例化上传文件对象
            $model->file=UploadedFile::getInstance($model,'file');
            //验证规则
            if($model->validate()){
                //移动文件
                $file='/upload/author/'.uniqid().'.'.$model->file->getExtension();//文件名
                //保存文件
                $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                $model->head=$file;
                //成功保存
                $model->save(false);
                \Yii::$app->session->setFlash('success','用户添加成功!');
                return $this->redirect(['author/index']);
            }else{
                //验证失败
                var_dump($model->getErrors());
                exit;
            }
        }
        //1 显示添加页面(表单)
        return $this->render('add',['model'=>$model]);
    }
    //修改作者
    public function actionEdit($id){
        $request=new Request();
        $model=Author::findOne(['id'=>$id]);
        if($request->isPost){
            // 接受表单提交的数据
            $model->load($request->post());
            //处理上传文件,实例化上传文件对象
            $model->file=UploadedFile::getInstance($model,'file');
            //验证规则
            if($model->validate()){
                //移动文件
                $file='/upload/author/'.uniqid().'.'.$model->file->getExtension();//文件名
                //保存文件
                $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                $model->head=$file;
                //成功保存
                $model->save(false);
                \Yii::$app->session->setFlash('success','用户修改成功!');
                return $this->redirect(['author/index']);
            }else{
                //验证失败
                var_dump($model->getErrors());
                exit;
            }
        }
        //1 显示添加页面(表单)
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id){
        $model=Author::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect(['author/index']);
    }
}