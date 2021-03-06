<?php
/* @var $this yii\web\View */
?>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>"><span class="glyphicon glyphicon-plus-sign">添加分类</span></a></li>
    </ul>
</nav>
<table class="table table-bordered table-responsive">
    <tr >
        <th>ID</th>
        <th>商品分类名称</th>
        <th>上级分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?php
                echo str_repeat('--',$model->depth).$model->name;
                ?></td>
            <td><?=$model->parent_id?></td>
            <td><?=$model->intro?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods-category/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil">编辑</span></a>
                <a href="<?=\yii\helpers\Url::to(['goods-category/del','id'=>$model->id])?>" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash">删除</span></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
?>
