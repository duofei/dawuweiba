<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th width="40">ID</th>
        <th width="70">编号</th>
        <th width="100">手机卡号</th>
        <th width="50">状态1</th>
        <th width="50">状态2</th>
        <th width="200">绑定商家</th>
        <th width="110">联系方式</th>
        <th width="50">操作</th>
        <th width="120">最后请求</th>
        <th>备注</th>
        <th width="100">重启</th>
    </tr>
<?php if ($printers):?>
<?php foreach ((array)$printers as $v):?>
	<tr>
		<td class="ac"><?php echo $v->id;?></td>
		<td class="ac"><?php echo $v->code;?></td>
		<td class="ac"><?php echo $v->phone;?></td>
		<td class="ac"><?php echo $v->orderStateHtml;?></td>
		<td class="ac"><?php echo $v->stateHtml;?></td>
		<td class="al">
		<?php echo $v->shop ?
		    $v->shop->getNameLinkHtml(0, '_blank') . '[<a href="' . url('admin/printer/shop', array('pid'=>$v->id)) . '">修改</a>]' :
		    '<a href="' . url('admin/printer/shop', array('pid'=>$v->id)) . '">关联店铺</a>';
		?>
		</td>
		<td class="al"><?php echo $v->shop->telphone; ?></td>
		<td class="ac">
			<a href="<?php echo url('admin/printer/create', array('id'=>$v->id));?>">修改</a>
		</td>
		<td class="ac"><?php echo $v->lastTimeText;?></td>
		<td><?php echo $v->remark;?></td>
		<td>
			<a href="<?php echo url('admin/printer/restartlog', array('code'=>$v->code));?>" target="_blank">日志</a>
			<input type="button" value="重启" class="restart" no="<?php echo $v->code;?>" />
		</td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan="11" class="ac">暂无打印机信息</td>
	</tr>
<?php endif;?>
</table>

<script type="text/javascript">
$(function(){
	$('.restart').click(printer_restart);
});

function printer_restart(event)
{
	var tthis = $(this);
	var no = $(this).attr('no');
	var url = '<?php echo aurl('admin/printer/restart');?>?no=' + no;
	$.post(url, function(data){
		tthis.val(data);
	});
}

</script>