<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th>商铺名称</th>
        <th>用户名</th>
        <th>评论内容/商家回复</th>
        <th width="120">评论时间</th>
        <th>回复时间</th>
    </tr>
	<?php foreach ($shopcomment as $k=>$v):?>
	<tr>
		<td><?php echo $v->shop->getNameLinkHtml(0, '_blank');?></td>
		<td><?php echo l($v->user->screenName, url('admin/user/info', array('id'=>$v->user_id)));?></td>
		<td class="lh20px">评论：<?php echo trim(h($v->content));?> <br />
		回复：<?php echo trim(h($v->reply));?></td>
		<td><?php echo $v->shortCreateDateTimeText;?></td>
		<td>
		<?php if($v->reply_time):?>
		<?php echo $v->shortReplyDateTimeText;?>
		<?php endif;?>
		</td>
	</tr>
	<?php endforeach;?>
</table>
<div class="pages ar">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '翻页',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
</div>