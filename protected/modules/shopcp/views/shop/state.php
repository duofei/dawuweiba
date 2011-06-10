<?php echo CHtml::beginForm(url('shopcp/shop/state'),'post',array('name'=>'edit'));?>
<div class="ma-t20px f20px">
	更改营业状态：
	<?php echo CHtml::activeRadioButtonList($shop_info,'business_state',Shop::$business_states,array('separator'=>'&nbsp;'));?>&nbsp;
	<?php $this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'submit',
			'caption' => '更 改',
		)
	);?>
</div>
<?php echo CHtml::endForm();?>
 <?php echo user()->getFlash('errorSummary'); ?>