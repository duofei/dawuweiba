<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<shapeList>
  	<?php foreach(CakeGoods::$shapes as $id=>$name):?>
  	<shape>
  		<id><?php echo $id;?></id>
  		<name><?php echo $name;?></name>
  	</shape>
  	<?php endforeach;?>
  	</shapeList>
</data>
</data52wm>