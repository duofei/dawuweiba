<?php if($success):?>
<div class="ma-t10px order-complete"> 
	<div class="bg-pic fl"></div>
	<div class="fl ma-l20px w660px">
     	<h4 class="f16px  bline lh30px">重设密码邮件发送成功</h4>
   		<h4 class="f14px lh20px ma-t10px color999">
   		请到<span class="cred"><?php echo $email;?></span>查阅来自我爱外卖网的邮件，从邮件重设您的密码。<br />
		感谢您对我爱外卖网的支持。
   		</h4>
	</div>
	<div class="clear"></div>
</div>
<?php else:?>
<div class="ma-t20px ma-l20px"> 
	<?php echo CHtml::beginForm('', 'post');?>
    <h3 class="f20px lh30px">找回密码</h3>
    <p class="color999">只需要一步，输入您的用户名和邮箱地址就可以马上找回您的密码。</p><br />
    <h3 class="inline">　用户名(必填)</h3>
    <?php echo CHtml::textField('username', $username, array('class'=>'ma-l20px txt'));?>
    <span class="ma-l10px color999"></span>
    <br /><br />
    <h3 class="inline">邮箱地址(必填)</h3>
    <?php echo CHtml::textField('email', $email, array('class'=>'ma-l20px txt'));?>
    <span class="ma-l10px color999">您注册时使用的邮箱地址。</span>
    <br /><br />
    <?php echo CHtml::submitButton('找回密码', array('class'=>'btn-four cred ma-l30px'));?>
    <?php echo CHtml::endForm();?>
    <div class="f14px lh30px"><?php echo $error;?></div>
</div>
<?php endif;?>