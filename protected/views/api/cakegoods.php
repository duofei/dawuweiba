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
  		<categoryText><?php echo $goods->goodsModel->categoryText; ?></categoryText>
  		<shapeId><?php echo $goods->goodsModel->shape_id; ?></shapeId>
  		<shapeText><?php echo $goods->goodsModel->shapeText; ?></shapeText>
  		<saccharinity><?php echo $goods->goodsModel->saccharinity; ?></saccharinity>
  		<saccharinitysText><?php echo $goods->goodsModel->saccharinitysText; ?></saccharinitysText>
  		<isSugar><?php echo $goods->goodsModel->is_sugar; ?></isSugar>
  		<sugarText><?php echo $goods->goodsModel->sugarText; ?></sugarText>
  		<purposes><?php if($goods->goodsModel->category_id == CakeGoods::CATEGROY_CAKE):?>
  			<?php foreach($goods->goodsModel->Purposes as $purpose):?>
  			<purpose>
  				<purposeId><?php echo $purpose->id;?></purposeId>
  				<name><?php echo $purpose->name;?></name>
  			</purpose>
  			<?php endforeach;?>
  		<?php endif;?></purposes>
  		<varietys><?php if($goods->goodsModel->category_id == CakeGoods::CATEGROY_CAKE):?>
  			<?php foreach($goods->goodsModel->Varietys as $variety):?>
  			<variety>
  				<varietyId><?php echo $variety->id;?></varietyId>
  				<name><?php echo $variety->name;?></name>
  			</variety>
  			<?php endforeach;?>
  		<?php endif;?></varietys>
  		<label><?php echo $goods->goodsModel->label; ?></label>
  		<buyAdvice><?php echo $goods->goodsModel->buy_advice; ?></buyAdvice>
  		<stuff><?php echo $goods->goodsModel->stuff; ?></stuff>
  		<pack><?php echo $goods->goodsModel->pack; ?></pack>
  		<taste><?php echo $goods->goodsModel->taste; ?></taste>
  		<freshCondition><?php echo $goods->goodsModel->fresh_condition; ?></freshCondition>
  		<isCakeBlessing><?php echo $goods->goodsModel->is_cake_blessing; ?></isCakeBlessing>
  		<isCardBlessing><?php echo $goods->goodsModel->is_card_blessing; ?></isCardBlessing>
  		<bigPic><?php echo sbu($goods->goodsModel->big_pic); ?></bigPic>
  		<smallPic><?php echo sbu($goods->goodsModel->small_pic); ?></smallPic>
  		<marketPrice><?php echo $goods->goodsModel->market_price; ?></marketPrice>
  		<wmPrice><?php echo $goods->goodsModel->wm_price; ?></wmPrice>
  		<?php if($goods->goodsModel->category_id == CakeGoods::CATEGROY_CAKE):?>
  		<cakePrice>
  		<?php foreach ($goods->goodsModel->cakePrices as $cp):?>
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
</data>
</data52wm>