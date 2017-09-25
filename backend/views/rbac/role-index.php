<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['rbac/add-role'])?>">添加角色</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['rbac/edit-role','name'=>$role->name])?>" class="btn btn-danger btn-group-sm">修改</a>
                <a href="<?=\yii\helpers\Url::to(['rbac/del-role','name'=>$role->name])?>" class="btn btn-danger btn-group-sm">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>