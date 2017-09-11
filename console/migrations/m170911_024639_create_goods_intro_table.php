<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170911_024639_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
/*goods_id	int	商品id
content	text	商品描述*/
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id'=>$this->integer()->comment('商品ID'),
            'content'=>$this->text()->comment('商品描述'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
