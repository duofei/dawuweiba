<h2 class="pa-l10px cred f16px bline lh30px">反馈留言</h2>

<?php echo CHtml::form(url('feedback/create'), 'post');?>
<div class="m10px f14px">
	<div>
		<?php echo CHtml::activeTextArea($feedback, 'content', array('class' => 'feedback-content', 'tabIndex'=>1));?>
		<?php echo CHtml::activeHiddenField($feedback, 'post_id'); ?>
	</div>
	<div class="ma-t10px">
	验证码：
	<?php echo CHtml::activetextField($feedback ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
	<?php $this->widget('CCaptcha',array(
		'captchaAction' => 'captcha',
		'showRefreshButton' => true,
		'buttonLabel' => '换一个',
		'clickableImage' => true,
		'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
	));?>
	</div>
	<div class="ma-t10px"><?php echo CHtml::submitButton('发 表', array('class'=>'btn-two', 'tabIndex'=>3));?></div>
	<div class="ma-t10px">
		<?php echo $errorSummary; ?>
	</div>
</div>
<?php echo CHtml::endForm();?>