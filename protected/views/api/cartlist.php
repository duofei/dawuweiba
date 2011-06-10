<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<cartList>
  	<?php foreach($cartlist as $cart):?>
  	<goods>
  		<id><?php echo $cart->id;?></id>
  		<goodsId><?php echo $cart->goods_id; ?></goodsId>
  		<cakepriceId><?php echo $cart->cakeprice_id; ?></cakepriceId>
  		<goodsName><?php echo $cart->goods_name; ?></goodsName>
  		<goodsNums><?php echo $cart->goods_nums; ?></goodsNums>
  		<goodsPrice><?php echo $cart->goods_price; ?></goodsPrice>
  		<createTime><?php echo $cart->create_time; ?></createTime>
  		<remark><?php echo h($cart->remark); ?></remark>
  	</goods>
  	<?php endforeach;?>
  	</cartList>
</data>
</data52wm>