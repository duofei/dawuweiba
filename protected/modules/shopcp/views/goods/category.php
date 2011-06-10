<?php echo CHtml::beginForm(url('shopcp/category/create'),'post',array('name'=>'add'));?>
<h3>新增商品分类:</h3>
<?php echo CHtml::textField('GoodsCategory[name]', $category->name, array('class'=>'txt')); ?>&nbsp;&nbsp;
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'add',
		'caption' => '提 交',
	)
);
?>
<?php echo CHtml::endForm();?>
<br />
 <?php echo user()->getFlash('errorSummaryC'); ?>
 
<h3>商品分类显示：</h3>
 <?php echo CHtml::beginForm(url('shopcp/category/order'));?>
<?php if ($goodscategory) : foreach ($goodscategory as $key=>$val):?>
	<input type="hidden" name="id" value="<?php echo $val->id;?>">
	<div class="fl width"><?php echo $val->name?></span>&nbsp;&nbsp;(<?php echo $val->goods_nums?>)</div>
	<div class="fl pa-b5px"><input class="txt" style="width:30px" name="orderid[<?php echo $val->id?>]" ype="text" value="<?php echo $val->orderid?>" /></div>
	<div class="fl pa-l10px"><a href="<?php echo url('shopcp/category/edit', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
	<?php if ($val->goods_nums > 0) {?>
		此分类下有商品，不可删除。
	<?php } else {?>
		<a href="<?php echo url('shopcp/category/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
	<?php }?>
	</div>
	<div class="clear"></div>
<?php endforeach;?>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit1',
		'caption' => '提 交',
	)
);
?>
<?php echo user()->getFlash('errorSummary'); ?>
<?php endif;?>
<?php echo CHtml::endForm();?>
