<?php echo CHtml::beginForm('', 'post');?>
<div class="login login-left fl ma-t20px ma-b20px f14px">
	<h3 class="f20px ma-b20px">欢迎光临我爱外卖，请登录</h3>
	<div class="onetr">
		<div class="fl label"><label>人人网用户登录</label></div>
		<div class="fl value"><?php echo CHtml::image(resBu('images/renren.png'), 'Renren Connect', array('onclick'=>'onRenRenLogin();', 'id'=>'xn_login_image', 'class'=>'xnconnect_login_button'));?></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>新浪微博用户登录</label></div>
		<div class="fl value"><?php echo l(CHtml::image(resBu('images/sinat.png'), 'SinaT Connect'), $sinaUlr);?></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>用户名</label></div>
		<div class="fl value"><?php echo CHtml::activeTextField($loginModel, 'username', array('class'=>'txt f16px', 'tabindex'=>1));?></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>密　码</label></div>
		<div class="fl value"><?php echo CHtml::activePasswordField($loginModel, 'password', array('class'=>'txt', 'tabindex'=>2));?>&nbsp;<?php echo l('忘记密码？', url('site/forgetPassword'))?></div>
		<div class="clear"></div>
	</div>
	<?php if (LoginForm::getEnableCaptcha()):?>
	<div class="onetr">
		<div class="fl label"><label>验证码</label></div>
		<div class="fl value">
	    	<?php echo CHtml::activeTextField($loginModel, 'validateCode', array('class'=>'validate-code fnum fb txt f16px', 'tabindex'=>3, 'maxlength'=>4))?>
			<?php $this->widget('CCaptcha',array(
			    'captchaAction' => 'captcha',
			    'showRefreshButton' => true,
			    'buttonLabel' => '换一个',
        		'clickableImage' => true,
			    'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
            ));?>
		</div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="onetr">
		<div class="fl label">&nbsp;</div>
		<div class="fl value">
		    <?php echo CHtml::submitButton('登 录', array('class'=>'btn-four fb cred f14px', 'tabindex'=>10));?>
		    <?php echo CHtml::activeCheckBox($loginModel, 'rememberMe');?><label for="LoginForm_rememberMe" class="cgray">下次自动登陆 </label>
		</div>
		<div class="clear"></div>
	</div>
    <?php echo CHtml::errorSummary($loginModel);?>
</div>
<?php echo CHtml::endForm();?>
<div class="login login-right fr ac ma-t20px">
	<h3 class="f16px">还不是会员？马上&nbsp;<a href="<?php echo url('site/signup');?>">注册</a></h3>
</div>
