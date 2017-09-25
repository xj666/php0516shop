<?php
?>
<a class="btn btn-info" href="<?=\yii\helpers\Url::to(['brand/add'])?>">添加品牌</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($Brands as $Brand):?>
        <tr  data-id="<?=$Brand->id?>">
            <td><?=$Brand->id?></td>
            <td><?=$Brand->name?></td>
            <td><?=$Brand->intro?></td>
            <td><?=\yii\bootstrap\Html::img($Brand->logo,['height'=>30])?></td>
            <td><?=$Brand->sort?></td>
            <td><?=$Brand->status==0?'隐藏':'正常'?>
            </td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/edit','id'=>$Brand->id])?>" class="btn btn-danger btn-group-sm">修改</a>
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
$del_url=\yii\helpers\Url::to(['brand/delete']);
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
