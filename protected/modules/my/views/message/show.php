<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/message/list");?>">系统消息</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<h3 class="lh20px ac"><?php echo $message->title; ?> <span class="fn f12px"><?php echo $message->createDateTimeText; ?></span></h3>
	<div class="space10pxline"></div>
	<div class="line1px"></div>
	<div class="space10pxline"></div>
	<div>
	<?php echo nl2br($message->content); ?>
	</div>
</div>