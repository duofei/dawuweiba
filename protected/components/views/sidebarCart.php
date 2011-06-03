<?php if (count($this->cart) > 0):?>
<p class="f12px indent5px fb"><?php echo $cart[0]->goods->shop->shop_name;?></p>
<table class="m5px">
	<tr>
		<th class="al">美食</th>
		<th width="40">数量</th>
		<th width="34" class="al">价格</th>
		<th width="14"></th>
	</tr>
	<?php foreach ((array)$this->cart as $v):?>
	<tr class="bline" gid="<?php echo $v->id;?>" url="<?php echo url('cart/updatenums', array('goodsid'=>$v->id));?>" view="small_cart">
		<td><?php echo $v->goods_name;?></td>
		<td class="ac"><input class="goods-nums f12px goods-item-nums<?php echo $v->id;?>" name="nums" type="text" value="<?php echo $v->goods_nums;?>" /></td>
		<td><?php echo $v->goods_price * $v->goods_nums;?></td>
		<td><?php echo l(CHtml::image(resBu('images/pixel.gif'), '删除', array('class'=>'bg-icon del-icon')), url('cart/delete', array('cartid'=>$v->id)), array('class'=>'cart-del', 'gid'=>$v->goods_id, 'title'=>'删除'));?></td>
	</tr>
	<?php
	$totalPrice += $v->goods_price*$v->goods_nums;
	$totalGroupPrice += $v->group_price*$v->goods_nums;
	endforeach;
	?>
	<tr>
		<th class="al">总价</th>
		<th colspan="3" class="ac goods-total-price"><?php echo $totalPrice;?>元</th>
	</tr>
	<?php if($this->cart[0]->is_group):?>
	<tr>
		<th class="al">同楼价</th>
		<th colspan="3" class="ac goods-total-price"><?php echo $totalGroupPrice;?>元</th>
	</tr>
	<?php endif;?>
</table>
<p class="ar ma-r20px lh30px"><?php echo l('清空购物车', url('cart/clear'))?></p>
<?php if($this->cart[0]->goods->shop->buy_type == Shop::BUYTYPE_TELPHONE && STATE_DISABLED == Setting::getValue('checkPhoneOrderDeal', $this->cart[0]->goods->shop->district->city_id)):?>
<div class="m10px f14px">该餐厅需要自行电话订餐，请对照您的购物车拨打餐厅电话进行订餐。</div>
<h4 class="ma-l10px ma-b10px f14px none" id="shopTelphone">电话： <span class="cred"><?php echo $this->cart[0]->goods->shop->telphone;?></span></h4>
<div><div class="check-out fr ma-r20px ma-b10px relative">
	<?php if(user()->isGuest):?>
	<a href="<?php echo url('site/login', array('referer' => abu(app()->request->url)));?>" class="button-yellow"><span class="cred">请先登陆</span></a>
	<?php else:?>
	<a href="<?php echo url('cart/phoneCheckout');?>" alt="<?php echo $this->cart[0]->goods->shop->id;?>" id="phoneCheckOut" class="button-yellow"><span class="cred">查看电话</span></a>
	<?php endif;?>
</div></div>
<?php else:?>
<div><div class="check-out fr ma-r20px relative ma-b10px">
	<a href="<?php echo url('cart/checkout');?>" class="button-yellow"><span class="cred">买&nbsp;单</span></a>
</div></div>
<?php endif;?>
<div class="clear"></div>
<?php else:?>
<div class="cart-empty cgray">您挑选的美食会被放到这里</div>
<?php endif;?>
<div class="cart-icon bg-pic absolute"></div>


<?php cs()->registerScriptFile(resBu('scripts/wmcart.js'), CClientScript::POS_END);?>
<script type="text/javascript">
$(function(){
	$('.goods-nums').keyup(updateCartGoodsNums);
	$('.cart-del').click(delCartOneGoods);
	$('#phoneCheckOut').click(phoneCheckOut);
});
</script>