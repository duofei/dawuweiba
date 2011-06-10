<?php echo CHtml::beginForm(url('shopcp/category/edit'),'post',array('name'=>'add'));?>
<h3>编辑商品分类:</h3>
<input name="id" type="hidden" value="<?php echo $goodsCategory->id?>" />
<?php echo CHtml::activeTextField($goodsCategory, 'name', array('class'=>'txt')); ?>&nbsp;&nbsp;
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'add',
		'caption' => '提 交',
	)
);
?>
<?php echo CHtml::endForm();?>
 <?php echo user()->getFlash('errorSummary'); ?>