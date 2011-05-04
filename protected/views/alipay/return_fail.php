<div class=" border-aaa pa-20px ma-b20px ">
	<div class="fl order-error"></div>
    <div class="fl w860px ">
    	<h3 class="f16px lh30px pa-l20px">您的订单支付失败！</h3>
    	<p class="bline cgray lh24px ma-l20px">可能是您的订单已过期或操作错误造成支付失败！</p>
    	<ul class="ma-t10px lh24px ma-l20px">
            <li><span class="cgray">我要到个人中心查看</span><?php echo l('我的订单',url('my/order/list'))?>。</li>
            <li><span class="cgray">我要返回首页</span><?php echo l('继续购物', app()->homeUrl)?>。</li>
        </ul>
    </div>
    <div class="clear"></div>
</div>