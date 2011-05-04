<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th class="al" width="300">地址名称</th>
        <th width="200">地图坐标</th>
        <th class="al">详细地址</th>
        <th width="60">来源</th>
        <th width="120">添加时间</th>
        <th width="80">操作</th>
    </tr>
<?php if ($location):?>
<?php foreach ((array)$location as $b) :?>
	<tr>
		<td class="ar"><?php echo $b->id;?>.</td>
		<td><?php echo $b->name;?></td>
		<td class="ac"><?php echo $b->map_x;?> , <?php echo $b->map_y;?></td>
		<td><?php echo $b->address;?></td>
		<td class="ac"><?php echo $b->sourceText;?></td>
		<td class="ac"><?php echo $b->shortCreateDateTimeText;?></td>
		<td class="ac">
			<a href="<?php echo url('admin/location/edit', array('id'=>$b->id, 'verify'=>1))?>">审核</a>
		 	<a href="<?php echo url('admin/location/delete', array('id'=>$b->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="7" class="ac">没有未审核地址信息</td>
	</tr>
<?php endif;?>
</table>
<div class="pages ar">
<?php $this->widget('CLinkPager', array(
	'pages' => $pages,
    'header' => '',
    'firstPageLabel' => '首页',
    'lastPageLabel' => '末页',
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页',
));?>
</div>