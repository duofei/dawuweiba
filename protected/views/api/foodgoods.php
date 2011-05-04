<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<goods>
  		<id><?php echo $goods->id;?></id>
  		<name><?php echo $goods->name; ?></name>
  		<shopId><?php echo $goods->shop_id; ?></shopId>
  		<pic><?php echo sbu($goods->pic); ?></pic>
  		<favoriteNums><?php echo $goods->favorite_nums; ?></favoriteNums>
  		<commentNums><?php echo $goods->comment_nums; ?></commentNums>
  		<rateAvg><?php echo $goods->rate_avg; ?></rateAvg>
  		<createTime><?php echo $goods->create_time; ?></createTime>
  		<isNew><?php echo $goods->is_new; ?></isNew>
  		<isTuan><?php echo $goods->is_tuan; ?></isTuan>
  		<isCarry><?php echo $goods->is_carry; ?></isCarry>
  		<tags><?php if(count($goods->tags) > 0):?>
  			<?php foreach($goods->tags as $tag):?>
  			<tag><?php echo $tag->name;?></tag>
  			<?php endforeach;?>
  		<?php endif;?></tags>
  		<categoryId><?php echo $goods->goodsModel->category_id; ?></categoryId>
  		<categoryName><?php echo $goods->goodsModel->goodsCategory->name; ?></categoryName>
  		<marketPrice><?php echo $goods->goodsModel->market_price; ?></marketPrice>
  		<wmPrice><?php echo $goods->goodsModel->wm_price; ?></wmPrice>
  		<groupPrice><?php echo $goods->goodsModel->group_price; ?></groupPrice>
  		<isSpicy><?php echo $goods->goodsModel->is_spicy; ?></isSpicy>
  		<desc><?php echo $goods->goodsModel->desc; ?></desc>
  	</goods>
</data>
</data52wm>