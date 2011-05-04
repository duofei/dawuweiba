<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<goodslist>
  	<?php foreach($goodslist as $goods):?>
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
  		<categoryId><?php echo $goods->$model->category_id; ?></categoryId>
  		<categoryName><?php echo $goods->$model->goodsCategory->name; ?></categoryName>
  		<marketPrice><?php echo $goods->$model->market_price; ?></marketPrice>
  		<wmPrice><?php echo $goods->$model->wm_price; ?></wmPrice>
  		<groupPrice><?php echo $goods->$model->group_price; ?></groupPrice>
  		<isSpicy><?php echo $goods->$model->is_spicy; ?></isSpicy>
  		<desc><?php echo $goods->$model->desc; ?></desc>
  	</goods>
  	<?php endforeach;?>
  	</goodslist>
</data>
</data52wm>