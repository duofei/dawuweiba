<div class="right-nav">
    <ul class="nav">
	  <li class="normal corner-top cgray"><a href="<?php echo url("my/default/approve");?>">用户认证</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px lh40px">
<?php echo CHtml::beginForm('', 'post', array('id'=>'postform'));?>
<?php if($user->approve_state == User::APPROVE_STATE_UNSETTLED):?>
<div>
	<label>您的手机号:</label>
	<?php echo CHtml::textField('phone', $user->mobile, array('class'=>'txt', 'style'=>'width:120px;', 'id'=>'phone'));?>
</div>
<div>
	<label>获取验证码:</label>
	<?php echo CHtml::button(' 获取手机验证码 ', array('id'=>'btnId'))?> <br />
	　　　　　　<span id="spanId" class="cred">请准确填写您的手机号码，再点击获取验证码。</span>
</div>
<div>
	<label>手机验证码:</label>
	<?php echo CHtml::textField('vcode', '', array('class'=>'txt', 'style'=>'width:60px;'));?> <span id="vcodeSpan" class="cred"><?php echo $errorcode;?></span>
</div>
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
如果您其它问题，请于我们的客服联系! <br />
客服电话55500071，客服在线时间(周一至周五 9:00-18:00)
</div>
<?php else:?>
您已通过认证请不要重复认证
<?php endif;?>
</div>
<script type="text/javascript">
var interval;
var time = 120;
var type = 1;
var phone_re = /^1(30|31|32|33|34|35|36|37|38|39|50|51|52|53|55|56|57|58|59|86|87|88|89)\d{8}$/;
var telphone_re = /^(0[1-9][0-9]{1,2}-?)?[1-9][0-9]{6,7}$/;
$(function(){
	$('#phone').keyup(function(){
		var phone = $("#phone").val();
		if(phone.match(phone_re)) {
			type = 1;
			$('#btnId').val(' 获取手机验证码 ');
		} else if(phone.match(telphone_re)) {
			type = 2;
			$('#btnId').val(' 获取语音验证码 ');
		}
	});
	$("#btnId").click(function(){
		var phone = $("#phone").val();
		if(!phone.match(phone_re) && !phone.match(telphone_re)) {
			$('#spanId').html('请输入正确的手机号或电话号码，电话格式：053155500071');
			return ;
		}

		$("#btnId").attr('disabled', 'disabled');
		$("#btnId").val(' 正在发送... ');

		$.post('<?php echo url("my/default/ajaxverifycode");?>', {phone:phone, type:type}, function(data){
			if(data == '1') {
				$("#btnId").val(' (120秒后) 使用语音获取 ');
				if(type==1) {
					$('#spanId').html('如果2分钟后没有收到验证码，请使用语音获取验证码。');
				} else {
					$('#spanId').html('本站已向您的:' + phone + '拨打电话，并播报验证码。如果2分钟内没有接到语音电话，请重新获取。');
				}
				interval = setInterval(timeout, 1000);
			} else {
				$('#spanId').html('发送失败，有可能是您的号码有误，请重新填写并获取');
				if(type == 1) {
					$("#btnId").val(' 获取手机验证码 ');
				} else {
					$("#btnId").val(' 获取语音验证码 ');
				}
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
		$("#btnId").val(' (' + time + '秒后) 使用语音获取 ');
	} else {
		$("#btnId").attr('disabled', '');
		type = 2;
		$("#btnId").val(' 获取语音验证码 ');
		$('#spanId').html('');
		time = 120;
		clearInterval(interval);
	}
}
</script>