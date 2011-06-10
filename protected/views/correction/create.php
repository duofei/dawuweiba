<h2 class="pa-l10px cred f16px bline lh30px">我要纠错</h2>

<?php echo CHtml::beginForm(url('correction/create'), 'post');?>
<div class="m10px f14px">
	<div>如果发现餐厅或网站的错误，请您告诉我们，谢谢！</div>
	<?php if($success):?>
	<h2 class="ma-t20px ma-b20px lh30px f20px"><?php echo $success;?></h2>
	<?php else:?>
	<div class="ma-t10px">
		<?php echo CHtml::activeTextArea($correction, 'content', array('class' => 'feedback-content'));?>
		<?php echo CHtml::activeHiddenField($correction, 'source');?>	
	</div>
	<div class="ma-t10px">
	验证码：
	<?php echo CHtml::activetextField($correction ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'tabindex'=>3, 'maxlength'=>4))?>
	<?php $this->widget('CCaptcha',array(
		'captchaAction' => 'captcha',
		'showRefreshButton' => true,
		'buttonLabel' => '换一个',
		'clickableImage' => true,
		'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
	));?>
	</div>
	<div class="ma-t10px"><?php echo CHtml::submitButton('发 表', array('class'=>'fb cred btn-two'));?></div>
	<div class="ma-t10px">
		<?php echo $errorSummary; ?>
	</div>
	<?php endif;?>
</div>
<?php echo CHtml::endForm();?>
