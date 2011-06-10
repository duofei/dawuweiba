<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th class="al" width="200">楼宇名称</th>
        <th width="80">楼宇类型</th>
        <th width="80">行政区域</th>
        <th width="200">地图坐标</th>
        <th class="al">详细地址</th>
        <th width="40">操作</th>
    </tr>
<?php if ($building):?>
<?php foreach ($building as $b) :?>
	<tr>
		<td class="ar"><?php echo $b->id;?>.</td>
		<td><?php echo $b->name;?></td>
		<td class="ac"><?php echo $b->typeText;?></td>
		<td class="ac"><?php echo $b->district->city->name;?> <?php echo $b->district->name;?></td>
		<td class="ac"><?php echo $b->map_x;?> , <?php echo $b->map_y;?></td>
		<td><?php echo $b->address;?></td>
		<td class="ac">
		 	<a href="<?php echo url('super/building/delete', array('id'=>$b->id))?>" onclick="return confirm('确定要删除吗？');">删除</a>
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