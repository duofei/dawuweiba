<div class="checkout_1" id="cart" view="checkout_cake_cart">
	<?php $this->renderPartial('/cart/checkout_cake_cart', array('cart'=>$cart));?>
</div>
<div class="space10pxline"></div>
<?php if($cart[0]->goods->shop->buy_type == Shop::BUYTYPE_TELPHONE):?>
<h4 class="f16px">该餐厅(<?php echo $cart[0]->goods->shop->shop_name;?>)为电话订餐餐厅，请自行拨打餐厅电话<span class="f20px cred"><?php echo $cart[0]->goods->shop->telphone;?></span>进行订餐</h4>
<div class="space10pxline"></div>
<div class="lh30px f14px">您与餐厅核对金额，如果发现餐厅或网站的错误，请您<?php echo l('告诉我们', url('correction/create'));?>，谢谢！</div>
<?php else:?>
<div class="border-aaa pa-1px" style="height:100%">
	<div class="bg-7a f14px pa-l20px lh30px cwhite">填写或读取我的地址</div>
	<div class="fl w-50 ma-t10px rline ma-b5px checkout-form">
	<?php if(user()->isGuest):?>
	<h3 class="f18px ma-b20px pa-l20px lh30px">蛋糕类商品，登陆后才能下订单！</h3>
	<?php else:?>
	<form method="post" id="addressPost">
		<input type="hidden" name="UserAddress[id]" id="addressid" value="<?php echo $address_default->id;?>" />
		<input type="hidden" name="editAddress" id="editAddress" value="0" />
		<input type="hidden" name="shop_id" value="<?php echo $cart[0]->goods->shop->id;?>" />
		<p class="f16px lh24px pa-l20px fb">地址列表</p>
		<ul class="lh40px pa-l20px ma-t10px">
			<li class="h40px f14px">是否自提：
				<input type="radio" name="iscarry" value="1" checked>门店自提
				<?php if(!$isCarry):?>
				<input type="radio" name="iscarry" value="0" checked>商家送货
				<?php endif;?>
			</li>
			<li class="h40px f14px">收货人：&nbsp;&nbsp;&nbsp;<input type="text" value="<?php echo $address_default->consignee;?>" id="consignee" name="UserAddress[consignee]" tabindex="1" class="txt f16px"></li>
			<li class="h40px f14px">详细地址：<input type="text" value="<?php echo $address_default->address;?>" id="address" name="UserAddress[address]" tabindex="2" class="txt f16px"></li>
			<li class="h40px f14px">联系电话：<input type="text" value="<?php echo $address_default->telphone;?>" id="telphone" name="UserAddress[telphone]" tabindex="3" class="txt"></li>
			<li class="h40px f14px">备选电话：<input type="text" value="<?php echo $address_default->mobile;?>" id="mobile" name="UserAddress[mobile]" tabindex="4" class="txt"></li>
			<li class="h40px f14px">送货时间：<?php echo CHtml::dropDownList(deliver_time, null, Order::getDeliverTimeData($cart[0]->goods->shop->reserve_hour), array('id'=>'deliver_time', 'tabindex'=>5));?></li>
		</ul>
		<p class="f16px ma-t10px fb lh24px pa-l20px">订单备注<span class="f14px color666" style="font-weight:normal">（选填）</span></p>
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
<?php endif;?>
<?php endif;?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'blessingDialog',
	'htmlOptions' => array('class'=>'none'),
    'options'=>array(
        'title'=>'祝福语填写',
        'autoOpen'=>false,
		'width' => 400,
    ),
));
?>
<div id="cakeBlessingDiv" class="none">
	蛋糕祝福语：<select>
		<option value=''>选择常用祝福语</option>
        <option value="生日快乐">生日快乐</option>
		<option value="Happy Brithday!">Happy Brithday!</option>
		<option value="I LOVE YOU!">I LOVE YOU!</option>
		<option value="I MISS YOU!">I MISS YOU!</option>
		<option value="节日快乐">节日快乐</option>
		<option value="合家团圆">合家团圆</option>
		<option value="健康快乐">健康快乐</option>
		<option value="寿与天齐">寿与天齐</option>
		<option value="健康长寿">健康长寿</option>
		<option value="福如东海，寿比南山">福如东海，寿比南山</option>
		<option value="圣诞快乐">圣诞快乐</option>
	</select><br />
	<?php echo CHtml::textArea('cakeBlessingContent', '', array('style'=>'width:340px;height:68px;', 'class'=>'ma-t10px'));?>
</div>
<div id="cardBlessingDiv" class="ma-t10px none">
	贺卡祝福语：<select>
		<option value=''>选择常用祝福语</option>
        <option value="生日快乐">生日快乐</option>
		<option value="Happy Brithday!">Happy Brithday!</option>
		<option value="I LOVE YOU!">I LOVE YOU!</option>
		<option value="I MISS YOU!">I MISS YOU!</option>
		<option value="节日快乐">节日快乐</option>
		<option value="合家团圆">合家团圆</option>
		<option value="健康快乐">健康快乐</option>
		<option value="寿与天齐">寿与天齐</option>
		<option value="健康长寿">健康长寿</option>
		<option value="福如东海，寿比南山">福如东海，寿比南山</option>
		<option value="圣诞快乐">圣诞快乐</option>
	</select><br />
	<?php echo CHtml::textArea('cardBlessingContent', '', array('style'=>'width:340px;height:68px;', 'class'=>'ma-t10px'));?>
</div>
<div class="ma-t10px">
<input href="<?php echo url('cart/addblessing');?>" class="input-ok bg-7a cwhite ma-t10px cursor" id="saveBlessing" type="button" value="保存祝福语"/>
</div>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>