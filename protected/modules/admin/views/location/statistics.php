<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th>城市名称</th>
        <th>已通过</th>
        <th>待审核</th>
    </tr>
<?php foreach ((array)$locations as $c=>$l) :?>
	<tr>
		<td class="ac"><?php echo $citys[$c];?></td>
		<td class="ac"><?php echo $l['enabled'];?></td>
		<td class="ac"><a href="<?php echo url('admin/location/unverify');?>"><?php echo $l['disabled'];?></a></td>
	</tr>
<?php endforeach;?>
</table>