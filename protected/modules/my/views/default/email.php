<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/profile");?>">基本资料</a></li>
	  <li class="select corner-top cgray"><a href="<?php echo url("my/default/email");?>">修改邮箱</a></li>
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/passwd");?>">修改密码</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
<label>你的密码:</label>
<?php echo CHtml::passwordField('password', '', array('class'=>'txt'));?><br /><br />
<label>电子邮箱:</label>
<?php echo CHtml::textField('edit_email', '', array('class'=>'txt'));?><br /><br />
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'changer_email',
		'caption' => '修改邮箱',
	)
);
?>
<div id="email_error_message"></div>
</div>
<script language="JavaScript">
$(function(){
	$("#changer_email").click(function(){
		var postUrl = '<?php echo url('my/default/ajaxEmail'); ?>';
		var state = true;
		var password = $("#password").val();
		var email_error_message = $("#email_error_message");
		var error_message = '';
		email_error_message.html('');
		if(isEmpty(password)) {
			error_message += '<br />密码不能为空！';
			state = false;
		}
		var email = $("#edit_email").val();
		if(isEmpty(email)) {
			error_message += '<br />邮箱不能为空！';
			state = false;
		}
		
		if(state) {
			$.post(postUrl,{password:password, email:email},function(data){
				if(data) {
					email_error_message.html(data);
				} else {
					email_error_message.html('邮箱修改成功！');
					$("#show_email").html(email);
					$("#password").val('');
				}
			});
		} else {
			email_error_message.html(error_message);
		}
	});
});
</script>