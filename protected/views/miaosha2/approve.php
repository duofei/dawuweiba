<div class="c-left fl">
	<div class="c-left-top"></div>
	<div class="c-left-content">
		<?php $this->renderPartial('/miaosha2/left', array(
			'todayShops'=>$todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
			't' => $t
		));?>
	</div>
	<div class="c-left-bottom"></div>
</div>
<div class="c-right fl">
	<div class="c-right-top"></div>
	<div class="c-right-content">
		<div class="miaosha-info pa-t20px cgray">
			<div style="width:450px; margin:0px auto;">
				<div class="ac"><?php echo CHtml::image(resBu('miaosha2/images/yz_r1_c1.gif'));?></div>
				
				<?php echo CHtml::beginForm(url('miaosha2/checkout', array('miaosha_id'=>$miaosha_id)), 'post', array('id'=>'postform'));?>
				<input type="hidden" value="<?php echo $consignee;?>" name="UserAddress[consignee]" />
				<input type="hidden" value="<?php echo $telphone;?>" id="telphone" name="UserAddress[telphone]" />
				<input type="hidden" value="<?php echo $address;?>" name="UserAddress[address]" />
				<input type="hidden" value="<?php echo $message;?>" name="UserAddress[message]" />
				
				
				<div class="ma-t20px f14px pa-l20px">
					　　<span id="showMsg">手机号 <span class="cred">+86 <?php echo $telphone;?></span> 已经向此号发送短信验证码。</span>
				</div>
				<div class="ma-t10px f14px pa-l20px">
					　　验证码 <input type='text' class="txt f16px" style="width:75px;" id="vfcode" name="vfcode" /> <span id="vfcode_msg" class="cred"><?php echo $error;?></span>
				</div>
				<?php echo CHtml::endForm();?>
				<div class="pa-l20px ma-t10px">
					　　　　　　<span class="cursor" id="submit"><?php echo CHtml::image(resBu('miaosha2/images/yz_r3_c2.gif'));?></span>
				</div>
				<div class="mline1px ma-t10px ma-b10px"></div>
				<div class="ac lh24px">如果2分钟没有收到请点击重新获取</div>
				<div class="ac lh24px"><span id="btnId" send='0' class="cursor bgeee pa-l10px pa-r10px cblack">(120秒后) 使用语音获取</span> 或 <span class="cred"><a href="<?php echo url('miaosha2/order', array('miaosha_id'=>$miaosha_id));?>">返回修改手机</a></span></div>
			</div>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>
<script type="text/javascript">
var interval;
var time = 120;
$(function(){
	interval = setInterval(timeout, 1000);
	
	$('#submit').click(function(){
		if($('#vfcode').val() == '') {
			$('#vfcode_msg').html('* 验证码不能为空!');
			return ;
		}
		$('#postform').submit();
	});
	
	$('#btnId').click(function(){
		if($(this).attr('send') == 1) {
			var phone = $('#telphone').val();
			var type = 2;
			$("#btnId").attr('send', '0');
			$("#btnId").html(' 正在发送... ');
			$.post('<?php echo url("my/default/ajaxverifycode")?>', {phone:phone, type:type}, function(data){
				if(data == '1') {
					$("#btnId").html(' (120秒后) 使用语音获取 ');
					$('#showMsg').html('本站已向您的:' + phone + '拨打电话，并播报验证码。如果2分钟内没有接到语音电话，请重新获取。');
					interval = setInterval(timeout, 1000);
				} else {
					$('#showMsg').html('发送失败，有可能是您的号码有误!');
					$("#btnId").val('获取语音验证码');
					$("#btnId").attr('send', '0');
				}
			});
		}
	});
});
function timeout() {
	time--;
	if(time > 0) {
		$("#btnId").html('(' + time + '秒后) 使用语音获取');
	} else {
		$("#btnId").html('获取语音验证码');
		$("#btnId").attr('send', '1');
		time = 120;
		clearInterval(interval);
	}
}
</script>