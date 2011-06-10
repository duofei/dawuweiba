<div class="right-nav">
    <ul class="nav">
	  <li class="select corner-top cgray"><a>账户预览</a></li>
	</ul>
</div>
<div class="pa-t10px pa-l20px pa-b10px pa-r20px">
	<div class="pad10">
		<div class="fl ma-r20px"><?php echo $_SESSION['portraitLinkHtml']; ?></div>
		<div class="fl">
	        <p>欢迎您，<?php echo user()->screenName; ?> <a href="<?php echo url('my/default/profile');?>">编辑个人资料&gt;&gt;</a> </p>
	        <?php if($user->approve_state == User::APPROVE_STATE_UNSETTLED):?>
	        <p>您还没有通过用户认证：<?php echo l('马上认证', url('my/default/approve'));?></p>
	        <?php endif;?>
	        <p>上次登录时间：<?php echo $user->lastLoginTimeText; ?></p>
			<p>上次登录IP：<?php echo $user->last_login_ip; ?> </p>
	        <p>您目前积分：<?php echo $user->integral; ?>分 <?php echo l('兑换白吃点', url('my/integral/change'));?> <?php echo l('兑换礼品', url('gift/index'))?></p>
	        <p>您目前白吃点：<?php echo $user->bcnums; ?>点 <?php echo l('查看',url('my/integral/bcintegral'));?></p>
			<p>您目前评价：好评率<?php echo $user->creditAverageMark;?></p>
	    </div>
	    <div class="clear"></div>
	</div>
	<div class="line1px"></div>
	<div class="pad10">
	      <div class="bg awoke fl ma-r10px"></div>
	 	<div class="fl">
	       <h3>个人中心提醒</h3>
		<?php if($user->orderCompleteCount > 0):?>
		   	<p>您目前已完成<a href="<?php echo url('my/order/list');?>"><?php echo $user->orderCompleteCount; ?>个</a>订单。</p>
		<?php else:?>
		   	<p>您目前还没有已完成的订单。</p>
		<?php endif;?>
		<?php if($user->orderGoodsNums > 0):?>
		   	<p>您已购买过<a><?php echo $user->orderGoodsNums;?>个</a>商品。</p>
		<?php else:?>
			<p>您还没有购买过任何商品。</p>
		<?php endif;?>
		<?php if($user->noRatingNums > 0):?>
		   	<p>您共有<a href="<?php echo url('my/order/norating');?>"><?php echo $user->noRatingNums;?>个</a>订单未评价。</p>
		<?php else:?>
			<p>您没有未点评订单。</p>
		<?php endif;?>
	       <p>去<a href="<?php echo app()->homeUrl; ?>">我爱外卖首页</a>，挑选喜爱的商品，体验购物乐趣吧。</p>
		   
	      </div>
	      <div class="clear"></div>
	</div>
</div>