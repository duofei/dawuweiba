<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<foodgoodsCategory>
  	<?php foreach($category as $cat):?>
  	<category>
  		<id><?php echo $cat->id;?></id>
  		<name><?php echo $cat->name; ?></name>
  		<goodsNum><?php echo $cat->goods_nums; ?></goodsNum>
  	</category>
  	<?php endforeach;?>
  	</foodgoodsCategory>
</data>
</data52wm>