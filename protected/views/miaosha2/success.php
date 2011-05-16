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
			<div class="fl ma-t30px ma-l30px"><?php echo CHtml::image(resBu('miaosha2/images/cg__r1_c1.png'));?></div>
			<div class="fl ma-t30px ma-l30px">
				<div class="ma-l20px ma-t20px"><?php echo CHtml::image(resBu('miaosha2/images/cg__r2_c3.png'));?></div>
				<div class="ma-t20px f18px fb pa-l20px cgray lh30px">请确保您的电话保持畅通，稍候我们<br />会给您发送确认短信。</div>
				<div class="ma-t20px ac f14px"><a href="<?php echo url('miaosha2/feedback');?>"><?php echo CHtml::image(resBu('miaosha2/images/cg__r4_c4.png'))?></a> 或 <?php echo l('邀请好友', url('my/default/inviteurl'));?></div>
			</div>
			<div class="clear"></div>
			<div class="mline1px ma-t30px "></div>
			<div class="ma-t20px ac"><a href="<?php echo url('my/default/inviteurl');?>"><?php echo CHtml::image(resBu('miaosha2/images/yaoqing.png'));?></a></div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>