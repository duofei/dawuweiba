<?php if (!user()->isGuest):?>
<?php $this->beginWidget('WmCornerBox', array(
	'htmlOptions' => array('class'=>'corner-gray ma-t10px ma-b20px lh24px'),
));?>
<h3 class="indent24px">
	欢迎您，亲爱的<?php echo user()->name;?>, 
	您已经在我爱外卖完成了<?php echo (int)$_SESSION['orderCompleteCount'];?>个订单，
	吃过<?php echo (int)$_SESSION['orderGoodsCount'];?>份美食，
	已经有了<?php echo $_SESSION['integral'];?>点积分。
</h3>
<?php $this->endWidget();?>
<?php endif;?>

<?php foreach ((array)$gifts as $k => $v):?>
<h3 class="f16px bline lh24px"><?php echo $k;?>分礼品</h3>
<?php foreach ((array)$v as $g):?>
<div class="ma20px fl gift-item">
	<div class="ma-b10px"><?php echo $g->picLinkHtml;?></div>
	<p class="ac cblack"><?php echo $g->nameLinkHtml;?></p>
</div>
<?php endforeach;?>
<div class="clear"></div>
<?php endforeach;?>