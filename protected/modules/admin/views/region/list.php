<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th class="al">地图区域名称</th>
        <th>地图坐标</th>
        <th width="200">添加时间</th>
        <th width="80">操作</th>
    </tr>
<?php if ($region):?>
<?php foreach ((array)$region as $r) :?>
	<tr>
		<td class="ar"><?php echo $r->id;?>.</td>
		<td><?php echo $r->name;?></td>
		<td class="ac"><?php echo mb_substr($r->region, 0, 100). '...';?></td>
		<td class="ac"><?php echo $r->shortCreateDateTimeText;?></td>
		<td class="ac">
			<a href="<?php echo url('admin/region/edit', array('id'=>$r->id));?>">修改</a>
		 	<a href="<?php echo url('admin/region/delete', array('id'=>$r->id));?>" onclick="return confirm('确定要删除吗？');">删除</a>
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="7" class="ac">没有地图区域信息</td>
	</tr>
<?php endif;?>
</table>