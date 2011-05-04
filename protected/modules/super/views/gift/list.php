<?php if ($gift) :?>
<table  class="tabcolor list-tbl ma-b5px" width="100%" cellspacing="1">
    <tr class="title f14px">
        <th class="al" width="300">礼品名</th>
        <th class="al" width="70">礼品图片</th>
        <th class="al" width="60"><?php echo $sort->link('integral', '兑换积分');?></th>
        <th class="al" width="140">添加时间</th>
        <th class="al" width="50"><?php echo $sort->link('state', '状态');?></th>
        <th class="al">操作</th>
    </tr>
<?php foreach ((array)$gift as $key=>$val) :?>
	<tr>
		<td><?php echo $val->getNameLinkHtml('_blank')?></td>
		<td><?php echo $val->smallPicHtml?></td>
		<td><?php echo $val->integral?></td>
		<td><?php echo $val->shortCreateDateTimeText?></td>
		<td><?php echo $val->statusText?></td>
		<td><a href="<?php echo url('super/gift/create', array('id'=>$val->id))?>"><span class="color">编辑</span></a>
		<?php if ($val->state == STATE_ENABLED):?>
		<a href="<?php echo url('super/gift/state', array('id'=>$val->id, 'state'=>STATE_DISABLED))?>" onclick="return confirm('确定要设置售完吗？');"><span class="color">售完</span></a>
		<?php else:?>
		<a href="<?php echo url('super/gift/state', array('id'=>$val->id, 'state'=>STATE_ENABLED))?>" onclick="return confirm('确定要设置有货吗？');"><span class="color">有货</span></a>
		<?php endif;?>
		<a href="<?php echo url('super/gift/delete', array('id'=>$val->id))?>" onclick="return confirm('确定要删除吗？');"><span class="color">删除</span></a>
		</td>
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
<div>没有礼品</div>
<?php endif;?>
<?php echo user()->getFlash('errorSummary');?>