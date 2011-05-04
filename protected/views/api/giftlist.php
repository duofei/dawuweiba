<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<giftList>
  	<?php foreach($giftlist as $gift):?>
  	<gift>
  		<id><?php echo $gift->id;?></id>
  		<name><?php echo $gift->name; ?></name>
  		<pic><?php echo sbu($gift->pic); ?></pic>
  		<content><?php echo h($gift->content); ?></content>
  		<integral><?php echo $gift->integral; ?></integral>
  		<createTime><?php echo $gift->create_time; ?></createTime>
  	</gift>
  	<?php endforeach;?>
  	</giftList>
</data>
</data52wm>