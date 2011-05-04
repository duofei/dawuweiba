<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/approve");?>">用户认证</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
<?php echo CHtml::beginForm('', 'post', array('id'=>'postform'));?>
<?php if($user->approve_state == User::APPROVE_STATE_UNSETTLED):?>
<label>您的手机号:</label>
<?php echo CHtml::textField('phone', $user->mobile, array('class'=>'txt', 'style'=>'width:120px;', 'id'=>'phone'));?>
&nbsp;&nbsp;
<?php echo CHtml::button(' 获取手机验证码 ', array('id'=>'btnId'))?>
&nbsp;&nbsp;
<span id="spanId" class="cred"></span>
<br /><br />
<label>手机验证码:</label>
<?php echo CHtml::textField('vcode', '', array('class'=>'txt', 'style'=>'width:60px;'));?> <span id="vcodeSpan" class="cred"><?php echo $errorcode;?></span><br /><br />
<?php
$this->widget('zii.widgets.jui.CJuiButton',
	array(
		'name' => 'postV',
		'caption' => '提交认证',
	)
);
?>
<?php echo CHtml::endForm();?>
<div class="lh24px ma-t10px">
如果您没有手机，请于我们的客服联系! <br />
客服电话55500071，客服在线时间(周一至周五 9:00-18:00)
</div>
<?php else:?>
您已通过认证请不要重复认证
<?php endif;?>
</div>
<script type="text/javascript">
var interval;
var time = 180;
$(function(){
	$("#btnId").click(function(){
		var phone = $("#phone").val();
		var re = /^1(30|31|32|33|34|35|36|37|38|39|50|51|52|53|55|56|57|58|59|86|87|88|89)\d{8}$/;
		if(!phone.match(re)) {
			$('#spanId').html('请输入正确的手机号');
			return ;
		}
		$("#btnId").attr('disabled', 'disabled');
		$("#btnId").val(' 正在发送... ');
		$.post('<?php echo url("my/default/ajaxverifycode")?>', {phone:phone}, function(data){
			if(data == '1') {
				$("#btnId").val(' 180秒后没收到，重新获取 ');
				$('#spanId').html('如果长时间没有收到，请再次获取验证码。');
				interval = setInterval(timeout, 1000);
			} else {
				$('#spanId').html('发送失败，请重先获取');
				$("#btnId").val(' 获取手机验证码 ');
				$("#btnId").attr('disabled', '');
			}
		});
	});
	$('#postform').submit(function(){
		var vcode = $('#vcode').val();
		if(vcode == '') {
			$('#vcodeSpan').html('验证码不能为空');
			return false;
		}
	});
});
function timeout() {
	time--;
	if(time > 0) {
		$("#btnId").val('  ' + time + '秒后没收到，重新获取 ');
	} else {
		$("#btnId").attr('disabled', '');
		$("#btnId").val(' 获取手机验证码 ');
		$('#spanId').html('');
		time = 180;
		clearInterval(interval);
	}
}
</script>