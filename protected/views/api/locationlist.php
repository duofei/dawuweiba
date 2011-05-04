<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<locationList>
  	<?php foreach($locations as $l):?>
  	<location>
  		<id><?php echo $l->id;?></id>
  		<name><?php echo $l->name; ?></name>
  		<longitude><?php echo $l->map_x; ?></longitude>
  		<latitude><?php echo $l->map_y; ?></latitude>
  	</location>
  	<?php endforeach;?>
  	</locationList>
</data>
</data52wm>