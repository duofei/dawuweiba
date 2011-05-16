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
		<div class="miaosha-info pa-t20px">
			<div><?php echo CHtml::image(resBu('miaosha2/images/guize.gif'));?></div>
			<div class="mline1px ma-t10px ma-b10px"></div>
			<div class="f14px lh30px">
			抢购成功判断标准： <br />
			抢购成功判断标准以提交订单为准，用户能否抢购成功不再是点击“秒杀”按钮的速度了，而是从点击“秒杀”按钮开始，一直到完成本订单的整个流程的速度。 <br />
 <br />
			抢购限制： <br />
			1、每位用户一天只能抢购一份；（以同台电脑同个手机号为限） <br />
			2、用户送餐地址需在活动商家的配送范围内。 <br />
			</div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>