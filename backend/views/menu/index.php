<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['menu/add'])?>">添加菜单</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr  >
            <td><?=$model->id?></td>
            <td><?=$model->label?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->url?>
            </td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['menu/edit','id'=>$model->id])?>" class="btn btn-danger btn-group-sm">修改</a>
                <a href="javascript:;" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash">删除</span></a>
            </td>

        </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
$del_url=\yii\helpers\Url::to(['menu/delete']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $('.del_btn').click(function() {
      if(confirm('确定要删除吗?')){
          var tr=$(this).closest('tr');
          var id=tr.attr('data-id');
          $.post("{$del_url}",{id:id},function(data) {
            if(data=='success'){
                  tr.fadeToggle();
                  alert('删除成功');
            }else {
                alert('删除失败');
            }
          })
      }
    })
JS

));?>
