<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<shopCategory>
  	<?php foreach(ShopCategory::$categorys as $key=>$name):?>
  	<category>
  		<id><?php echo $key;?></id>
  		<name><?php echo $name; ?></name>
  	</category>
  	<?php endforeach;?>
  	</shopCategory>
</data>
</data52wm>