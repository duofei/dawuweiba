<div class=" border-aaa pa-20px ma-b20px ">
	<div class="fl order-ok " ></div>
    <div class="fl ma-l20px ma-t10px w860px ">
    	<h3 class="f16px">您的订单支付成功！</h3>
        <p class="ma-t5px bline cgray lh30px">您的订单号：<?php echo $order->orderSn?>，付款金额：<?php echo $order->amountPrice?>元。</p>
        <ul class="ma-t10px lh24px">
            <li><span class="cgray">我要到个人中心查看</span><?php echo l('我的订单',url('my/order/list'))?>。</li>
            <li><span class="cgray">我要返回商铺</span><?php echo l('继续购物', url('shop/show', array('shopid'=>$order->shop_id)))?>。</li>
        </ul>
    </div>
    <div class="clear"></div>
</div>