<div class="main">
<div><img src="<?php echo resBu('miaosha/images/titjr3.jpg');?>" /></div>
<div style="width:874px; font-size:14px; margin: 0px auto; background:#ffffff; border-left:1px solid #CBCBCB; border-right:1px solid #CBCBCB; line-height:30px; padding:0px 20px">
	<div style="height:50px;"></div>
	<div class="fail-top"></div>
	<div class="fail">
		<div class="fl"><?php echo CHtml::image(resBu('miaosha/images/fail_timeisup.jpg'));?></div>
		<div class="fl">
			<div class="txt">很抱歉！ 您下手太慢了，本次秒杀商品已被抢光了。 本活动15分钟一轮，您可以进入下一轮秒杀。 </div>
			<a class="btn1" href="<?php echo url('miaosha/index')?>"></a>
			<a class="btn2" href="<?php echo url('miaosha/history')?>"></a>
		</div>
		<div class="clear"></div>
		<div class="line1px" style="width:650px; margin:20px auto;"></div>
		<div style="margin:20px 125px;"><?php echo l(CHtml::image(resBu('miaosha/images/fail_link1.jpg')), url('site/index'));?></div>
		<div style="margin:20px 125px;"><?php echo l(CHtml::image(resBu('miaosha/images/fail_link2.jpg')), url('my/default/inviteurl'));?></div>
	</div>
	<div class="fail-bottom"></div>
	<div style="height:70px;"></div>
</div>
<div><img src="<?php echo resBu('miaosha/images/bg04.jpg');?>" /></div>
</div>
<script type="text/javascript">
$(function(){
	$(".btn1").hover(function(){$(this).addClass('select1');},function(){$(this).removeClass('select1');});
	$(".btn2").hover(function(){$(this).addClass('select2');},function(){$(this).removeClass('select2');});
});
</script>