<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
body{font-size:12px; margin:0px;}
table {border-collapse:collapse; border:none; width:100%}
.bline{border-bottom:1px dashed #000;}
.tline{border-top:1px dashed #000;}
.ar{text-align:right;}
.fb{font-weight:bold;}
.ma-t5px {margin-top:5px;}
</style>
</head>
<body>
<div>订单号：<?php echo $order->orderSn;?></div>
<div><?php echo $order->shop->shop_name;?></div>
<table class="tline bline">
	<tr>
		<td>商品名称</td><td width="25" class="ar">数量</td><td width="25" class="ar">单价</td><td width="25" class="ar">小计</td>
	</tr>
	<?php foreach ($order->orderGoods as $og):?>
	<tr>
		<td><?php echo $og->goods_name;?></td>
		<td class="ar"><?php echo $og->goodsPrice;?></td>
		<td class="ar"><?php echo $og->goods_nums;?></td>
		<td class="ar"><?php echo $og->goodsAmount;?></td>
	</tr>
	<?php endforeach;?>
	<tr><td colspan="4" class="ar">总计：<span class="fb"><?php echo $order->amountPrice;?></span>元<!-- &nbsp;&nbsp;应收金额：<?php echo $order->amountPrice;?> --></td></tr>
</table>
<div class="ma-t5px"><?php echo $order->consignee;?>&nbsp;&nbsp;<?php echo $order->telphone;?></div>
<div class="bline"><?php echo $order->address;?></div>
<?php if($order->deliver_time):?>
<div>送餐时间：<?php echo $order->deliver_time;?></div>
<?php endif;?>
<?php if($order->message):?>
<div>订单备注：<?php echo trim($order->message);?></div>
<?php endif;?>
<br />
<div class="ar"><a href="javascript:window.print()">打印</a>&nbsp;&nbsp;<a href="javascript:window.close()">关闭</a></div>
<br />
<div class="tline"></div>
</body>
</html>