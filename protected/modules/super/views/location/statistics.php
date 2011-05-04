<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th>城市</th>
        <th>已审核地址</th>
        <th>待审核地址</th>
        <th>总地址数量</th>
    </tr>
<?php if ($data):?>
<?php foreach ($data as $city_id=>$location) :?>
	<tr>
		<td class="ac"><?php echo $citys[$city_id];?></td>
		<td class="ac"><?php echo intval($location['enable']);?></td>
		<td class="ac"><?php echo intval($location['disable']);?></td>
		<td class="ac"><?php echo intval($location['count']);?></td>
	</tr>
<?php endforeach;?>
<?php endif;?>
</table>