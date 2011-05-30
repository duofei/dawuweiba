<div id="yw0" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
	  <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo url("shopcp/voucher/list");?>">优惠券列表</a></li>
	  <li class="ui-state-default ui-corner-top"><a href="<?php echo url("shopcp/voucher/create");?>">添加优惠券</a></li>
	</ul>
    <div id="index" class="ui-tabs-panel">
		<table class="lh30px tabcolor list-tbl" width="100%">
			<tr class="title">
				<th width="27" class="ar">ID</th>
				<th class="al">商品名称</th>
				<th width="80">原价格</th>
				<th width="80">优惠价格</th>
				<th>有限时间</th>
				<th>操作</th>
			</tr>
			<?php if($vouchers):?>
			<?php foreach ((array)$vouchers as $v):?>
		    <tr class="ac">
		        <td class="ar"><?php echo $v->id;?>.</td>
		        <td class="al"><?php echo $v->goods->name;?></td>
		        <td>￥<?php echo $v->goods->wmPrice;?></td>
		        <td>￥<?php echo $v->price + 0;?></td>
		        <td><?php echo date('Y-m-d', $v->end_time);?></td>
		        <td>
		        	<?php if($v->end_time > time()):?>
		        	<a href="<?php echo sbu($v->img);?>" target="_blank">查看优惠券</a>
		        	<?php else:?>
		        	已过期
		        	<?php endif;?>
		        	<?php echo l('删除', url('shopcp/voucher/delete', array('id'=>$v->id)), array('onclick'=>'return confirm("确定要删除吗？");'));?>
		        </td>
			</tr>
			<?php endforeach;?>
			<?php else:?>
			<tr><td colspan="6" class="ac">您还没有添加优惠券，现在<?php echo l('添加', url('shopcp/voucher/create'));?></td></tr>
			<?php endif;?>
		</table>
  	</div>
</div>
