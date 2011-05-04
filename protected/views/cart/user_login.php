<?php echo CHtml::beginForm(url('site/login'), 'post', array('id'=>'postform'));?>
<p class="lh24px f14px pa-l20px">已有我爱外卖账号，登录读取地址记录</p>
<p class="pa-l20px cgray lh24px">没有我爱外卖账户， 建议您点击<span class="cred"><?php echo l('注册', url('site/signup'));?></span>。</p>
<div class="lh40px f14px pa-l20px">
	<div class="fl">用户名：</div>
	<div class="fl ma-l10px"><?php echo CHtml::activeTextField($loginModel, 'username', array('class'=>'txt f16px', 'tabindex'=>50));?></div>
	<div class="clear"></div>
</div>
<div class="lh40px f14px pa-l20px">
	<div class="fl">密　码：</div>
	<div class="fl ma-l10px"><?php echo CHtml::activePasswordField($loginModel, 'password', array('class'=>'txt', 'tabindex'=>51));?></div>
	<div class="clear"></div>
</div>
<?php if (LoginForm::getEnableCaptcha()):?>
<div class="lh40px f14px pa-l20px">
	<div class="fl">验证码：</div>
	<div class="fl ma-l10px">
    	<?php echo CHtml::activeTextField($loginModel, 'validateCode', array('class'=>'validate-code fnum fb txt f16px', 'tabindex'=>52, 'maxlength'=>4))?>
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
<div class="lh40px f14px pa-l20px">
	<?php echo CHtml::submitButton('读取历史地址', array('class'=>'input-read bg-7a cwhite'));?>
</div>
<div class="lh30px f14px pa-l20px">1. 登录用户可记录历史地址，方便以后购买！</div>
<div class="lh30px f14px pa-l20px">2. 登录用户下订单可获积分，可以换<?php echo l('礼品', url('gift/index'), array('target'=>'_blank'))?>。</div>
<?php echo CHtml::endForm();?>