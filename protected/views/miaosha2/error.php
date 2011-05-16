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
			<div class="fl ma-t30px ma-l30px"><?php echo CHtml::image(resBu('miaosha2/images/w_r1_c1.gif'));?></div>
			<div class="fl ma-t30px ma-l30px">
				<div class="ma-l10px ma-t20px f18px fb lh30px">秒杀提示：</div>
				<div class="ma-l10px ma-t10px f14px cblack al" style="width:280px;"><?php echo $error;?></div>
				<div class="ma-t10px ac"><a href="<?php echo url('miaosha2/index');?>"><?php echo CHtml::image(resBu('miaosha2/images/w_r2_c3.gif'))?></a></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>