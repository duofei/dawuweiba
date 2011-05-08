<div class="pa-1px border-aaa">
	<h4 class="f14px cwhite pa-l20px lh30px bg-7a">
		<span class="ma-r20px">订单提交成功!</span>
		<span class="ma-r20px">订单号:<?php echo $order->orderSn;?></span>餐厅名称：<?php echo $order->shop->nameLinkHtml;?>
	</h4>
	<div class="pa-t10px pa-l20px pa-r20px pa-b10px">
	<table width="100%" class="tab-padding bg-color">
		<tr class="f14px">
			<td width="70%" class="indent10px">商品名称</td>
			<td width="10%" class="ar">单价</td>
          	<td width="10%" class="ar">数量</td>
         	<td width="10%" class="ar">总价</td>
      	</tr>
		<?php foreach((array)$order->orderGoods as $ordergoods):?>
		<tr>
			<td class="indent10px"><?php echo $ordergoods->goods_name;?></td>
			<td class="ar"><?php echo $ordergoods->goodsPrice;?>元</td>
			<td class="ar"><?php echo $ordergoods->goods_nums;?>份</td>
			<td class="ar"><?php echo $ordergoods->goodsAmount;?>元</td>
		</tr>
   	 	<?php endforeach;?>
    </table>
	<table width="100%" class="tab-padding tline">
      	<tr>
        	<td class="indent10px f14px"><?php echo $order->address;?>，<?php echo $order->telphone;?></td>
           	<td class="f14px cred ar">订单总价：<?php echo $order->amountPrice;?>元 &nbsp;&nbsp;
           	<?php if($order->groupon_id):?>
           	同楼订餐价：<?php echo $order->groupAmountPrice;?>元 &nbsp;&nbsp;
           	<?php endif;?>
           	已付款：<?php echo $order->paidAmountPrice;?>元 &nbsp;&nbsp;
           	应付款：<?php echo $order->dueAmountPrice;?>元 &nbsp;&nbsp;
           	</td>
       	</tr>
 	</table>
 	</div>
</div>
<div class="ma-t10px ma-b10px border-aaa pa-l30px pa-r20px pa-t20px pa-b20px ">
	<h4 class="f16px fl lh30px ma-r10px" >订单提交成功</h4>
	<div class="fl order-ok"></div>
	<div class="clear"></div>
	<p class="f14px lh24px cgray" >请您留意您的手机：<span class="cred"><?php echo $order->telphone;?></span>。大约2-3分钟左右收到商铺的确认短信。 </p>
	<p class="f14px lh24px cgray" >如果长时间未收到短信请致电<span class="cred">(<?php echo $order->shop->nameLinkHtml;?>)<?php echo $order->shop->telphone;?></span>或登陆<span class="cred"><?php echo l('个人中心', url('my/order/online'));?></span>查看订单状态.<p/>
</div>
<div class="ma-t10px ma-b10px border-aaa pa-l30px pa-r20px pa-b20px ">
<?php if(user()->isGuest):?>
 	<h4 class="f14px ma-t20px"> 只有登陆用户才送积分！ <?php echo l('马上去注册',url('signup'));?></h4>
<?php else:?>
	<div class="bg-pic fl order-lable-img"></div>
    <div class="fl pa-l20px order-lable">
            <h2 class="color999 f14px" >订餐结束了别忘了<span class="cff9900"><?php echo l('点评美食', url('my/order/norating'));?></span></h2>
            <h2 class="color999 f14px">点评送<span class="cff9900">积分</span>，积分换<span class="cff9900"><a href="<?php echo aurl('gift');?>">大礼</a></span></h2>
            <h4 class="f14px ma-t20px">您还有<?php echo User::getUserNoRatingNums(user()->id);?>个订单没有完成点评，点评订单可以获取更多的积分，<span class="cred"><?php echo l('现在就去点评',url('my/order/norating'));?></span>。</h4>
            <h2><span class="f20px cff9900">恭喜您获得订餐积分！</span><span class="f14px cgray">积分可以获得<span class="cred"><?php echo l('更多礼品',url('gift'));?></span></span></h2>
 	</div>
 	<div class="clear"></div>
   	<h4 class="f14px">积分从<?php echo $integral['lastintegral'];?>分增加到了<?php echo $integral['user_integral'];?>分</h4>
   	<div class="integral-border">
    	<div class="integral-line"></div>
   	</div>
 	<h4 class="relative"><?php echo $integral['min_integral'];?><span class="absolute" style="left:430px;"><?php echo $integral['mid_integral'];?></span><span class="absolute" style="right:1px;"><?php echo $integral['max_integral'];?></span></h4>
 	<script type="text/javascript">
 		$(function(){
			$(".integral-line").animate({width:'535px'},2000);
 	 	});
 	</script>
<?php endif;?>
</div>