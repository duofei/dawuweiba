<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<goodsrate>
  	<?php foreach($rates as $rate):?>
  	<rate>
  		<id><?php echo $rate->id;?></id>
  		<goodsId><?php echo $rate->goods_id;?></goodsId>
  		<userId><?php echo $rate->user_id;?></userId>
  		<shopId><?php echo $rate->shop_id;?></shopId>
  		<ordergoodsId><?php echo $rate->ordergoods_id;?></ordergoodsId>
  		<createTime><?php echo $rate->create_time;?></createTime>
  		<content><?php echo $rate->content;?></content>
  		<mark><?php echo $rate->mark;?></mark>
  		<username><?php echo $rate->user->username; ?></username>
  		<realname><?php echo $rate->user->realname; ?></realname>
  	</rate>
  	<?php endforeach;?>
  	</goodsrate>
</data>
</data52wm>