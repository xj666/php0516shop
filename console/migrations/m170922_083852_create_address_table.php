<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170922_083852_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'province'=>$this->string(),
            'city'=>$this->string(),
            'area'=>$this->string(),
            'area_tail'=>$this->string(),
            'tel'=>$this->string(),
            'user_id'=>$this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
