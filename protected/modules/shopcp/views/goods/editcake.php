<?php echo CHtml::beginForm(url('shopcp/goods/editCake'),'post',array('name'=>'add', 'enctype'=>'multipart/form-data'));?>
    <h3>商品基本信息</h3>			
<input type="hidden" name="id" value="<?php echo $goods_info->id?>">
<table  class="tabcolor list-tbl" width="100%">
  <tr>
    <td width="120">商品分类:</td>
    <td width=""><?php echo CakeGoods::$categorys[$goods_info->cakeGoods->category_id]?>&nbsp;&nbsp;<span class="color">*</span>&nbsp;&nbsp;蛋糕的每一个分类都必选</td>
  </tr>
  <tr class="show">
    <td>造型分类:</td>
    <td><?php echo CHtml::activeDropDownList($goods_info->cakeGoods, 'shape_id', CakeGoods::$shapes, array('separator'=>' '))?></td>
  </tr>
  <tr class="show">
    <td>用途分类:</td>
    <td><?php echo CHtml::checkBoxList('CakePurpose[purpose_id]', CHtml::listData($goods_info->cakeGoods->Purposes, 'id', 'id'), Purpose::getPurposeArray(), array('separator'=>' '))?></td>
  </tr>
  <tr class="show">
    <td>品种分类:</td>
    <td><?php echo CHtml::checkBoxList('CakeVariety[variety_id]', CHtml::listData($goods_info->cakeGoods->Varietys, 'id', 'id'), Variety::getVarietyArray(), array('separator'=>' '))?></td>
  </tr>
  <tr>
    <td >商品名称:</td>
    <td ><?php echo CHtml::activeTextField($goods_info, 'name', array('class'=>'txt')); ?>&nbsp;&nbsp;<span class="color">*</span></td>
  </tr>
  
  <tr class="none">
    <td>商品图片:</td>
    <td>
	    <?php if ($goods_info->pic) { echo '<div>'.$goods_info->picHtml.'</div>'; }?>
	    <?php echo CHtml::activeFileField($goods_info, 'pic');?>
	    <input type="hidden" name="Goods[picOriginal]" value="<?php echo $goods_info->pic?>">&nbsp;&nbsp;图片宽高比例必须为 135*135像素</td>
  </tr>
  
  <tr class="show">
    <td >商品全图:</td>
    <td >
	    <?php if ($goods_info->cakeGoods->big_pic) { echo '<div>'.$goods_info->cakeGoods->bigPicHtml.'</div>'; }?>
	    <?php echo CHtml::activeFileField($goods_info->cakeGoods, 'big_pic');?>
	    <input type="hidden" name="CakeGoods[big_picOriginal]" value="<?php echo $goods_info->cakeGoods->big_pic?>">&nbsp;&nbsp;图片宽高比例必须为 600*600像素</td>
  </tr>
  <tr class="show">
    <td >商品切图:</td>
    <td >
	    <?php if ($goods_info->cakeGoods->small_pic) { echo '<div>'.$goods_info->cakeGoods->smallPicHtml.'</div>'; }?>
	    <?php echo CHtml::activeFileField($goods_info->cakeGoods, 'small_pic');?>
	    <input type="hidden" name="CakeGoods[small_picOriginal]" value="<?php echo $goods_info->cakeGoods->small_pic?>">&nbsp;&nbsp;图片宽高比例必须为 338*338像素</td>
  </tr>
  <tr>
    <td>小语：</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'label', array('class'=>'txt')); ?></td>
  </tr>
  <tr>
    <td>口味：</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'taste', array('class'=>'txt')); ?></td>
  </tr>
  <tr>
    <td>甜度：</td>
    <td><?php echo CHtml::activeRadioButtonList($goods_info->cakeGoods, 'saccharinity', CakeGoods::$saccharinitys, array('separator'=>' ')); ?></td>
  </tr>
  <tr>
    <td>是否无糖：</td>
    <td><?php echo CHtml::activeRadioButtonList($goods_info->cakeGoods, 'is_sugar', CakeGoods::$sugars, array('separator'=>' ')); ?></td>
  </tr>
   <tr>
    <td>材料：</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'stuff', array('class'=>'txt')); ?></td>
  </tr>
   <tr>
    <td>保鲜条件：</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'fresh_condition', array('class'=>'txt')); ?></td>
  </tr>
   <tr>
    <td>购买建议：</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'buy_advice', array('class'=>'txt')); ?></td>
  </tr>
  <tr>
    <td>是否赠送贺卡：</td>
    <td><?php echo CHtml::activeRadioButtonList($goods_info->cakeGoods, 'is_card_blessing', CakeGoods::$card_blessings, array('separator'=>' ')); ?></td>
  </tr>
  <tr>
    <td>是否允许写祝福语：</td>
    <td><?php echo CHtml::activeRadioButtonList($goods_info->cakeGoods, 'is_cake_blessing', CakeGoods::$card_blessings, array('separator'=>' ')); ?></td>
  </tr>
 <tr class="none">
    <td>门市价:</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'market_price', array('class'=>'txt', 'style'=>'width:50px')); ?> 元</td>
  </tr>
  <tr class="none">
    <td>外卖价:</td>
    <td><?php echo CHtml::activeTextField($goods_info->cakeGoods, 'wm_price', array('class'=>'txt', 'style'=>'width:50px')); ?> 元</td>
  </tr>
  <tr>
    <td>自提：</td>
    <td><?php echo CHtml::activeRadioButtonList($goods_info, 'is_carry', CakeGoods::$card_blessings, array('separator'=>' ')); ?></td>
  </tr>
  </table>
  
