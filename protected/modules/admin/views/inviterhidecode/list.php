<?php if ($list) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="150">隐藏码</th>
        <th width="120">白吃点数</th>
        <th width="100">被使用次数</th>
        <th width="100">状态</th>
        <th class="al" width="120">提交时间</th>
        <th class="al" width="">操作</th>
    </tr>
<?php foreach ($list as $key=>$val) :?>
	<tr>
		<td class="ac"><?php echo $val->hcode;?></td>
		<td class="ac"><?php echo $val->integral;?></td>
		<td class="ac"><?php echo $val->use_nums;?></td>
		<td class="ac"><?php echo $val->stateText;?></td>
		<td><?php echo $val->shortCreateDateTimeText;?></td>
		<td>
			<?php echo l('修改', url('admin/inviterhidecode/create', array('id'=>$val->id)));?>
			<?php echo l('删除', url('admin/inviterhidecode/delete', array('id'=>$val->id)), array('onclick'=>"return confirm('确定要删除吗？');"));?>
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
<?php else:?>
<div>目前列表</div>
<?php endif;?>