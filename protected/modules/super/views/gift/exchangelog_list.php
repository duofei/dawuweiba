<?php if ($data) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="200">礼品名</th>
        <th class="al" width="100">用户</th>
        <th width="130">兑换时间</th>
        <th class="al" width="300">联系人资料</th>
        <th width="50">状态</th>
        <th class="al">备注</th>
    </tr>
<?php foreach ((array)$data as $key=>$val) :?>
	<tr>
		<td class="exchange-state<?php echo $val->state;?>"><?php echo $val->gift->getNameLinkHtml('_blank')?></td>
		<td><?php echo $val->user->username;?></td>
		<td class="ac"><?php echo $val->shortCreateDateTimeText;?></td>
		<td><?php echo $val->consignee;?><br /><?php echo $val->address?><br /><?php echo $val->telphone?>, <?php echo $val->mobile?></td>
		<td class="ac"><?php echo $val->stateText?><br /><?php echo l('处理兑换', url('super/gift/editlog', array('giftid'=>$val->id)));?></td>
		<td><?php echo h($val->message);?></td>
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
<div>没有人兑换礼品</div>
<?php endif;?>
<?php echo user()->getFlash('errorSummary');?>