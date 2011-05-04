<?php echo CHtml::form('#', 'post', array('name'=>'theform'));?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
		<th class="al" width="60">操作</th>
		<th class="al" width="60">用户组</th>
		<th class="al" width="60">描述</th>
		<th class="al" width="60">bizRule</th>
		<th class="al" width="60">Data</th>
	</tr>
	<?php foreach ($roles as $k => $v): ?>
	<tr>
		<td >
			<?php echo l(CHtml::image(resBu('admin/images/edit-icon.gif')), url('admin/user/roleedit', array('uid'=>$v->name)), array('class'=>'delete'));?>
		</td>
		<td><?php echo $v->name;?></td>
		<td ><?php echo $v->description;?></td>
		<td ><?php echo $v->bizRule;?></td>
		<td><?php echo $v->data;?></td>
	</tr>
	<?php endforeach;?>
</table>
<?php echo CHtml::endForm();?>

<script type="text/javascript">
/*<![CDATA[*/
$(function(){
	$('.user-list tr').mouseover(trMouseOver);
	$('.user-list tr').mouseout(trMouseOut);
});
/*]]>*/
</script>

