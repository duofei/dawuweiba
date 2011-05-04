<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th width="100">城市</th>
        <th class="al" width="300">地址名称</th>
        <th width="200">地图坐标</th>
        <th class="al">详细地址</th>
        <th width="40">操作</th>
    </tr>
<?php if ($location):?>
<?php foreach ($location as $b) :?>
	<tr>
		<td class="ar"><?php echo $b->id;?>.</td>
		<td class="ac"><?php echo $b->city->name;?></td>
		<td><?php echo $b->name;?></td>
		<td class="ac"><?php echo $b->map_x;?> , <?php echo $b->map_y;?></td>
		<td><?php echo $b->address;?></td>
		<td class="ac">
		 	<a href="<?php echo url('super/location/delete', array('id'=>$b->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
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