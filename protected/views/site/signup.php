<?php echo CHtml::beginForm('', 'post');?>
<div class="login login-left fl ma-t20px ma-b20px f14px">
	<h3 class="f20px ma-b20px">个人用户注册</h3>
	<?php if($invite_username):?>
	<div class="onetr">
		<div class="fl label"><label>推荐人</label></div>
		<div class="fl value">
			<?php echo CHtml::textField('invite_username', $invite_username, array('class'=>'txt f16px', 'readOnly'=>true));?>
			<?php echo CHtml::hiddenField('invite_id', $invite_id);?>
			<?php echo CHtml::hiddenField('hcode', $_GET['hcode']);?>
		</div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
	<div class="onetr">
		<div class="fl label"><label>用户名</label></div>
		<div class="fl value"><?php echo CHtml::activeTextField($loginModel, 'username', array('class'=>'txt f16px', 'tabindex'=>1));?></div>
		<div class="fl cred ma-l5px" id="msg_username"></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>密　码</label></div>
		<div class="fl value"><?php echo CHtml::activePasswordField($loginModel, 'password', array('class'=>'txt', 'tabindex'=>2));?></div>
		<div class="fl cred ma-l5px" id="msg_password"></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>确认密码</label></div>
		<div class="fl value"><?php echo CHtml::activePasswordField($loginModel, 'repassword', array('class'=>'txt', 'tabindex'=>3));?></div>
		<div class="fl cred ma-l5px" id="msg_repassword"></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>手　机</label></div>
		<div class="fl value"><?php echo CHtml::activeTextField($loginModel, 'telphone', array('class'=>'txt', 'tabindex'=>4));?></div>
		<div class="fl cred ma-l5px" id="msg_telphone"></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label"><label>电子邮箱</label></div>
		<div class="fl value"><?php echo CHtml::activeTextField($loginModel, 'email', array('class'=>'txt', 'tabindex'=>4));?></div>
		<div class="fl cred ma-l5px" id="msg_email"></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label">&nbsp;</div>
		<div class="fl value"><?php echo CHtml::activeCheckBox($loginModel, 'service', array('id'=>'agreement'));?> <label for="agreement">同意我爱外卖网的<?php echo l('服务条款和协议', url('static/service'), array('target'=>'_blank'));?></label></div>
		<div class="clear"></div>
	</div>
	<div class="onetr">
		<div class="fl label">&nbsp;</div>
		<div class="fl value">
			<?php echo CHtml::hiddenField('referer', CdcBetaTools::getReferrer());?>
		    <?php echo CHtml::submitButton('注 册', array('class'=>'btn-four fb cred f14px', 'tabindex'=>10, 'id'=>'submitbtn'));?>
		</div>
		<div class="clear"></div>
	</div>
    <?php echo CHtml::errorSummary($loginModel);?>
</div>
<?php echo CHtml::endForm();?>
<div class="login login-right fr ac ma-t20px">
	<h3 class="f16px">已是会员？马上&nbsp;<a href="<?php echo url('site/login');?>">登录</a></h3>
</div>
<script type="text/javascript">
var url = "<?php echo url('site/checksignup');?>";
$(function(){
	var s_username = false;
	var s_password = false;
	var s_repassword = false;
	var s_telphone = false;
	var s_email = false;
	var checkUsername = function(){
		var tthis = $("#LoginForm_username");
		var username = tthis.val();
		$.ajax({
			type: 'get',
			url: url,
			data: "type=username&username=" + username,
			dataType: 'html',
			cache: false,
			success: function(data){
				if(data=='-1') {
					tthis.addClass('cred');
					$("#msg_username").html('用户名已存在');
					tthis.focus();
					s_username = false;
				} else if(data=='-2') {
					tthis.addClass('cred');
					$("#msg_username").html('需要在3-15位字符之间');
					tthis.focus();
					s_username = false;
				} else {
					tthis.removeClass('cred');
					$("#msg_username").html('');
					s_username = true;
				}
			}
		});
	}
	var checkPassword = function(){
		var tthis = $("#LoginForm_password");
		var password = tthis.val();
		if(password.length < 5 || password.length > 20) {
			tthis.addClass('cred');
			$("#msg_password").html('需要在5-20位字符之间');
			s_password = false;
		} else {
			tthis.removeClass('cred');
			$("#msg_password").html('');
			s_password = true;
		}
	}
	var checkRepassword = function(){
		var tthis = $("#LoginForm_repassword");
		var repassword = tthis.val();
		if($("#LoginForm_password").val() != repassword) {
			tthis.addClass('cred');
			$("#msg_repassword").html('两次密码输入不一致');
			s_repassword = false;
		} else {
			tthis.removeClass('cred');
			$("#msg_repassword").html('');
			s_repassword = true;
		}
	}
	var checkTelphone = function(){
		var tthis = $("#LoginForm_telphone");
		var telphone = tthis.val();
		var s = telphone.match(/^1\d{10}$/);
		if(null == s) {
			tthis.addClass('cred');
			$("#msg_telphone").html('手机格式输入不正确');
			s_telphone = false;
		} else {
			tthis.removeClass('cred');
			$("#msg_telphone").html('');
			s_telphone = true;
		}
	}
	var checkEmail = function(){
		var tthis = $("#LoginForm_email");
		var email = tthis.val();
		var s = email.match(/^\w+@\w+\.[\w\.]+$/);
		if(null == s) {
			tthis.addClass('cred');
			$("#msg_email").html('邮箱格式输入不正确');
			s_email = false;
			return ;
		}
		$.ajax({
			type: 'get',
			url: url,
			data: "type=email&email=" + email,
			dataType: 'html',
			cache: false,
			success: function(data){
				if(data=='-1') {
					tthis.addClass('cred');
					$("#msg_email").html('邮箱已存在');
					s_email = false;
				} else {
					tthis.removeClass('cred');
					$("#msg_email").html('');
					s_email = true;
				}
			}
		});
	}
	$("#LoginForm_username").blur(checkUsername);
	$("#LoginForm_password").blur(checkPassword);
	$("#LoginForm_repassword").blur(checkRepassword);
	$("#LoginForm_telphone").blur(checkTelphone);
	$("#LoginForm_email").blur(checkEmail);
	$("#submitbtn").click(function(){
		var status = true;
		if(!s_username) {
			checkUsername();
			status = false;
		}
		if(!s_password) {
			checkPassword();
			status = false;
		}
		if(!s_repassword) {
			checkRepassword();
			status = false;
		}
		if(!s_telphone) {
			checkTelphone();
			status = false;
		}
		if(!s_email) {
			checkEmail();
			status = false;
		}
		return status;
	});
});
</script>