<style>
.list-tbl {border:1px solid #eee}
.listbg td{background:#f8f8f8;}
.list_tline td{border-top: 1px solid #eee;}
.title td{background:#efefef;}
.cancel-reason span, .deliver-time span, .shop-remark span {border:1px solid #cccccc; padding:3px 5px; margin-left:5px; cursor:pointer;}
</style>
<table class="tabcolor list-tbl " width="100%" cellspacing="0">
    <tr class="title lh20px">
    	<td class="bline"><span class="fb">商铺详情</span> &nbsp;&nbsp;商铺名称：<?php echo $order->shop->shop_name;?></td>
    	<td class="bline">商铺电话：<?php echo $order->shop->telphone;?></td>
    	<td colspan="2" class="bline">商铺地址：<?php echo $order->shop->address;?></td>
    </tr>
    <tr class="title lh20px">
    	<td width="220"><span class="fb">订单详情</span> &nbsp;&nbsp;订单号：<?php echo $order->orderSn;?></td>
    	<td width="200">收货人：<?php echo $order->consignee;?></td>
    	<td width="280">联系电话：<?php echo $order->telphone;?> <?php echo $order->mobile;?></td>
    	<td>收货地址：<?php echo $order->address;?></td>
    </tr>
    <tr>
    	<td colspan="3" align="center">
    		<table width="90%">
    			<?php foreach ($order->orderGoods as $v):?>
    			<tr>
    				<td><?php echo $v->goods_name;?></td>
    				<td><?php echo $v->goods_nums;?> X <?php echo $v->goodsPrice;?>元</td>
    				<td><?php echo $v->goodsAmount?>元</td>
    			</tr>
    			<?php endforeach;?>
    		</table>
    	</td>
    	<td class="lh24px">
    		<div class="fl pa-l20px" style="border-left:1px solid #cccccc;">
	    		<span>配送费：<?php echo $order->dispatchingAmountPrice;?>元</span><br />
	    		<span>已付款：<?php echo $order->paidAmountPrice;?>元</span>
    		</div>
    		<div class="fl ma-l30px cred">
	    		<span>订单总计：<?php echo $order->amountPrice;?>元</span><br />
	    		<span>应收款：<?php echo $order->dueAmountPrice;?>元</span>
    		</div>
    		<div class="clear"></div>
    	</td>
    </tr>
    <tr class="title lh20px">
    	<td>下单时间：<?php echo $order->shortCreateDateTimeText;?></td>
    	<td>下单IP：<?php echo $order->create_ip;?>(<?php echo $order->createIpCityText;?>)</td>
    	<td class="<?php if($order->status==Order::STATUS_UNDISPOSED) echo 'cred'; elseif ($order->status==Order::STATUS_COMPLETE) echo 'cgreen'; else echo 'cgray';?>">订单状态：<?php echo $order->statusText;?></td>
    	<td>订单备注：<?php echo $order->message;?></td>
    </tr>
</table>

<?php if($order->status==Order::STATUS_UNDISPOSED):?>
<!-- 订单处理 -->
<?php echo CHtml::beginForm(url('admin/order/phoneorderpost'), 'post');?>
<table class="tabcolor list-tbl ma-t10px " width="100%" cellspacing="0">
    <tr class="title lh20px">
    	<td width="80" class="ar fb">订单处理：</td>
    	<td colspan="2">
    		<input type="radio" name="status" value="3" checked /> 确认订单 &nbsp;&nbsp;&nbsp;&nbsp;
    		<input type="radio" name="status" value="4" /> 取消订单
    	</td>
    </tr>
    <tr id="complete">
    	<td class="ar">预计送达：</td>
    	<td width="100"><input type="text" class="txt" name="deliver_time" style="width:95%;" /></td>
    	<td class="deliver-time">
    		<?php for($i=15; $i<120; $i=$i+15):?>
    		<span><?php echo date("H:i", time()+$i*60);?></span>
    		<?php endfor;?>
    	</td>
    </tr>
    <tr id="cancel" class="none">
    	<td class="ar">取消理由：</td>
    	<td width="100"><input type="text" class="txt" name="cancel_reason" style="width:95%;" /></td>
    	<td class="cancel-reason"><span>人手不足</span><span>菜品脱销</span><span>距离过远</span><span>无效订单</span></td>
    </tr>
    <tr>
    	<td class="ar">发送短信：</td>
    	<td colspan="2">
    		<?php if(SendSms::filter_mobile($order->telphone)):?>
    		<input type="checkbox" name="sendsms" value="1" checked />
    		<?php else :?>
    		<input type="checkbox" name="sendsms" value="1" disabled />
    		<?php endif;?>
    		&nbsp;&nbsp;
    		<span id="send_sms_content_complete"><?php echo '您在' . trim($order->shop->shop_name) . '定的外卖预计' . '<span class="fb">####</span>' . '送达，请保持电话畅通，单号：' . $order->orderSn . '，http://52wm.com';?></span>
    		<span id="send_sms_content_cancel" class="none"><?php echo '很抱歉，您在我爱外卖网下的订单'. $order->orderSn .'已被商家取消，取消原因:'. '<span class="fb">####</span>' .'。如有疑问请致电商铺：' . $order->shop->telphone;?></span>
    	</td>
    </tr>
    <tr class="listbg">
    	<td></td>
    	<td colspan="2">
    		<input type="hidden" value="<?php echo $order->id;?>" name="order_id" />
    		<input type="hidden" value="" name="sms_content" />
    		<input type="submit" value=" 处理订单 " name="submit" id="submit_order_deal" />
    	</td>
    </tr>
</table>
<script>
$(function(){
	var status = 3;
	// 快速填写
	$('.cancel-reason span').click(function(){
		var html = $(this).html();
		$('input[name=cancel_reason]').val(html);
		$('#send_sms_content_cancel span').html(html);
	});
	$('.deliver-time span').click(function(){
		var html = $(this).html();
		$('input[name=deliver_time]').val(html);
		$('#send_sms_content_complete span').html(html);
	});
	$('input[name=cancel_reason]').keyup(function(){
		$('#send_sms_content_cancel span').html($(this).val());
	});
	$('input[name=deliver_time]').keyup(function(){
		$('#send_sms_content_complete span').html($(this).val());
	});
	// 确定或取消订单
	$('input[name=status]').click(function(){
		var v = $(this).val();
		if(v==3) {
			$('#cancel').hide();
			$('#complete').show();
			$('#send_sms_content_cancel').hide();
			$('#send_sms_content_complete').show();
			status = 3;
		} else if(v==4) {
			$('#complete').hide();
			$('#cancel').show();
			$('#send_sms_content_cancel').show();
			$('#send_sms_content_complete').hide();
			status = 4;
		}
	});
	// 提交前判断
	$('#submit_order_deal').click(function(){
		var scontent = '';
		if(status == 3) {
			if($('input[name=deliver_time]').val()=='') {
				alert('请填写：预计送达时间!');
				return false;
			}
			scontent = $('#send_sms_content_complete').html();
		} else if(status == 4) {
			if($('input[name=cancel_reason]').val()=='') {
				alert('请填写：订单取消理由!');
				return false;
			}
			scontent = $('#send_sms_content_cancel').html();
		}
		$('input[name=sms_content]').val(scontent);
	});
});
</script>
<?php echo CHtml::endForm();?>
<?php else:?>
<table class="tabcolor list-tbl ma-t10px " width="100%" cellspacing="0">
    <tr class="title lh20px">
    	<td width="80" class="ar fb">订单已处理：</td>
    	<td colspan="2" class="<?php if($order->status==Order::STATUS_UNDISPOSED) echo 'cred'; elseif ($order->status==Order::STATUS_COMPLETE) echo 'cgreen'; else echo 'cgray';?>"><?php echo $order->statusText;?></td>
    </tr>
</table>
<?php endif;?>

<!-- 商铺处理 -->
<?php echo CHtml::beginForm(url('admin/shop/remark'), 'post');?>
<table class="tabcolor list-tbl ma-t10px" width="100%" cellspacing="0">
    <tr class="title lh20px">
    	<td width="80" class="ar fb">商铺处理：</td>
    	<td colspan="2">
    		1. 添加备注&nbsp;&nbsp;&nbsp;&nbsp;
    		2. <?php echo l('管理店铺', url('admin/shop/setSession', array('id'=>$order->shop_id)), array('target'=>'_blank'));?>
    	</td>
    </tr>
    <tr>
    	<td class="ar">已备注信息：</td>
    	<td colspan="2" class="lh20px"><?php echo nl2br($order->shop->remark);?></td>
    </tr>
    <tr>
    	<td class="ar">添加备注：</td>
    	<td width="150"><input type="text" class="txt" name="remark" style="width:95%;" /></td>
    	<td class="shop-remark"><span>电话打不通</span><span>已停机</span><span>人手不足</span><span>距离过远</span></td>
    </tr>
    <tr class="listbg">
    	<td></td>
    	<td colspan="2">
    		<input type="hidden" value="<?php echo $order->shop_id;?>" name="shop_id" />
    		<input type="submit" value=" 添加备注 " name="submit" id="submit_add_shop_remark" />
    	</td>
    </tr>
</table>
<?php echo CHtml::endForm();?>
<script type="text/javascript">
$(function(){
	// 快速填写
	$('.shop-remark span').click(function(){
		var html = $(this).html();
		$('input[name=remark]').val(html);
	});
	// 提交前判断
	$('#submit_add_shop_remark').click(function(){
		if($('input[name=remark]').val()=='') {
			alert('请填写：备注内容!');
			return false;
		}
	});
});
</script>

<!-- 商品处理 -->
<?php echo CHtml::beginForm(url('admin/goods/change'), 'post');?>
<table class="tabcolor list-tbl ma-t10px" width="100%" cellspacing="0">
    <tr class="title lh20px">
    	<td width="80" class="ar fb">商品处理：</td>
    	<td colspan="3">
    		更改价格或下架商品
    	</td>
    </tr>
    <tr class="listbg">
    	<td></td>
    	<td width="150">商品名称</td>
    	<td class="ac" width="80">价格</td>
    	<td>状态</td>
    </tr>
    <?php foreach ($order->orderGoods as $v):?>
    <tr class="list_tline">
    	<td class="ar"><input type="checkbox" name="goodsid[<?php echo $v->goods_id;?>]" /></td>
    	<td><?php echo $v->goods->name;?></td>
    	<td class="ac"><input type="text" name="wmprice[<?php echo $v->goods_id;?>]" value="<?php echo $v->goods->wmPrice;?>" class="txt" style="width:30px" />元</td>
    	<td><?php echo $v->goods->stateText;?></td>
    </tr>
    <?php endforeach;?>
    <tr class="list_tline">
    	<td class="ar"><input type="submit" name="submit_nosell" value=" 下架 " /></td>
    	<td></td>
    	<td class="ac"><input type="submit" name="submit_price" value=" 修改价格 " /></td>
    	<td></td>
    </tr>
</table>
<?php echo CHtml::endForm();?>