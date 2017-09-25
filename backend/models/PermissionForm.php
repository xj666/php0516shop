<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 14:15
 */
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    const SCENARIO_ADD ='add';
//,'on'=>self::SCENARIO_ADD
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
    public function validateName(){
        //只管问题
        if(\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'描述'
        ];
    }

}