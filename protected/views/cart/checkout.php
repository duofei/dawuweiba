<div class="checkout_1" id="cart" view="checkout_cart">
	<?php $this->renderPartial('/cart/checkout_cart', array('cart'=>$cart));?>
</div>
<div class="space10pxline"></div>
<?php if($cart[0]->goods->shop->buy_type == Shop::BUYTYPE_TELPHONE):?>
<h4 class="f16px">该餐厅(<?php echo $cart[0]->goods->shop->shop_name;?>)为电话订餐餐厅，请自行拨打餐厅电话<span class="f20px cred"><?php echo $cart[0]->goods->shop->telphone;?></span>进行订餐</h4>
<div class="space10pxline"></div>
<div class="lh30px f14px">您与餐厅核对金额，如果发现餐厅或网站的错误，请您<?php echo l('告诉我们', url('correction/create'));?>，谢谢！</div>
<?php else:?>
<div class="border-aaa pa-1px ma-t20px" style="height:100%">
	<div class="bg-7a f14px pa-l20px lh30px cwhite">订单信息填写</div>
	<div class="fl w-50 ma-t10px rline ma-b5px checkout-form">
	<?php if(user()->isGuest):?>
	<h3 class="f18px ma-b20px pa-l20px lh30px">登录后才能下订单！</h3>
	<?php else:?>
	<form method="post" id="addressPost">
		<?php if($usebcnum >= 1 && $cart[0]->goods->shop->is_bcshop==STATE_ENABLED):?>
		<p class="f16px lh24px pa-l20px fb">使用白吃点</p>
		<ul class="lh40px pa-l20px ma-t5px ma-b10px">
			<?php if($user->bcnums):?>
			<li class="h40px f14px">
				您有<?php echo $user->bcnums;?>点白吃点，最多可以使用
				<input type="text" value="<?php echo $allowUseBcnum;?>" name="bcnum" onblur="bcnumBlur(this,'<?php echo $allowUseBcnum;?>')" class="txt f16px ar" style="width:30px" />
				点。<?php echo l('我要获取更多白吃点', url('intro/baichidian'))?>。
			</li>
			<?php else:?>
			<li class="h40px f14px">
				这里允许使用<?php echo $usebcnum;?>点白吃点。您没有可用的白吃点，<?php echo l('怎样获取白吃点', url('intro/baichidian'))?>？
			</li>
			<?php endif;?>
		</ul>
		<?php endif;?>
		<input type="hidden" name="UserAddress[id]" id="addressid" value="<?php echo $address_default->id;?>" />
		<input type="hidden" name="editAddress" id="editAddress" value="0" />
		<input type="hidden" name="shop_id" value="<?php echo $cart[0]->goods->shop->id;?>" />
		<p class="f16px lh24px pa-l20px fb">送餐地址</p>
		<ul class="lh40px pa-l20px ma-t10px">
			<li class="h40px f14px">收货人：&nbsp;&nbsp;&nbsp;<input type="text" value="<?php echo $address_default->consignee;?>" id="consignee" name="UserAddress[consignee]" tabindex="1" class="txt f16px"></li>
			<li class="h40px f14px">详细地址：<input type="text" value="<?php echo $address_default->address;?>" id="address" name="UserAddress[address]" tabindex="2" class="txt f16px"></li>
			<li class="h40px f14px">手机号码：<input type="text" value="<?php echo $address_default->telphone;?>" id="telphone" name="UserAddress[telphone]" tabindex="3" class="txt"></li>
			<li class="lh20px cred">　　　　　　尽量填写手机号，以便收到订单提醒。</li>
			<?php if($user->approve_state != User::APPROVE_STATE_VERIFY):?>
			<li class="h40px f14px">验证码：&nbsp;&nbsp;&nbsp;<input type="text" value="" id="vcode" name="vcode" tabindex="4" class="txt" style="width:60px;">
				<?php echo CHtml::button(' 获取验证码 ', array('id'=>'btnId'));?>
			</li>
			<li class="lh20px cred" id="spanId">　　　　　　<?php if($_POST['vcode']):?>手机验证码填写错误<?php else:?>首次下网络订单用户需要手机认证。<?php endif;?></li>
			<?php else:?>
			<li class="h40px f14px">备选电话：<input type="text" value="<?php echo $address_default->mobile;?>" id="mobile" name="UserAddress[mobile]" tabindex="4" class="txt"></li>
			<?php endif;?>
			<?php if($cart[0]->is_group):?>
			<li class="h40px f14px">送货时间：<?php if(time() > Groupon::getTodayGroupEndTime()):?>明天<?php endif;?>12:00<?php echo CHtml::hiddenField('deliver_time', '12:00');?></li>
			<?php else:?>
			<li class="h40px f14px">送货时间：<?php echo CHtml::dropDownList('deliver_time', null, Order::getDeliverTimeData($cart[0]->goods->shop->reserve_hour), array('id'=>'deliver_time', 'tabindex'=>5));?></li>
			<?php endif;?>
		</ul>
		<p class="f16px ma-t10px fb lh24px pa-l20px">订单备注<span class="f14px color666" style="font-weight:normal">（选填）</span></p>
		<div class="message-li ma-t5px pa-l20px"><ul><li>么零钱</li><li>不要葱姜蒜 </li><li>不吃辣</li><li>辣一点</li><li>多加米</li><li>自取</li><li>谢谢:)</li></ul></div>
		<div class="message-textarea pa-l20px"><textarea name="UserAddress[message]" id="message" class="txt f14px" tabindex="6"></textarea></div>
	</form>
	<?php endif;?>
	</div>
	<div class="fl lh30px  ma-t10px ma-b5px checkout-address">
	<?php if(user()->isGuest):?>
		<?php $this->renderPartial('user_login', array('loginModel'=>$loginModel));?>
	<?php else:?>
		<?php $this->renderPartial('user_address', array('address'=>$address, 'checkid'=>$address_default->id));?>
	<?php endif;?>
	</div>
	<div class="clear"></div>
	<div><?php echo $errorSummary;?></div>