<table  class="tabcolor list-tbl show" width="100%">
  <tr ><td colspan="5"><h3 class="inline">蛋糕价格：</h3>(请选择当前蛋糕包含的尺寸，填写相关内容)</td></tr>
  <tr >
    <td>蛋糕尺寸</td>
    <td>适合人数</td>
    <td>门市价</td>
    <td>我爱外卖价</td>
	<td>附送餐具</td>
  </tr>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 8) : $size = 8;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="8" checked="checked"/>&nbsp;8寸</td>
    <td>适合2--3人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price8]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price8]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc8]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 8) :?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="8" />&nbsp;8寸</td>
    <td>适合2--3人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price8]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price8]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc8]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 10) : $size = 10;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="10" checked="checked"/>&nbsp;10寸</td>
    <td>适合4--6人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price10]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price10]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc10]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 10) :?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="10" />&nbsp;10寸</td>
    <td>适合4--6人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price10]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price10]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc10]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 12) : $size = 12;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="12" checked="checked"/>&nbsp;12寸</td>
    <td>适合6--9人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price12]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price12]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc12]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 12) :?>
   <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="12" />&nbsp;12寸</td>
    <td>适合6--9人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price12]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price12]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc12]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 14) : $size = 14;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="14" checked="checked"/>&nbsp;14寸</td>
    <td>适合9--12人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price14]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price14]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc14]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 14) :?>
   <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="14" />&nbsp;14寸</td>
    <td>适合9--12人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price14]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price14]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc14]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 16) : $size = 16;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="16" checked="checked"/>&nbsp;16寸</td>
    <td>适合12--15人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price16]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price16]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc16]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 16) :?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="16" />&nbsp;16寸</td>
    <td>适合12--15人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price16]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price16]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc16]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 18) : $size = 18;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="18" checked="checked"/>&nbsp;18寸</td>
    <td>适合15--18人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price18]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price18]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc18]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 18) :?>
    <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="18" />&nbsp;18寸</td>
    <td>适合15--18人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price18]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price18]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc18]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
  <?php foreach ($goods_info->cakeGoods->cakePrices as $key=>$val) :
  if ($val->size == 20) : $size = 20;?>
  <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="20" checked="checked"/>&nbsp;20寸</td>
    <td>中型聚会</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price20]', $val->market_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price20]', $val->wm_price, array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc20]', $val->desc, array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;endforeach;?>
<?php if ($size != 20) :?>
    <tr>
    <td><input name="CakePrice[size][]" type="checkbox" value="20" />&nbsp;20寸</td>
    <td>中型聚会</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price20]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price20]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc20]', '', array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
<?php endif;?>
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

<script type="text/javascript">
$(function(){
	if (<?php echo $goods_info->cakeGoods->category_id?> != 1 ) {
		$("tr.none").attr("class","show1");
		$("tr.show").attr("class","none1");
		$("table.show").attr("class","none1");
	}
});
</script>