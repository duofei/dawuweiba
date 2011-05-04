<h3 class="f16px ma-t10px ma-b10px cred">礼品兑换中心</h3>
<p class="f14px lh24px cgray">您要兑换的礼品是<span class="cred"><?php echo $gift->nameLinkHtml;?></span>，兑换该礼品要扣除您<span class="cred"><?php echo $gift->integral;?></span>积分</p>
<p class="f14px lh24px cgray">请认真填写下面的详细资料，我们会尽快与您取的联系。</p>

<div class="border-aaa pa-1px ma-t20px" style="height:100%">
	<div class="bg-7a f14px pa-l20px lh30px cwhite">填写或读取我的地址</div>
	<div class="fl w-50 ma-t10px rline ma-b5px checkout-form">
	<form method="post" id="addressPost" action="<?php echo url('gift/exchange', array('giftid'=>$gift->id));?>">
		<input type="hidden" name="GiftExchange[id]" id="addressid" value="<?php echo $address_default->id;?>" />
		<input type="hidden" name="editAddress" id="editAddress" value="0" />
		<input type="hidden" name="gift_id" value="<?php echo $gift->id;?>" />
		<p class="f16px lh24px pa-l20px fb">您的地址</p>
		<ul class="lh40px pa-l20px ma-t10px">
			<li class="h40px f14px">选择城市：<?php echo CHtml::dropDownList('GiftExchange[city_id]', '<?php echo $address_default->city_id;?>', City::getCityArray(), array('id'=>'cityid'));?></li>
			<li class="h40px f14px">收货人：&nbsp;&nbsp;&nbsp;<input type="text" value="<?php echo $address_default->consignee;?>" id="consignee" name="GiftExchange[consignee]" tabindex="1" class="txt f16px"></li>
			<li class="h40px f14px">详细地址：<input type="text" value="<?php echo $address_default->address;?>" id="address" name="GiftExchange[address]" tabindex="2" class="txt f16px"></li>
			<li class="h40px f14px">联系电话：<input type="text" value="<?php echo $address_default->telphone;?>" id="telphone" name="GiftExchange[telphone]" tabindex="3" class="txt"></li>
			<li class="h40px f14px">备选电话：<input type="text" value="<?php echo $address_default->mobile;?>" id="mobile" name="GiftExchange[mobile]" tabindex="4" class="txt"></li>
		</ul>
		<p class="f16px ma-t10px fb lh24px pa-l20px">备注<span class="f14px color666" style="font-weight:normal">（选填）</span></p>
		<div class="message-textarea pa-l20px"><textarea name="GiftExchange[message]" id="message" class="txt f14px" tabindex="6"></textarea></div>
	</form>
	<div class="ma-l10px"><?php echo CHtml::errorSummary($giftexchange);?></div>
	</div>
	<div class="fl lh30px  ma-t10px ma-b5px checkout-address">
	<?php $this->renderPartial('/cart/user_address', array('address'=>$address, 'checkid'=>$address_default->id));?>
	</div>
	<div class="clear"></div>
</div>

<input type="button" value="兑换礼品" name="yt0" tabindex="7" class="input-ok bg-a1 cwhite fb ma-t10px cursor" onclick="javascript:$('#addressPost').submit();">
