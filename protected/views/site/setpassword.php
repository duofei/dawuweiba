<?php if($success):?>
<div class="ma-t10px order-complete"> 
	<div class="bg-pic fl"></div>
	<div class="fl ma-l20px w660px">
     	<h4 class="f16px  bline lh30px">密码重设成功</h4>
   		<h4 class="f14px lh20px ma-t10px color999">
   		我现在就去<?php echo l('登陆', url('site/login'));?>我爱外卖网<br />
		感谢您对我爱外卖网的支持。
   		</h4>
	</div>
	<div class="clear"></div>
</div>
<?php else:?>
<div class="ma-t20px ma-l20px"> 
	<?php echo CHtml::beginForm('', 'post');?>
    <h3 class="f20px lh30px">重设密码</h3>
    <p class="color999">请重新设置您的密码， 请牢记您的密码！</p><br />
    <?php if ($error_fail):?>
    	<h3><?php echo $error_fail;?></h3>
    <?php else:?>
	    <h3 class="inline">　新密码(必填)</h3>
	    <?php echo CHtml::passwordField('password', '', array('class'=>'ma-l20px txt'));?>
	    <span class="ma-l10px color999"></span>
	    <br /><br />
	    <h3 class="inline">确认密码(必填)</h3>
	    <?php echo CHtml::passwordField('re-password', '', array('class'=>'ma-l20px txt'));?>
	    <span class="ma-l10px color999">您注册时使用的邮箱地址。</span>
	    <br /><br />
	    <?php echo CHtml::hiddenField('validate', $validate);?>
	    <?php echo CHtml::submitButton('重设密码', array('class'=>'btn-four cred ma-l30px'));?>
	    <?php echo CHtml::endForm();?>
	    <div class="f14px lh30px"><?php echo $error;?></div>
    <?php endif;?>
</div>
<?php endif;?>