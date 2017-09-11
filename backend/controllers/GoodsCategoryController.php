<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=GoodsCategory::find();
        //实例化分页工具条
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            //每页多少条
            'defaultPageSize'=>2,
        ]);
        //计算后
        $models=$query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断是否是顶级分类
                if($model->parent_id){
                    //子分类
                   $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $parent_id=$model->parent_id;
//        var_dump($parent_id);exit;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            var_dump($request->post());exit;
            if($parent_id<=$model->parent_id){
                \Yii::$app->session->setFlash('success','不能选择比自己小的或者同级!');
                return $this->redirect(['goods-category/index']);
            }
            if($model->validate()){
                if($id===$model->parent_id){
                    \Yii::$app->session->setFlash('success','不能选择自己!');
                    return $this->redirect(['goods-category/index']);
                }

                //判断是否是顶级分类
                if($model->parent_id){
                    //子分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功!');
                return $this->redirect(['goods-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model = GoodsCategory::findOne(['id' => $id]);

        $child = GoodsCategory::findOne(['parent_id' => $model->id]);
        // var_dump($child);exit;
        if ($child) {
            \Yii::$app->session->setFlash('success', '有子分类不能删除!');
        } else {
//            $model = GoodsCategory::findOne(['id' => $id]);
            $model->delete();
        }
        return $this->redirect(['goods-category/index']);
    }
    public function actionZtree(){
        $goodsCategories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
//        var_dump($goodsCategories);exit;
        return $this->renderPartial('ztree',['goodsCategories'=>$goodsCategories]);
    }

}
