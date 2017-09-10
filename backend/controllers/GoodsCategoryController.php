<?php

namespace backend\controllers;
use backend\models\Article;
use yii\data\Pagination;
class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Article::find();
        //实例化工具条
        $pager = new Pagination([
            'totalCount' => $query->where(['>', 'status', -1])->count(),
            //每页多少条
            'defaultPageSize' => 2,
        ]);

    }
}