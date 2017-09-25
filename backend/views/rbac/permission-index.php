<?php
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css')
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['rbac/add-permission'])?>">添加权限</a>
<table id="table_id_example" class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($permissions as $permission):?>
    <tr>
        <td><?=$permission->name?></td>
        <td><?=$permission->description?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['rbac/edit-permission','name'=>$permission->name])?>" class="btn btn-danger btn-group-sm">修改</a>
            <a href="<?=\yii\helpers\Url::to(['rbac/del-permission','name'=>$permission->name])?>" class="btn btn-danger btn-group-sm">删除</a>
        </td>
    </tr>
   <?php endforeach;?>
    </tbody>
</table>

<?php
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $(document).ready( function () {
     $('#table_id_example').DataTable();
    } );
JS
));

