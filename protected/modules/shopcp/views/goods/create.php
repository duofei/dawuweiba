<h3>商品基本信息</h3>
<?php if (!$goodscategory): ?>
<div>您目前没有商品分类,请先<a href="<?php echo url('shopcp/goods/list', array('type'=>'2'))?>" class="fb cred">添加分类</a></div>
<?php else:?>
<?php echo CHtml::beginForm(url('shopcp/goods/create'),'post',array('name'=>'add', 'enctype'=>'multipart/form-data'));?>
<table  class="tabcolor list-tbl" width="100%">
  <tr>
    <td width="100">商品名称:</td>
    <td><?php echo CHtml::textField('Goods[name]', $goods->name, array('class'=>'txt')); ?>&nbsp;<span class="color">*</span></td>
  </tr>
  <tr>
    <td width="100">商品分类:</td>
    <td><?php echo CHtml::radioButtonList('FoodGoods[category_id]', $foodgoods->category_id ? $foodgoods->category_id : $goodscategory['0']['id'], CHtml::listData($goodscategory, 'id', 'name'), array('separator'=>' ')); ?></td>
  </tr>
  <tr>
    <td width="100">商品图片:</td>
    <td><?php echo CHtml::fileField('Goods[pic]', $goods->pic);?>&nbsp;&nbsp;图片宽高比例必须为 180*135像素</td>
  </tr>
  <tr>
    <td >辣不辣:</td>
    <td ><?php echo CHtml::radioButtonList('FoodGoods[is_spicy]', $foodgoods->is_spicy ? $foodgoods->is_spicy : '0', FoodGoods::$spicys, array('separator'=>' ')); ?></td>
  </tr>
  <tr>
    <td>外卖价:</td>
    <td><?php echo CHtml::textField('FoodGoods[wm_price]', $foodgoods->wm_price, array('class'=>'txt', 'style'=>'width:50px')); ?>&nbsp;元</td>
  </tr>
  <?php if ($_SESSION['shop']->is_group):?>
   <tr>
    <td>团购价:</td>
    <td><?php echo CHtml::textField('FoodGoods[group_price]', $foodgoods->group_price, array('class'=>'txt', 'style'=>'width:50px')); ?>&nbsp;元&nbsp;<span class="cred">(如果为空则与外卖价相同)</span></td>
  </tr>
  <?php endif;?>
  <tr>
    <td>商品描述:</td>
    <td><?php echo CHtml::TextArea('FoodGoods[desc]', $foodgoods->desc, array('cols'=>'60', 'rows'=>'3'))?></td>
  </tr>
</table>
    <?php echo user()->getFlash('errorSummary'); ?>
    <?php echo user()->getFlash('errorSummaryF'); ?>
    <?php echo user()->getFlash('errorSummaryD'); ?>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
<?php echo CHtml::endForm();?>
<?php endif;?>