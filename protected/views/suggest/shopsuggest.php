<h2 class="pa-l10px cred f16px bline lh30px">我要推荐商铺</h2>
<?php echo CHtml::beginForm(url('suggest/shop'), 'post');?>
<div class="m10px f14px">
	<div>您所提交的的商铺，我们会尽快推广并与商铺联系，并第一时间通知您。您的提交将是对我们最大的支持！</div>
	<?php if($success):?>
	<h2 class="ma-t20px ma-b20px lh30px f20px">您的信息已提交成功，我们会尽快与您联系！</h2>
	<?php else:?>
	<div class="ma-t10px">商铺地址：<?php echo CHtml::activeTextField($shopsuggest, 'shop_address', array('class'=>'txt'));?> <span class="cred">*</span></div>
	<div class="ma-t10px">商铺名称：<?php echo CHtml::activeTextField($shopsuggest, 'shop_name', array('class'=>'txt'));?> <span class="cred">*</span></div>
	<div class="ma-t10px">您的邮箱：<?php echo CHtml::activeTextField($shopsuggest, 'email', array('class'=>'txt'));?></div>
	<div class="ma-t10px">您的电话：<?php echo CHtml::activeTextField($shopsuggest, 'telphone', array('class'=>'txt'));?></div>
	<div class="ma-t10px">留　　言：<?php echo CHtml::activeTextArea($shopsuggest, 'comment', array('style'=>'height:70px; width:300px'));?></div>
	<div class="ma-t10px">
	验证　码：
	<?php echo CHtml::activetextField($shopsuggest ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'tabindex'=>3, 'maxlength'=>4))?>
	<?php $this->widget('CCaptcha',array(
		'captchaAction' => 'captcha',
		'showRefreshButton' => true,
		'buttonLabel' => '换一个',
		'clickableImage' => true,
		'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
	));?>
	</div>
	<div class="ma-t10px"><?php echo CHtml::submitButton('提交', array('class'=>'fb cred btn-two'));?></div>
	<div class="ma-t10px"><?php echo CHtml::errorSummary($shopsuggest); ?></div>
	<?php endif;?>
</div>
<?php echo CHtml::endForm();?>
