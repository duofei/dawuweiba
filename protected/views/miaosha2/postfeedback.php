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
		<div class="miaosha-info pa-t10px">
			<div class="postbox">
				<div class="top">
					<?php if($type==2):?>
					请留下您对我爱外卖网的意见和建议
					<?php else:?>
					发表一下成功感言吧，很多网友很期待噢!
					<?php endif;?>
				</div>
				<div class="content pa-t20px pa-b20px">
					<div class="cred f16px fb ac">感谢您提出的宝贵意见，我们会及时处理!</div>
					<div class="ma-t30px ac"><a href="<?php echo url('miaosha2/index');?>"><?php echo CHtml::image(resBu('miaosha2/images/w_r2_c3.gif'));?></a></div>
				</div>
				<div class="bottom"></div>
			</div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>