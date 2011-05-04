<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th class="al">行政区域名称</th>
        <th width="200">地图坐标</th>
        <th width="80">操作</th>
    </tr>
<?php if ($district):?>
<?php foreach ((array)$district as $b) :?>
	<tr>
		<td class="ar"><?php echo $b->id;?>.</td>
		<td><?php echo $b->name;?></td>
		<td class="ac"><?php echo $b->map_x;?> , <?php echo $b->map_y;?></td>
		<td class="ac">
			<a href="<?php echo url('admin/district/edit', array('id'=>$b->id));?>">修改</a>
		 	<a href="<?php echo url('admin/district/delete', array('id'=>$b->id));?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="7" class="ac">没有行政区域信息</td>
	</tr>
<?php endif;?>
</table>