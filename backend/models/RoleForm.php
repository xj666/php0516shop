<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }
    public function attributeLabels()
    {
        return[
          'name'=>'角色名称',
            'description'=>'描述',
        ];
        }

    public static function getPermissionItems(){
        $permissions = \Yii::$app->authManager->getPermissions();
        $items = [];
        foreach ($permissions as $permission){
            $items[$permission->name] = $permission->description;
        }
        //return ['user/add'=>'添加用户','user/edit'=>'修改用户'];
        return $items;
    }
}