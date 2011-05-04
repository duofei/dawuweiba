<?php if($shop->state):?>
<div><img class="ma-t10px" src="<?php echo resBu('images/shop-step3.jpg');?>" width="600" height="50" /></div>
<?php else: ?>
<div><img class="ma-t10px" src="<?php echo resBu('images/shop-step2.jpg');?>" width="600" height="50" /></div>
<?php endif;?>
<div class="pa-l10px f16px lh40px">
	<?php if($shop->state):?>
	<h4>您的商铺：<span class="cred"><?php echo $shop->nameLinkHtml; ?></span>已经开通！</h4>
	<h4>现在就去<?php echo l('管理中心管理商铺', url('shopcp'));?>！</h4>
	<?php else:?>
	<h4>我们已经收到<span class="f20px cred"><?php echo $shop->shop_name; ?></span>的开通申请。</h4>
	<h4>请耐心等待我们的通知，感谢您对我爱外卖网的支持！</h4>
	<h4>客服电话：<?php echo param('servicePhone');?></h4>
	<?php endif;?>
</div>