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
	<div class="c-right-content">
		<div class="miaosha-info pa-t10px">
			<div class="postbox">
				<div class="top">发表一下成功感言吧，很多网友很期待噢!</div>
				<div class="content">
					<?php echo CHtml::beginForm(url('miaosha2/postfeedback'), 'post', array('id'=>'postFormId'));?>
					<div class="ma-l10px f14px">成功感言
					<?php echo CHtml::activeTextArea($model, 'content', array('style' => 'width:430px; height:150px;', 'tabIndex'=>1));?>
					</div>
					<div class="ma-l10px ma-t10px f14px">验证码：
						<?php echo CHtml::activetextField($model ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
						<?php $this->widget('CCaptcha',array(
							'captchaAction' => 'captcha',
							'showRefreshButton' => true,
							'buttonLabel' => '换一个',
							'clickableImage' => true,
							'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
						));?>
					</div>
					<?php echo CHtml::endForm();?>
					<div class="ma-t10px ac"><?php echo CHtml::image(resBu('miaosha2/images/fbgy.gif'), '', array('id'=>'submit', 'class'=>'cursor'));?></div>
					<?php if($errormodel):?>
					<div class="ma-l10px ma-t10px"><?php echo CHtml::errorSummary($errormodel);?></div>
					<?php endif;?>
				</div>
				<div class="bottom"></div>
			</div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function(){
	$('#submit').click(function(){
		$('#postFormId').submit();
	});
});
</script>