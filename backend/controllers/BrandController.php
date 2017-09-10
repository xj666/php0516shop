<?php

namespace backend\controllers;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
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
            'defaultPageSize'=>3
        ]);
       // $models = $query->limit($pager->limit)->offset($pager->offset)->all();
       // $models = $query->limit($pager->limit)->offset($pager->offset)->orderBy($sort->orders)->where(['>','status',-1])->all();
        $models = $query->where(['>','status',-1])->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['Brands'=>$models,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
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
       //     $model->file=UploadedFile::getInstance($model,'file');
            if($model->validate()){
                }
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['brand/index']);
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
    public function actions()
    {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {
                },
                'beforeSave' => function (UploadAction $action) {
                },
                'afterSave' => function (UploadAction $action) {
                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    $key = $action->getWebUrl();
                    $file = $action->getSavePath();
                    $qiniu->uploadFile($file, $key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;//输出图片的路径
                },
            ],
        ];

    }
    //七牛云测试
/*    public function actionQiniuyun(){

        $config = [
            'accessKey'=>'sUqpdz0z6nQ2g2FXuFrIXFqVjDYJpzI4UKS5h5mb',
            'secretKey'=>'ygLfQSguvn9X9MgbdyKESls5KtJCuX-x6h3nnYWO',
            'domain'=>'http://ovybhznk5.bkt.clouddn.com/',
            'bucket'=>'0516php',
            'area'=>Qiniu::AREA_HUADONG
        ];
    $qiniu = new Qiniu($config);
        $key = time();
        $qiniu->uploadFile($_FILES['tmp_name'],$key);
        $url = $qiniu->getLink($key);
    }*/

}
