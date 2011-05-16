<div class="c-left fl">
	<div class="c-left-top"></div>
	<div class="c-left-content">
		<?php $this->renderPartial('/miaosha2/left', array(
			'todayShops'=>$todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
			't' => $t
		));?>
	</div>
	<div class="c-left-bottom"></div>
</div>
<div class="c-right fl">
	<div class="c-right-top"></div>
	<div class="c-right-content">
		<div class="miaosha-info">
			<div class="fl ma-t30px ma-l30px"><?php echo CHtml::image(resBu('miaosha2/images/js__r1_c1.gif'));?></div>
			<div class="fl ma-t30px ma-l30px">
				<div class="ma-l20px ma-t20px"><?php echo CHtml::image(resBu('miaosha2/images/js__r2_c4.gif'));?></div>
				<div class="ma-t10px f14px cblack ac"><?php echo l('查看成功用户', url('miaosha2/history', array('t'=>$t)));?></div>
				<div class="ma-t10px ac"><a href="<?php echo url('miaosha2/index', array('t'=>$t+86400));?>"><?php echo CHtml::image(resBu('miaosha2/images/js__r4_c5.gif'))?></a></div>
			</div>
			<div class="clear"></div>
			<div class="mline1px ma-t30px "></div>
			<div class="ma-t30px ac"><a href="<?php echo app()->homeUrl;?>"><?php echo CHtml::image(resBu('miaosha2/images/js__r7_c2.gif'));?></a></div>
			<div class="ma-t20px ac"><a href="<?php echo url('my/default/inviteurl');?>"><?php echo CHtml::image(resBu('miaosha2/images/js__r9_c2.gif'));?></a></div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">

</script>