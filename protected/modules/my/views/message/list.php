<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/message/list");?>">系统消息</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<table class="list">
		<tr>
			<th>标题</th>
			<th width="160">时间</th>
		</tr>
	<?php foreach($message as $row):?>
	  	<tr>
	    	<td><?php echo $row->titleLinkHtml; ?></td>
	    	<td><?php echo $row->createDateTimeText; ?></td>
	  	</tr>
	<?php endforeach;?>
	</table>
</div>