<?php

namespace backend\controllers;
use backend\filters\RbacFilters;
use backend\models\Goods;
use backend\models\GoodsGallery;
use flyok666\uploadifive\UploadAction;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class GoodsGalleryController extends \yii\web\Controller
{
    public function actionGallery($id){
        //判断该商品是否存在
        $goods=Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('该商品不存在');
        }
        //查询相册的表
        $gallerys=GoodsGallery::find()->all();
        return $this->render('index',['goods'=>$goods,'gallerys'=>$gallerys]);
    }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }
    //选择文件
    public function actions() {
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
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $model=new GoodsGallery();
                    $model->goods_id=\Yii::$app->request->post('goods_id');
                    $model->path = $action->getWebUrl();
                    $model->save();
                    //返回到页面的路劲
                    $action->output['fileUrl'] = $model->path;
                },
            ],

        ];
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
