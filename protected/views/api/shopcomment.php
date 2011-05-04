<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<shopCommentList>
  	<?php foreach($shopcomment as $c):?>
  	<shopComment>
  		<id><?php echo $c->id;?></id>
  		<shopId><?php echo $c->shop_id;?></shopId>
  		<orderId><?php echo $c->order_id; ?></orderId>
  		<createTime><?php echo $c->create_time; ?></createTime>
  		<content><?php echo $c->content; ?></content>
  		<reply><?php echo $c->reply; ?></reply>
  		<replyTime><?php echo $c->reply_time; ?></replyTime>
  	</shopComment>
  	<?php endforeach;?>
  	</shopCommentList>
</data>
</data52wm>