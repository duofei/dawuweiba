<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
    	<th width="70">城市名称</th>
        <th width="70">管理人员</th>
        <th width="100">管理角色</th>
        <th>操作</th>
    </tr>
<?php foreach($managers as $m): ?>
	<tr>
		<td class="ac"><?php echo $citys[$m->manage_city_id];?></td>
		<td class="ac"><?php echo $m->username;?></td>
		<td class="ac"><?php echo $roles[$m->id]->description;?></td>
		<td class="ac">
			<a href="<?php echo url('super/cityadmin/removeManager', array('id'=>$m->id));?>" onclick="return confirm('确定要取消管理权限操作吗？');">取消管理权限</a>
		</td>
	</tr>
<?php endforeach;?>
</table>