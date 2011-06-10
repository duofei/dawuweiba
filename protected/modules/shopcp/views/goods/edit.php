<h3>商品基本信息</h3>
<?php if (!$goodscategory): ?>
<div>您目前没有商品分类,请先<a href="<?php echo url('shopcp/goods/list', array('type'=>'2'))?>">添加分类</a></div>
<?php else:?>
<?php echo CHtml::beginForm(url('shopcp/goods/edit'),'post',array('name'=>'add', 'enctype'=>'multipart/form-data'));?>
<div class="float ma-l10px">
<input type="hidden" name="id" value="<?php echo $goods_info->id?>">
<table  class="tabcolor list-tbl" width="100%">
  <tr>
    <td width="100">商品名称:</td>
    <td><?php echo CHtml::activeTextField($goods_info, 'name',  array('class'=>'txt'));?>&nbsp;<span class="color">*</span></td>
  </tr>
  <tr>
    <td>商品分类:</td>
    <td><?php echo CHtml::activeRadioButtonList($goods_info->foodGoods, 'category_id', CHtml::listData($goodscategory, 'id', 'name'), array('separator'=>' '));?></td>
  </tr>
  <tr>
    <td>商品图片:</td>
    <td>
    <?php if ($goods_info->pic) { echo '<div>'.$goods_info->picHtml.'</div>'; }?>
    <?php echo CHtml::activeFileField($goods_info, 'pic');?>
    <input type="hidden" name="Goods[picOriginal]" value="<?php echo $goods_info->pic?>">&nbsp;&nbsp;图片宽高比例必须为 180*135像素
    </td>
  </tr>
  <tr>
    <td >辣不辣:</td>
    <td ><?php echo CHtml::activeRadioButtonList($goods_info->foodGoods, 'is_spicy', FoodGoods::$spicys, array('separator'=>' ')); ?></td>
  </tr>
  <tr>
    <td>购买价格:</td>
    <td><?php echo CHtml::activeTextField($goods_info->foodGoods, 'wm_price',  array('class'=>'txt', 'style'=>'width:50px'));?> 元</td>
  </tr>
  <?php if ($_SESSION['shop']->is_group):?>
  <tr>
    <td>团购价格:</td>
    <td><?php echo CHtml::activeTextField($goods_info->foodGoods, 'group_price', array('class'=>'txt', 'style'=>'width:50px')); ?> 元</td>
  </tr>
  <?php endif;?>
  <tr>
    <td>商品描述:</td>
    <td><?php echo CHtml::activeTextArea($goods_info->foodGoods, 'desc', array('cols'=>'45', 'rows'=>'3'))?></td>
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
</div>
<?php echo CHtml::endForm();?>
<?php endif;?>