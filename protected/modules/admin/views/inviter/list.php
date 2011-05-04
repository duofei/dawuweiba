<?php if ($list) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="150">用户名</th>
        <th width="120">被邀请用户</th>
        <th width="100">白吃点数</th>
        <th width="80">状态</th>
        <th class="al" width="120">邀请时间</th>
        <th class="al" width="120">成功时间</th>
        <th class="al"></th>
    </tr>
<?php foreach ($list as $key=>$val) :?>
	<tr>
		<td class="ac"><?php echo l($val->user->username, url('admin/user/info', array('id'=>$val->user_id)));?></td>
		<td class="ac"><?php echo l($val->invitee->username, url('admin/user/info', array('id'=>$val->invitee_id)));?></td>
		<td class="ac"><?php echo $val->integral;?></td>
		<td class="ac"><?php echo $val->stateText;?></td>
		<td><?php echo $val->shortCreateDateTimeText;?></td>
		<td><?php echo $val->shortUpdateDateTimeText;?></td>
		<td></td>
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
<?php else:?>
<div>目前列表</div>
<?php endif;?>