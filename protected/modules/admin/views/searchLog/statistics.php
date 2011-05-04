<?php if ($searchs) :?>
<table  class="tabcolor list-tbl ma-b5px ma-t5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al">关键字</th>
        <th class="al" width="120">查询次数</th>
        <th class="al" width="60">操作</th>
    </tr>
<?php foreach ($searchs as $key=>$val) :?>
	<tr>
		<td><?php echo $val[keywords]?></td>
		<td><?php echo $val[c]?></td>
		<td><a href="<?php echo url('admin/searchLog/delete', array('key'=>urlencode($val[keywords])))?>" onclick="return confirm('确定要删除吗？');">删除</a></td>
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
  	<div>没有搜索记录</div>
<?php endif;?>
<?php echo user()->getFlash('errorSummary'); ?>