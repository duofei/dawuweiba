<?php echo CHtml::form(url('shop/newComment',array('shopid'=>$model->shop_id)),'post',array('name'=>'postNewComment'));?>

<div><?php echo CHtml::activeTextArea($model, 'content', array('class'=>'feedback-content', 'tabIndex'=>1));?></div>
<div class="ma-t10px">
验证码：
<?php echo CHtml::activetextField($model ,'validateCode',  array('class'=>'validate-code fnum fb txt f16px', 'maxlength'=>4, 'tabIndex'=>2))?>
<?php $this->widget('CCaptcha',array(
	'captchaAction' => 'captcha',
	'showRefreshButton' => true,
	'buttonLabel' => '换一个',
	'clickableImage' => true,
	'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
));?>
</div>
<div class="ma-t10px"><?php echo CHtml::activeHiddenField($model, 'shop_id') ?><?php echo CHtml::submitButton('发 表', array('class'=>'btn-two', 'tabIndex'=>3));?></div>

<?php echo CHtml::endForm();?>
<?php echo $errorsummary; ?>