<?php
if (count($cart) == 0) {
    echo 0;
    exit(0);
}
?>
<h3 class="f16px ma-b10px">我的购物车</h3>
<p class="f14px">店铺：<?php echo $cart[0]->goods->shop->nameLinkHtml;?>&nbsp;&nbsp;<span class="cred f12px">(<span class="fb">起送条件:</span><?php echo $cart[0]->goods->shop->transport_condition; ?>)</span></p>
<div class="ma-t5px border-aaa pa-1px">
	<table  width="100%">
		<tr class="cwhite f14px lh30px" bgcolor="#7a7f89">
			<td class=" indent24px" >商品名称</td>
			<td width="80"></td>
            <td width="80">积分</td>
            <td width="80">价格</td>
            <td width="80">数量</td>
            <td width="80">小计</td>
            <td width="80">操作</td>
		</tr>
	</table>
	<table width="100%" class="bg-color" id="cartlist">
	<?php foreach ((array)$cart as $v):?>
		<tr gid="<?php echo $v->id;?>" url="<?php echo url('cart/updatenums', array('goodsid'=>$v->id));?>" class="tline tr-padding">
            <td class="indent24px"><?php echo $v->goods_name;?></td>
            <td width="80"><?php if($v->goods->goodsModel->is_cake_blessing == STATE_ENABLED || $v->goods->goodsModel->is_card_blessing == STATE_ENABLED):?>
        	<?php echo l('祝福语', url('cart/addblessing'), array('id'=>'addblessingId_'.$v->id, 'class'=>'cart-add-blessing', 'cartid'=>$v->id, 'cakeblessingcontent'=>$v->remarkArray[0], 'cakeblessing'=>$v->goods->goodsModel->is_cake_blessing, 'cardblessingcontent'=>$v->remarkArray[1], 'cardblessing'=>$v->goods->goodsModel->is_card_blessing));?>
        	<?php endif;?></td>
            <td width="80"><?php echo (int)$v->goods_price;?></td>
            <td width="80">&yen;<?php echo $v->goods_price;?></td>
            <td width="80" ><input name="" type="text" value="<?php echo $v->goods_nums;?>" class="goods-nums" /></td>    
            <td width="80" >&yen;<?php echo $v->goods_price * $v->goods_nums;?></td>
            <td width="80"><?php echo l('删除', url('cart/delete', array('cartid'=>$v->id)), array('class'=>'cart-del', 'gid'=>$v->goods_id));?></td>
		</tr>
		<?php if(($v->goods->goodsModel->is_cake_blessing == STATE_ENABLED) || ($v->goods->goodsModel->is_card_blessing == STATE_ENABLED)):?>
    	<tr class="bg-f5"><td class="indent24px lh30px" colspan="7" id="blessing_msg_<?php echo $v->id;?>">
		<?php if($v->remarkArray[0]) echo '蛋糕祝福语：'.h($v->remarkArray[0]) . '&nbsp;&nbsp;&nbsp;&nbsp;';?>
    	<?php if($v->remarkArray[1]) echo '贺卡祝福语：'.h($v->remarkArray[1]);?>
    	</td></tr>
    	<?php endif;?>
		<?php $totalPrice += $v->goods_price*$v->goods_nums; endforeach;?>
	</table>
	<table width="100%" class="tab-padding">
		<tr class="tline">
			<td width="78%" class=" indent10px cwhite" ><a class="bg-7a pa-5px-20px" href="<?php echo $cart[0]->goods->shop->relativeUrl;?>">继续购物</a></td>            
            <td width="22%"  class="f14px fb red" >订单总价:&yen;<?php echo $totalPrice;?></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(function(){
	$('.goods-nums').keyup(updateCartGoodsNums);
	$('.cart-del').click(delCartOneGoods);
});
</script>
<script type="text/javascript">
$(function(){
	$('.goods-nums').keyup(updateCartGoodsNums);
	$('.cart-del').click(delCartOneGoods);
	$('.cart-add-blessing').click(openBlessingDialog);
	$('#saveBlessing').click(addCartBlessing);
});
</script>