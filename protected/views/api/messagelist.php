<data52wm>
<data>
  	<errorCode>0</errorCode>
  	<errorMessage></errorMessage>
  	<messageList>
  	<?php foreach($message as $msg):?>
  	<message>
  		<id><?php echo $msg->id;?></id>
  		<title><?php echo $msg->title; ?></title>
  		<content><?php echo h($msg->content); ?></content>
  		<createTime><?php echo $msg->create_time; ?></createTime>
  	</message>
  	<?php endforeach;?>
  	</messageList>
</data>
</data52wm>