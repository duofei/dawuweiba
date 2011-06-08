<style>
.list-tbl {border-left:1px solid #eee}
.listtr td{border-bottom:1px solid #eee; line-height:20px; padding:3px 0px;}
.listbg td{background:#f8f8f8;}
</style>
<table class="tabcolor list-tbl ma-b5px " width="100%" cellspacing="0">
    <tr class="title">
        <th width="100">订单号</th>
        <th class="al">商铺详情</th>
        <th class="al">收货人/地址</th>
        <th width="110" class="al">电话号码</th>
        <th width="90" class="al">订单价格</th>
        <th width="120">时间/IP</th>
        <th width="120">订单状态</th>
        <th width="60">操作</th>
    </tr>
<?php if ($orderlist) :?>
<?php foreach ($orderlist as $k=>$v):?>
    <tr class="listtr <?php echo $k%2 ? 'listbg':'';?>">
        <td class="ac" style="font-family: Arial, Helvetica, sans-serif;"><span class="cgray"><?php echo $v->order_sn;?></span><span class="f16px"><?php echo $v->id;?></span></td>
        <td>
        	<?php echo $v->shop->shop_name;?> (<?php echo $v->shop->telphone;?>) <br />
        	<?php echo $v->shop->address;?>
        </td>
        <td>
        	<?php echo l($v->consignee, url('admin/user/info', array('id'=>$v->user_id)));?> <br />
        	<?php echo $v->address;?>
        </td>
        <td><?php echo $v->telphone;?><br /><?php echo $v->mobile;?></td>
        <td>
        	总　价:<span class="">&yen;<?php echo $v->amountPrice;?></span><br />
        	应收款:<span class="">&yen;<?php echo $v->dueAmountPrice;?></span>
        </td>
        <td><?php echo $v->shortCreateDateTimeText;?><br/><?php echo $v->create_ip;?></td>
        <td class="ac <?php if($v->status==Order::STATUS_UNDISPOSED) echo 'cred'; elseif ($v->status==Order::STATUS_COMPLETE) echo 'cgreen'; else echo 'cgray';?>">
        	<?php echo Order::$phoneStates[$v->status];?> <br />
        	<?php if($v->status == Order::STATUS_COMPLETE):?>
				预计送达 <?php echo $v->deliver_time;?>
			<?php elseif($v->status == Order::STATUS_CANCEL):?>
				<?php echo $v->cancel_reason;?>
			<?php endif;?>
        </td>
        <td class="ac">
        	<?php if($v->status == Order::STATUS_UNDISPOSED):?>
        	<?php echo l('处理订单', url('admin/order/phoneorderdeal', array('id'=>$v->id)));?>
        	<?php else: ?>
        	<span class="cgray"><?php echo l('查看详情', url('admin/order/phoneorderdeal', array('id'=>$v->id)));?></span>
        	<?php endif;?>
        </td>
    </tr>
<?php endforeach;?>
<?php else: ?>
	<tr><td colspan="8">暂无列表</td></tr>
<?php endif;?>
	<tr><td class="pages ar" colspan="8">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
	    'header' => '',
	    'firstPageLabel' => '首页',
	    'lastPageLabel' => '末页',
	    'nextPageLabel' => '下一页',
	    'prevPageLabel' => '上一页',
	));?>
	</td></tr>
</table>