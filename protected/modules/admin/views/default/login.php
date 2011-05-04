<div id="admin-login">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="info">
			<h1>我爱外卖网 管理中心</h1>
			<p>我爱外卖是一个针对全国一二线城市功能型的同城服务，外卖交易平台，提供订餐，订蛋糕，鲜花等便民服务。</p>
		</td>
		<td class="theform cgray">
		<?php echo CHtml::form(url('admin/default/login'), 'post')?>
			<p>
				<span class="fb f14px"><?php echo CHtml::activeLabel($loginModel, 'username');?>:</span>
				<span><?php echo CHtml::activeTextField($loginModel, 'username', array('tabindex'=>1, 'class'=>'txt'))?></span>
				</p>
			<p>
				<span class="fb f14px"><?php echo CHtml::activeLabel($loginModel, 'password');?>:</span>
				<span><?php echo CHtml::activePasswordField($loginModel, 'password', array('tabindex'=>2, 'class'=>'txt'))?></span>
			</p>
			<?php if (LoginForm::getEnableCaptcha()):?>
			<p>
				<span class="fb f14px"><?php echo CHtml::activeLabel($loginModel, 'validateCode');?>:</span>
				<span>
					<?php echo CHtml::activeTextField($loginModel, 'validateCode', array('class'=>'validate fnum fb txt f16px', 'tabindex'=>3, 'maxlength'=>4))?>
        			<?php $this->widget('CCaptcha',array(
        			    'captchaAction' => 'captcha',
                    	'showRefreshButton' => false,
                		'clickableImage' => true,
        			    'imageOptions' => array('align'=>'top', 'title'=>'点击图片重新获取验证码'),
                    ));?>
				</span>
			</p>
			<?php endif;?>
			<p><input type="submit" value="登陆" class="btn btn-submit" tabindex="4" /></p>
		<?php echo CHtml::endForm();?>
		</td>
	</tr>
</table>
<p class="copyright ac color-gray"><?php echo CdcBetaTools::getPowered();?></p>
</div>