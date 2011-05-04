<style>
#add-list-table .input input {width:98%;}
</style>
<?php if (!$goodscategory): ?>
<div>您目前没有商品分类,请先<a href="<?php echo url('shopcp/goods/list', array('type'=>'2'))?>" class="fb cred">添加分类</a></div>
<?php else:?>
<?php echo CHtml::beginForm(url('shopcp/goods/addlist'),'post',array('name'=>'addlist'));?>
<table  class="tabcolor list-tbl" id="add-list-table" width="100%">
	<tr><td colspan="3"><strong>选择分类：</strong><?php echo CHtml::radioButtonList('category_id', $foodgoods->category_id ? $foodgoods->category_id : $goodscategory['0']['id'], CHtml::listData($goodscategory, 'id', 'name'), array('separator'=>' ')); ?></td></tr>
	<tr>
		<th width="120">商品名称</th>
		<th width="80">外卖价</th>
		<th>商品描述</th>
	</tr>
  	<tr class="input">
    	<td><input name="name[]" type="text" class="txt" /></td>
    	<td><input name="wm_price[]" type="text" class="txt" /></td>
    	<td><input name="desc[]" type="text" class="txt" /></td>
  	</tr>
  	<tr class="input">
    	<td><input name="name[]" type="text" class="txt" /></td>
    	<td><input name="wm_price[]" type="text" class="txt" /></td>
    	<td><input name="desc[]" type="text" class="txt" /></td>
  	</tr>
  	<tr class="input">
    	<td><input name="name[]" type="text" class="txt" /></td>
    	<td><input name="wm_price[]" type="text" class="txt" /></td>
    	<td><input name="desc[]" type="text" class="txt" /></td>
  	</tr>
  	<tr class="input">
    	<td><input name="name[]" type="text" class="txt" /></td>
    	<td><input name="wm_price[]" type="text" class="txt" /></td>
    	<td><input name="desc[]" type="text" class="txt" /></td>
  	</tr>
  	<tr class="input">
    	<td><input name="name[]" type="text" class="txt" /></td>
    	<td><input name="wm_price[]" type="text" class="txt" /></td>
    	<td><input name="desc[]" type="text" class="txt" /></td>
  	</tr>
</table>
<div class="ar"><a href="javascript:void(0);" onclick="addNewRow()">增加一行</a></div>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '批量添加',
	)
);
?>
<?php echo CHtml::endForm();?>
<?php endif;?>
<script type="text/javascript">
function addNewRow()
{
	$("#add-list-table tr").last().clone().appendTo("#add-list-table");
}
</script>