<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<cakegoodsCategory>
  	<?php foreach(CakeGoods::$categorys as $key=>$name):?>
  	<category>
  		<id><?php echo $key;?></id>
  		<name><?php echo $name; ?></name>
  	</category>
  	<?php endforeach;?>
  	</cakegoodsCategory>
</data>
</data52wm>