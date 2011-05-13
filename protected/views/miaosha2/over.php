<div class="c-left fl">
	<div class="c-left-top"></div>
	<div class="c-left-content">
		<?php $this->renderPartial('/miaosha2/left', array(
			'todayShops'=>$todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
		));?>
	</div>
	<div class="c-left-bottom"></div>
</div>
<div class="c-right fl">
	<div class="c-right-top"></div>
	<div class="c-right-conten">
		<div class="miaosha-info">
			当天秒杀已结束
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function(){
	/* 设置左右高 */
	setLeftRightHeight(980);
});
</script>