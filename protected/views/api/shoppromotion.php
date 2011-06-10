<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<shopCommentList>
  	<?php foreach($shoppromotion as $c):?>
  	<shopComment>
  		<id><?php echo $c->id;?></id>
  		<shopId><?php echo $c->shop_id;?></shopId>
  		<createTime><?php echo $c->create_time; ?></createTime>
  		<content><?php echo h($c->content); ?></content>
  		<recommend><?php echo $c->recommend; ?></recommend>
  	</shopComment>
  	<?php endforeach;?>
  	</shopCommentList>
</data>
</data52wm>