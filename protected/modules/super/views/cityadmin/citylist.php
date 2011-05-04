<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">Id</th>
        <th class="al">城市名称</th>
        <th width="200">地图坐标</th>
        <th width="110">操作</th>
    </tr>
<?php if ($city):?>
<?php foreach ($city as $c) :?>
	<tr>
		<td class="ac"><?php echo $c->id;?>.</td>
		<td><?php echo $c->name;?></td>
		<td class="ac"><?php echo $c->map_x;?> , <?php echo $c->map_y;?></td>
		<td class="ac">
			<?php echo l('修改', url('super/cityadmin/addcity', array('id'=>$c->id)));?>
			<?php echo l('管理员人员', url('super/cityadmin/addcity', array('id'=>$c->id)));?>
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