<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th>名称</th>
        <th>已审核楼宇</th>
        <th>待审核楼宇</th>
        <th>总楼宇数量</th>
    </tr>
<?php if ($data):?>
<?php foreach ($data as $city_id=>$district) :?>
	<tr><td colspan="4"><?php echo $citys[$city_id];?></td></tr>
	<?php foreach ($district as $district_id=>$building):?>
	<tr>
		<td class="ac"><?php echo $districts[$district_id];?></td>
		<td class="ac"><?php echo intval($building['enable']);?></td>
		<td class="ac"><?php echo intval($building['disable']);?></td>
		<td class="ac"><?php echo intval($building['count']);?></td>
	</tr>
	<?php endforeach;?>
<?php endforeach;?>
<?php endif;?>
</table>