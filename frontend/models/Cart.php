<?php
namespace frontend\models;

use backend\models\Goods;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{

    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
}