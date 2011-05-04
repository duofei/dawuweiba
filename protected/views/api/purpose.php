<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<purposeList>
  	<?php foreach($purpose as $p):?>
  	<purpose>
  		<id><?php echo $p->id;?></id>
  		<name><?php echo $p->name;?></name>
  	</purpose>
  	<?php endforeach;?>
  	</purposeList>
</data>
</data52wm>