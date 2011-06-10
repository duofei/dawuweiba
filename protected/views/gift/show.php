<h2 class="f14px ma-b5px ma-t10px"><?php echo $gift->name;?></h2>
<div class="gift-detail lh20px ma-t20px">
    <p class="lh24px"><?php echo $gift->content;?></p>
    <p class="ma-t20px">所需积分<?php echo $gift->integral;?>分，返回<?php echo l('礼品中心', url('gift'));?></p>
	<?php if(!user()->isGuest):?><p>
		您现有积分<?php echo $_SESSION['integral'];?>分，
		<?php if($_SESSION['integral'] < $gift->integral):?>
			还差<?php echo $gift->integral - $_SESSION['integral']; ?>分
		<?php else: ?>
			<?php echo l('马上兑换', url('gift/exchange', array('giftid'=>$gift->id)));?>
		<?php endif;?>
	</p><?php endif;?>
</div>