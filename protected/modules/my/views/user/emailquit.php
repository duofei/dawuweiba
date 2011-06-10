<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/user/emailquit");?>">邮件订阅</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px lh40px f14px">
<?php echo CHtml::beginForm('', 'post', array('id'=>'postform'));?>
<div>
	<label>来自我爱外卖网的邮件:</label>
	<input type="radio" name="state" value="1" <?php if($user->is_sendmail==STATE_ENABLED) echo 'checked';?> />接受 &nbsp;&nbsp;
	<input type="radio" name="state" value="0" <?php if($user->is_sendmail==STATE_DISABLED) echo 'checked';?>/>不接受
</div>
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'postV',
		'caption' => ' 提交 ',
	)
);
?>
<?php echo CHtml::endForm();?>