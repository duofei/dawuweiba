<?php if(user()->isGuest):?>
<div class="lh30px">没有注册我爱外卖账号？注册后下单可以增加积分，马上 <?php echo l('注册',url('site/signup'));?> 或 <?php echo l('登录',url('site/login'));?>！</div>
<?php else:?>
<h4 class="f14px">积分从<?php echo $integral['lastintegral'];?>分增加到了<?php echo $integral['user_integral'];?>分</h4>
<div class="integral-border">
  	<div class="integral-line"></div>
</div>
<h4 class="relative"><?php echo $integral['min_integral'];?><span class="absolute" style="left:330px;"><?php echo $integral['mid_integral'];?></span><span class="absolute" style="right:1px;"><?php echo $integral['max_integral'];?></span></h4>
<script type="text/javascript">
$(function(){
	$(".integral-line").animate({width:'535px'},2000);
});
</script>
<?php endif;?>