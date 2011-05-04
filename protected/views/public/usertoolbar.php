<?php if (user()->isGuest):?>
	您好！欢迎光临我爱外卖
	<?php echo l('登录', user()->loginUrl);?>
	<?php echo l('免费注册', url('site/signup'));?>
<?php else:?>
	<?php echo '亲爱的' . user()->screenName . '，欢迎光临我爱外卖';?>
	<?php //echo l('购物车', url('cart/checkout')) . sprintf('(%d个美食)', Cart::getGoodsCount());?>
	<?php echo l('个人中心', url('my'));?>
	<?php //echo l('收藏夹', url('my/favorite'));?>
	<?php echo l('安全退出', url('site/logout'));?>
<?php endif;?>
 <a href="<?php echo url('shop/checkin');?>"><strong class="cd60a01">我要开店</strong></a>