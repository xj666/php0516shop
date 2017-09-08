<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;
class BrandController extends \yii\web\Controller
{
    public function actionIndex(){
        $query = Brand::find();
        $pager = new Pagination([
            'totalCount'=>$query->where(['>','status',-1])->count(),
            'defaultPageSize'=>2
        ]);
       // $models = $query->limit($pager->limit)->offset($pager->offset)->all();
       // $models = $query->limit($pager->limit)->offset($pager->offset)->orderBy($sort->orders)->where(['>','status',-1])->all();
        $models = $query->where(['>','status',-1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['Brands'=>$models,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Brand();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            //处理上传文件 实例化上传文件对象
            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate()){
                //移动文件 move_uploaded_file()
                $file='/upload/'.uniqid().'.'.$model->file->getExtension();//文件名（含路径）
                //保存文件 指定路径
                $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                $model->logo=$file;//上传文件的地址
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        //根据id查询数据
        $model=Brand::findOne(['id'=>$id]);
        $request=\Yii::$app->request;
        //判断是否为post
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //处理上传文件 实例化上传文件对象
            $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate()){
                if ($model->file){
                    //移动文件 move_uploaded_file()
                    $file='/upload/'.uniqid().'.'.$model->file->getExtension();//文件名（含路径）
                    //保存文件 指定路径
                    $model->file->saveAs(\Yii::getAlias('@webroot').$file,false);
                    $model->logo=$file;//上传文件的地址
                }
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id)
    {
        $model = Brand::findOne($id);
        $model->status = -1;
        $model->save(false);
        if ($model->status == -1) {
            \Yii::$app->session->setFlash('success', '删除成功');
        }else{
            \Yii::$app->session->setFlash('success', '删除失败');
        }
        return $this->redirect(['brand/index']);
    }


}
