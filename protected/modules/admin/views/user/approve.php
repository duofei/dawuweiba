<?php if ($user) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al">用户名</th>
        <th class="al" width="150">注册时间</th>
        <th class="al" width="150">最后登录时间</th>
        <th class="al" width="130">最后订单时间</th>
        <th class="al" width="110">成功订单数量</th>
        <th class="al" width="50">状态</th>
        <th class="al" width="50">操作</th>
    </tr>
<?php foreach ($user as $key=>$val) :?>
	<tr>
		<td><a href="<?php echo url('admin/user/info', array('id'=>$val->id))?>"><?php echo $val->username?></a></td>
		<td><?php echo $val->createTimeText?></td>
		<td><?php echo $val->lastLoginTimeText?></td>
		<td><?php echo $val->orders[0]->shortCreateDateTimeText;?></td>
		<td><?php echo $val->orderPrinterCompleteCount;?></td>
		<td><?php echo $val->stateText?></td>
		<td>
		<a href="<?php echo url('admin/user/info', array('id'=>$val->id))?>"><span class="color">查看</span></a>
		</td>
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
<div>目前没有用户</div>
<?php endif;?>