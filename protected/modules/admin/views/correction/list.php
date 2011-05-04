<?php if ($correction) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="150">用户名</th>
        <th class="al" width="120">内容</th>
        <th class="al" width="100">来源</th>
        <th class="al" width="120">提交时间</th>
        <th class="al" width="120">操作</th>
    </tr>
<?php foreach ($correction as $key=>$val) :?>
	<tr>
		<td><a href="<?php echo url('admin/user/info', array('id'=>$val->id))?>"><?php echo $val->user->username;?></a></td>
		<td><?php echo $val->content;?></td>
		<td><?php echo $val->source?></td>
		<td><?php echo $val->shortCreateDateTimeText;?></td>
		<td class="ac"></td>
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