<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<shopList>
  	<?php foreach($shops as $shop):?>
  	<shop>
  		<id><?php echo $shop->id;?></id>
  		<name><?php echo $shop->shop_name; ?></name>
  		<logo><?php echo sbu($shop->logo); ?></logo>
  		<desc><?php echo h($shop->desc); ?></desc>
  		<announcement><?php echo h($shop->announcement); ?></announcement>
  		<address><?php echo $shop->address; ?></address>
  		<businessState><?php echo $shop->service_avg; ?></businessState>
  		<businessTime><?php echo $shop->business_time; ?></businessTime>
  		<transportCondition><?php echo h($shop->transport_condition); ?></transportCondition>
  		<transportTime><?php echo $shop->transport_time; ?></transportTime>
  		<isMuslim><?php echo $shop->is_muslim; ?></isMuslim>
  		<isSanitaryApprove><?php echo $shop->is_sanitary_approve; ?></isSanitaryApprove>
  		<isDailymenu><?php echo $shop->is_dailymenu; ?></isDailymenu>
  		<createTime><?php echo $shop->create_time; ?></createTime>
  		<goodsNums><?php echo $shop->goods_nums; ?></goodsNums>
  		<visitNums><?php echo $shop->visit_nums; ?></visitNums>
  		<serviceAvg><?php echo $shop->service_avg; ?></serviceAvg>
  		<tasteAvg><?php echo $shop->taste_avg; ?></tasteAvg>
  		<telphone><?php echo $shop->telphone; ?></telphone>
  		<mobile><?php echo $shop->mobile; ?></mobile>
  		<qq><?php echo $shop->qq; ?></qq>
  		<tags>
  			<?php foreach($shop->tags as $tag):?>
  			<tag><?php echo $tag->name;?></tag>
  			<?php endforeach;?>
  		</tags>
  	</shop>
  	<?php endforeach;?>
  	</shopList>
</data>
</data52wm>