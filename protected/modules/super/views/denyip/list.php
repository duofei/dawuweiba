<?php echo CHtml::form('#', 'post', array('name'=>'theform'));?>
<table border="0" cellspacing="1" cellpadding="0" width="100%" class="list-tbl denyip-list">
	<tr>
		<th width="50"><?php echo $sort->link('id')?></th>
		<th width="130"><?php echo $sort->link('ip_start')?></th>
		<th width="130"><?php echo $sort->link('ip_end')?></th>
		<th width="80"><?php echo $sort->link('type')?></th>
		<th width="150"><?php echo $sort->link('user_id')?></th>
		<th width="50">操作</th>
		<th width="150"><?php echo $sort->link('create_time')?></th>
		<th width="130">操作IP</th>
		<th>&nbsp;</th>
	</tr>
	<?php foreach ($ips as $v):?>
	<tr>
		<td class="ac""><?php echo CHtml::label($v->id, 'ip' . $v->id);?></td>
		<td><?php echo $v->startIp;?></td>
		<td><?php echo $v->endIp;?></td>
		<td><?php echo $v->typeText;?></td>
		<td class="ac"><?php echo $v->user->username;?></td>
		<td class="ac">
			<?php echo l(CHtml::image(resBu('admin/images/del-icon.gif')), url('super/denyip/delete', array('ipid'=>$v->id)), array('class'=>'delete'));?>
		</td>
		<td class="cgray"><?php echo $v->createDateTimeText;?></td>
		<td class="cgray"><?php echo $v->create_ip;?></td>
		<td>&nbsp;</td>
	</tr>
	<?php endforeach;?>
</table>
<?php echo CHtml::endForm();?>

<div class="pages fr">
<?php $this->widget('CLinkPager', array(
	'pages' => $pages,
    'header' => '翻页',
    'firstPageLabel' => '首页',
    'lastPageLabel' => '末页',
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页',
));?>
</div>
<script type="text/javascript">
/*<![CDATA[*/
$(function(){
	$('.delete').click(deleteOneRecord);
	$('.list-tbl tr').mouseover(trMouseOver);
	$('.list-tbl tr').mouseout(trMouseOut);
	$('#selectall').click(selectAll);
	$('#inverse').click(selectInverse);
});
/*]]>*/
</script>

