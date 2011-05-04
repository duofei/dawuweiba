<?php if ($goods_list) {?>
 <?php echo CHtml::beginForm(url('shopcp/goods/daycreate'),'post',array('name'=>'edit'));?>
 <h3>请选择周五推出的菜单</h3>
 <input type="hidden" name="week" value="5">
<ul>
<?php $i=1; foreach ($goods_list as $key=>$val) :?>
<li class="divbg1 block clr f14px fb pa-5px"><?php echo $key;?></li>
<li class="li pa-b5px"><?php echo CHtml::checkBoxList('DayList5'.$i, $day_list, CHtml::listData($val, 'id', 'name'), array('separator'=>'</li><li class="li">')); ?></li>
<input type="hidden" name="i[]" value="<?php echo $i?>">
<?php $i++; endforeach;?>
</ul>
<div class="clear"></div>
    <?php echo user()->getFlash('errorSummary'); ?>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'friday',
		'caption' => '提 交',
	)
);
?>
 <?php echo CHtml::endForm();?>
  <?php echo user()->getFlash('errorSummary'); ?>
 <?php }else{?>
 <div>您目前没有菜品，不能选择每日菜单</div>
 <?php }?>