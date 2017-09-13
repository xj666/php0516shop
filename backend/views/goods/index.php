<?php
/* @var $this yii\web\View */
?>
<style>
    /*.sub{*/
        /*margin-bottom: 12px;*/
        /*margin-left: 20px;*/
    /*}*/
</style>
<nav aria-label="...">
    <ul class="pager">
        <li class="previous"><a href="<?=\yii\helpers\Url::to(['goods/add'])?>"><span class="glyphicon glyphicon-plus-sign">添加商品</span></li>
    </ul>
</nav>
<form id="w0" class="form-inline" action="/goods/index" method="get" role="form"><div class="form-group field-goodssearchform-name">

        <input type="text" id="goodssearchform-name" class="form-control" name="GoodsSearchForm[name]" placeholder="商品名">
    </div><div class="form-group field-goodssearchform-sn has-success">
        <input type="text" id="goodssearchform-sn" class="form-control" name="GoodsSearchForm[sn]" placeholder="货号" aria-invalid="false">
    </div><div class="form-group field-goodssearchform-minprice">
        <input type="text" id="goodssearchform-minprice" class="form-control" name="GoodsSearchForm[minPrice]" placeholder="￥">
    </div><div class="form-group field-goodssearchform-maxprice">
        <label class="sr-only" for="goodssearchform-maxprice">-</label>
        <input type="text" id="goodssearchform-maxprice" class="form-control" name="GoodsSearchForm[maxPrice]" placeholder="￥">
    </div><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>搜索</button></form>
<!-- -->
<table class="table table-bordered table-responsive active text-info table-hover ">
    <tr class="success">
        <th>ID</th>
        <th>货号</th>
        <th>名称</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods_model as $model): ?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->sn?></td>
            <td><?=$model->name?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><img src="<?=($model->logo)==''?'/upload/2.jpg':$model->logo?>" class="img-circle" width="50px"></td>
            <td class="col-md-4">
                <a href="<?=\yii\helpers\Url::to(['goods-gallery/gallery','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-book">相册</span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-wrench">编辑</span></a>
                <a href="javascript:;" class="btn btn-default del_btn" class="btn btn-default del_btn"><span class="glyphicon glyphicon-trash">删除</span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/detail','id'=>$model->id])?>" class="btn btn-default"><span class="glyphicon glyphicon-search">预览</span></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);
//注册js代码
$del_url=\yii\helpers\Url::to(['goods/del']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        $(".del_btn").click(function() {
          if(confirm('确定要删除吗')){
              var tr=$(this).closest('tr');
              var id=tr.attr('data-id');
              $.post("{$del_url}",{id:id},function(data) {
                 if(data=='success'){
                    tr.fadeToggle();
                     alert('删除成功');
                }else{
                    alert('删除失败');
                }
              })
          }
        });
JS

));
?>
