<?php echo CHtml::beginForm(url('shopcp/goods/createCake'),'post',array('name'=>'add', 'enctype'=>'multipart/form-data'));?>
    <h3>商品基本信息</h3>			

<table  class="tabcolor list-tbl" width="100%">
  <tr>
    <td width="120">商品分类:</td>
    <td width=""><?php echo CHtml::dropDownList('CakeGoods[category_id]', $cakegoods->category_id, CakeGoods::$categorys, array('separator'=>' '))?>&nbsp;<span class="color">*</span>&nbsp;&nbsp;蛋糕的每一个分类都必选</td>
  </tr>
  <tr class="show">
    <td>造型分类:</td>
    <td><?php echo CHtml::dropDownList('CakeGoods[shape_id]', $cakegoods->shape_id, CakeGoods::$shapes, array('separator'=>' '))?>&nbsp;</td>
  </tr>
  <tr class="show">
    <td>用途分类:</td>
    <td><?php echo CHtml::checkBoxList('CakePurpose[purpose_id]', $purpose_id, Purpose::getPurposeArray(), array('separator'=>' '))?></td>
  </tr>  
  <tr class="show">
    <td>品种分类:</td>
    <td><?php echo CHtml::checkBoxList('CakeVariety[variety_id]', $variety_id, Variety::getVarietyArray(), array('separator'=>' '))?></td>
  </tr>
  <tr>
    <td >商品名称:</td>
    <td ><?php echo CHtml::textField('Goods[name]', $goods->name, array('class'=>'txt')); ?>&nbsp;&nbsp;<span class="color">*</span></td>
  </tr>
  
  <tr class="none">
    <td>商品图片:</td>
    <td><?php echo CHtml::fileField('Goods[pic]', $goods->pic);?>&nbsp;&nbsp;图片宽高比例必须为 135*135像素</td>
  </tr>
  
  <tr class="show">
    <td >商品全图:</td>
    <td ><?php echo CHtml::fileField('CakeGoods[big_pic]', '');?>&nbsp;&nbsp;图片宽高比例必须为 600*600像素</td>
  </tr>
  <tr class="show">
    <td >商品切图:</td>
    <td ><?php echo CHtml::fileField('CakeGoods[small_pic]', '');?>&nbsp;&nbsp;图片宽高比例必须为 338*338像素</td>
  </tr>
  <tr>
    <td>小语：</td>
    <td><?php echo CHtml::textField('CakeGoods[label]', $cakegoods->label, array('class'=>'txt')); ?></td>
  </tr>
  <tr>
    <td>口味：</td>
    <td><?php echo CHtml::textField('CakeGoods[taste]', $cakegoods->taste, array('class'=>'txt')); ?></td>
  </tr>
  <tr>
    <td>甜度：</td>
    <td><?php echo CHtml::radioButtonList('CakeGoods[saccharinity]', $cakegoods->saccharinity ? $cakegoods->saccharinity : '0', CakeGoods::$saccharinitys, array('separator'=>' ')); ?>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td>是否无糖：</td>
    <td><?php echo CHtml::radioButtonList('CakeGoods[is_sugar]', $cakegoods->is_sugar ? $cakegoods->is_sugar : '0', CakeGoods::$sugars, array('separator'=>' ')); ?></td>
  </tr>
   <tr>
    <td>材料：</td>
    <td><?php echo CHtml::textField('CakeGoods[stuff]', $cakegoods->stuff, array('class'=>'txt')); ?></td>
  </tr>
   <tr>
    <td>保鲜条件：</td>
    <td><?php echo CHtml::textField('CakeGoods[fresh_condition]', $cakegoods->fresh_condition, array('class'=>'txt')); ?></td>
  </tr>
   <tr>
    <td>购买建议：</td>
    <td><?php echo CHtml::textField('CakeGoods[buy_advice]', $cakegoods->buy_advice, array('class'=>'txt')); ?></td>
  </tr>
  <tr>
    <td>是否赠送贺卡：</td>
    <td><?php echo CHtml::radioButtonList('CakeGoods[is_card_blessing]', $cakegoods->is_card_blessing ? $cakegoods->is_card_blessing : '0', CakeGoods::$card_blessings, array('separator'=>' ')); ?></td>
  </tr>
  <tr class="show">
    <td>是否允许写祝福语：</td>
    <td><?php echo CHtml::radioButtonList('CakeGoods[is_cake_blessing]', $cakegoods->is_cake_blessing ? $cakegoods->is_cake_blessing : '0', CakeGoods::$card_blessings, array('separator'=>' ')); ?></td>
  </tr>
 <tr class="none">
    <td>门市价:</td>
    <td><?php echo CHtml::textField('CakeGoods[market_price]', $cakegoods->market_price, array('class'=>'txt', 'style'=>'width:50px')); ?> 元</td>
  </tr>
  <tr class="none">
    <td>外卖价:</td>
    <td><?php echo CHtml::textField('CakeGoods[wm_price]', $cakegoods->wm_price, array('class'=>'txt', 'style'=>'width:50px')); ?> 元</td>
  </tr>
  <tr>
    <td>自提:</td>
    <td><?php echo CHtml::radioButtonList('Goods[is_carry]', $goods->is_carry ? $goods->is_carry : '0', CakeGoods::$card_blessings, array('separator'=>' ')); ?>&nbsp;&nbsp;如选择是则此商品只允许自提</td>
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
  <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('8'=>'8寸'))?></td>
    <td>适合2--3人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price8]', $CakePrice['market_price8'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price8]', $CakePrice['wm_price8'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc8]', $CakePrice['desc8'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>

  <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('10'=>'10寸'))?></td>
    <td>适合4--6人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price10]', $CakePrice['market_price10'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price10]', $CakePrice['wm_price10'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc10]', $CakePrice['desc10'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
  
   <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('12'=>'12寸'))?></td>
    <td>适合6--9人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price12]', $CakePrice['market_price12'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price12]', $CakePrice['wm_price12'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc12]', $CakePrice['desc12'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
  
   <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('14'=>'14寸'))?></td>
    <td>适合9--12人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price14]', $CakePrice['market_price14'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price14]', $CakePrice['wm_price14'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc14]', $CakePrice['desc14'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
  
  <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('16'=>'16寸'))?></td>
    <td>适合12--15人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price16]', $CakePrice['market_price16'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price16]', $CakePrice['wm_price16'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc16]', $CakePrice['desc16'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
  
    <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('18'=>'18寸'))?></td>
    <td>适合15--18人食用</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price18]', $CakePrice['market_price18'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price18]', $CakePrice['wm_price18'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc18]', $CakePrice['desc18'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
  
    <tr>
    <td><?php echo CHtml::checkBoxList("CakePrice[size][]", $CakePrice['size'], array('20'=>'20寸'))?></td>
    <td>中型聚会</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[market_price20]', $CakePrice['market_price20'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
    <td>&nbsp;<?php echo CHtml::textField('CakePrice[wm_price20]', $CakePrice['wm_price20'], array('class'=>'txt', 'style'=>'width:40px')); ?>元</td>
	<td>附送<?php echo CHtml::textField('CakePrice[desc20]', $CakePrice['desc20'], array('class'=>'txt', 'style'=>'width:40px')); ?>套餐具</td>
  </tr>
</table>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'submit',
		'caption' => '提 交',
	)
);
?>
<?php echo CHtml::endForm();?>
<div class="ma-t5px">    <?php echo user()->getFlash('errorSummary'); ?>
    <?php echo user()->getFlash('errorSummaryF'); ?>
    <?php echo user()->getFlash('errorSummaryD'); ?></div>

<script type="text/javascript">
$(function(){
	$("#CakeGoods_category_id").change(function(){
		if ($(this).val() == '1') {
			$("tr.none1").attr("class","show");
			$("tr.show1").attr("class","none");
			$("table.none1").attr("class","show");
		} else {
			$("tr.none").attr("class","show1");
			$("tr.show").attr("class","none1");
			$("table.show").attr("class","none1");
		}
	});
});
$(function(){
	if (<?php echo $cakegoods->category_id?$cakegoods->category_id:1?> != 1 ) {
		$("tr.none").attr("class","show1");
		$("tr.show").attr("class","none1");
		$("table.show").attr("class","none1");
	}
});
</script>