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
  		<categoryText><?php echo $goods->$model->categoryText; ?></categoryText>
  		<shapeId><?php echo $goods->$model->shape_id; ?></shapeId>
  		<shapeText><?php echo $goods->$model->shapeText; ?></shapeText>
  		<saccharinity><?php echo $goods->$model->saccharinity; ?></saccharinity>
  		<saccharinitysText><?php echo $goods->$model->saccharinitysText; ?></saccharinitysText>
  		<isSugar><?php echo $goods->$model->is_sugar; ?></isSugar>
  		<sugarText><?php echo $goods->$model->sugarText; ?></sugarText>
  		<purposes><?php if($goods->$model->category_id == CakeGoods::CATEGROY_CAKE):?>
  			<?php foreach($goods->$model->Purposes as $purpose):?>
  			<purpose>
  				<purposeId><?php echo $purpose->id;?></purposeId>
  				<name><?php echo $purpose->name;?></name>
  			</purpose>
  			<?php endforeach;?>
  		<?php endif;?></purposes>
  		<varietys><?php if($goods->$model->category_id == CakeGoods::CATEGROY_CAKE):?>
  			<?php foreach($goods->$model->Varietys as $variety):?>
  			<variety>
  				<varietyId><?php echo $variety->id;?></varietyId>
  				<name><?php echo $variety->name;?></name>
  			</variety>
  			<?php endforeach;?>
  		<?php endif;?></varietys>
  		<label><?php echo $goods->$model->label; ?></label>
  		<buyAdvice><?php echo $goods->$model->buy_advice; ?></buyAdvice>
  		<stuff><?php echo $goods->$model->stuff; ?></stuff>
  		<pack><?php echo $goods->$model->pack; ?></pack>
  		<taste><?php echo $goods->$model->taste; ?></taste>
  		<freshCondition><?php echo $goods->$model->fresh_condition; ?></freshCondition>
  		<isCakeBlessing><?php echo $goods->$model->is_cake_blessing; ?></isCakeBlessing>
  		<isCardBlessing><?php echo $goods->$model->is_card_blessing; ?></isCardBlessing>
  		<bigPic><?php echo sbu($goods->$model->big_pic); ?></bigPic>
  		<smallPic><?php echo sbu($goods->$model->small_pic); ?></smallPic>
  		<marketPrice><?php echo $goods->$model->market_price; ?></marketPrice>
  		<wmPrice><?php echo $goods->$model->wm_price; ?></wmPrice>
  		<?php if($goods->$model->category_id == CakeGoods::CATEGROY_CAKE):?>
  		<cakePrice>
  		<?php foreach ($goods->$model->cakePrices as $cp):?>
  		<price>
  			<cakePriceId><?php echo $cp->id; ?></cakePriceId>
  			<size><?php echo $cp->size; ?></size>
  			<marketPrice><?php echo $cp->market_price; ?></marketPrice>
  			<wmPrice><?php echo $cp->wm_price; ?></wmPrice>
  			<desc><?php echo $cp->desc; ?></desc>
  		</price>
  		<?php endforeach;?>
  		</cakePrice>
  		<?php endif;?>
  	</goods>
  	<?php endforeach;?>
  	</goodslist>
</data>
</data52wm>