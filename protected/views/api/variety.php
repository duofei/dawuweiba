<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<varietyList>
  	<?php foreach($variety as $v):?>
  	<variety>
  		<id><?php echo $v->id;?></id>
  		<name><?php echo $v->name;?></name>
  	</variety>
  	<?php endforeach;?>
  	</varietyList>
</data>
</data52wm>