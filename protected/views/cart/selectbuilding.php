<div class="pa-10px conflict" style="width:610px">
	<div class="cf60 lh30px open f14px groupon-message">同楼订餐说明</div>
    <ul class="lh24px cgray none about ma-t10px">
     	<li><span class="fb">同楼订餐：</span>是指同一座楼上的网友在规定的时间段内，共同在同一家商铺订餐。目前同楼订餐只支持中午订餐。</li>
       	<li><span class="fb">达成条件：</span>同楼所有网友的订单总额达到商铺的规定的价格，就能享受同楼订餐服务。<br />
                    	<span class="fb">　　　　　</span>若未达到只能享受普通订餐服务。</li>
        <li><span class="fb">截止时间：</span>每天上午10：00，可以提前一天预订。10点以后下的订单，第二天中午才能收到。</li>
		<li><span class="fb">送餐时间：</span>每天中午11：30-12：00。</li>
		<li><span class="fb">优点：</span>价格比普通订餐便宜，商铺送餐及时、准时。</li>
	</ul>
    <p class="ma-t5px">
    	<span class="cgray f14px ma-t10px">您要参加同楼订餐请先选择该商铺所支持的楼宇：</span>
    	<div class="building-list">
    	<ul>
    		<?php foreach ((array)$buildings as $b):?>
    		<li class="cf60"><a href="<?php echo url('at/setLocation',array('atid'=>$b->id)) . '?referer=' . $referer;?>"><?php echo $b->name;?></a><li>
    		<?php endforeach;?>
    		<div class="clear"></div>
    	</ul>
    	<div class="clear"></div>
    	</div>
    </p>
  	
  	<p class="ma-t10px">
    	<div class="ma-t5px cgray f14px fl">如果没有您所在的楼宇，请按普通方式订餐 &nbsp;</div>
    	<a class="button-yellow" href="javascript:void(0);" onclick="$.colorbox.close();"><span>普通订餐</span></a>
    </p>
</div>
<script type="text/javascript">
$(function(){
    $(".open").click(function(){
		$(".about").toggle();
	});
});
</script>