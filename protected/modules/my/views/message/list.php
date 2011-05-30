<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a href="<?php echo url("my/message/list");?>">短消息</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px lh30px">
	<table class="list">
		<tr>
			<th>标题</th>
			<th width="60">状态</th>
			<th width="120">时间</th>
		</tr>
	<?php if($message):?>
	<?php foreach($message as $row):?>
	  	<tr>
	    	<td class="<?php echo $row->is_read ? 'cblack' : 'cred';?>"><?php echo $row->titleLinkHtml; ?></td>
	    	<td class="ac"><?php echo $row->is_read ? '已读' : '未读';?></td>
	    	<td class="ac"><?php echo $row->shortCreateDateTimeText; ?></td>
	  	</tr>
	<?php endforeach;?>
	<?php else:?>
	<tr><td colspan="3" class="ac">无消息内容</td></tr>
	<?php endif;?>
	<tr><td colspan="3" class="pages ar pa-t5px">
		<?php $this->widget('CLinkPager', array(
			'pages' => $pages,
		    'header' => '',
		    'firstPageLabel' => '首页',
		    'lastPageLabel' => '末页',
		    'nextPageLabel' => '下一页',
		    'prevPageLabel' => '上一页',
		));?>
	</td></tr>
	</table>
</div>