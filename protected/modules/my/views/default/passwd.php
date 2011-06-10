<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/profile");?>">基本资料</a></li>
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/email");?>">修改邮箱</a></li>
	  <li class="select corner-top cgray"><a href="<?php echo url("my/default/passwd");?>">修改密码</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
<label>旧　密码:</label>
<?php echo CHtml::passwordField('old_passwd', '', array('class'=>'txt'));?><br /><br />

<label>新　密码:</label>
<?php echo CHtml::passwordField('new_passwd', '', array('class'=>'txt'));?><br /><br />
    
<label>确认密码:</label>
<?php echo CHtml::passwordField('repeat_passwd', '', array('class'=>'txt'));?><br /><br />

<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'changer_password',
		'caption' => '修改密码',
	)
);
?>

<div id="password_error_message"></div>
</div>
<script language="JavaScript">
$(function(){
	$("#changer_password").click(function(){
		var postUrl = '<?php echo url('my/default/ajaxPasswd'); ?>';
		var state = true;
		var old_passwd = $("#old_passwd").val();
		var password_error_message = $("#password_error_message");
		var error_message = '';
		password_error_message.html('');
		if(isEmpty(old_passwd)) {
			error_message += '<br />旧密码不能为空！';
			state = false;
		}
		var new_passwd = $("#new_passwd").val();
		var repeat_passwd = $("#repeat_passwd").val();
		if(isEmpty(new_passwd)) {
			error_message += '<br />新密码不能为空！';
			state = false;
		}
		if(new_passwd != repeat_passwd) {
			error_message += '<br />两次密码输入不一致！';
			state = false;
		}
		if(state) {
			$.post(postUrl,{old:old_passwd, new_passwd:new_passwd},function(data){
				if(data) {
					password_error_message.html(data);
				} else {
					password_error_message.html('密码修改成功！');
					$("#old_passwd").val('');
					$("#new_passwd").val('');
					$("#repeat_passwd").val('');
				}
			});
		} else {
			password_error_message.html(error_message);
		}
	});
});
</script>