</div>
<?php if(!user()->isGuest):?>
<input type="button" value="确认订单" name="yt0" tabindex="7" class="input-ok bg-a1 cwhite fb ma-t10px cursor" onclick="javascript:$('#addressPost').submit();">
	<?php if(Cart::getGoodsAmount() < $cart[0]->goods->shop->matchTransportAmount):?>
	<span class="ft14px fb pa-10px">您购物车还没有达到最低起送价(&yen;<?php echo $cart[0]->goods->shop->matchTransportAmount;?>)，请<?php echo l('继续购买', $cart[0]->goods->shop->relativeUrl);?>。</span>
	<?php endif;?>
<?php endif;?>
<script type="text/javascript">
$(function(){
	$(".message-li li").click(function(){
		$("#message").val($("#message").val() + ' ' + $(this).html());
	});
});
function bcnumBlur(obj, bcnum) {
	if(parseInt($(obj).val()) > parseInt(bcnum)) {
		$(obj).val(bcnum);
	}
}
</script>
<?php endif;?>


<?php if(time() < mktime(0,0,0,11,28,2010)): ?>
<!-- 开业之前显示的提示 -->
<span id="showOverlayBox" url="<?php echo url('site/cartpopbox');?>"></span>
<script type="text/javascript">
$(function(){
	showOverlayBox($('#showOverlayBox').attr('url'));
});
</script>
<?php cs()->registerCssFile(resBu("styles/colorbox.css"), 'screen'); ?>
<?php endif;?>

<?php if($user->approve_state == User::APPROVE_STATE_UNSETTLED):?>
<script type="text/javascript">
var interval;
var time = 120;
var type = 1;
var phone_re = /^1(30|31|32|33|34|35|36|37|38|39|50|51|52|53|55|56|57|58|59|86|87|88|89)\d{8}$/;
var telphone_re = /^(0[1-9][0-9]{1,2}-?)?[1-9][0-9]{6,7}$/;
$(function(){
	$("#btnId").click(function(){
		var phone = $("#telphone").val();
		if(!phone.match(phone_re) && !phone.match(telphone_re)) {
			$('#spanId').html('　　　　　　请输入正确的手机号或电话号码，电话格式：053155500071');
			return ;
		}
		if(phone.match(telphone_re)) {
			type = 2;
		}
		$("#btnId").attr('disabled', 'disabled');
		$("#btnId").val(' 正在发送... ');
		$.post('<?php echo url("my/default/ajaxverifycode")?>', {phone:phone, type:type}, function(data){
			if(data == '1') {
				$("#btnId").val(' (120秒后) 使用语音获取 ');
				if(type == 1) {
					$('#spanId').html('　　　　　　如果2分钟后没有收到验证码，请使用语音获取验证码。');
				} else {
					$('#spanId').html('　　　　　　本站已向您的:' + phone + '拨打电话，并播报验证码。<br />　　　　　　如果2分钟内没有接到语音电话，请重新获取。');
				}
				interval = setInterval(timeout, 1000);
			} else {
				$('#spanId').html('　　　　　　发送失败，有可能是您的号码有误，请重新填写并获取');
				$("#btnId").val(' 获取验证码 ');
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
		$('#spanId').html('　　　　　　请重新获取语音验证码');
		time = 120;
		clearInterval(interval);
	}
}
</script>
<?php elseif ($user->approve_state == User::APPROVE_STATE_BLACKLIST):?>
<script type="text/javascript">
$(function(){
	$("#btnId").click(function(){
		$('#spanId').html('　　　　　　您已被我们加入了黑名单中，请与我们客服联系0531-55500071');
	});
});
</script>
<?php endif;?>