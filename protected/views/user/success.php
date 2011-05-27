<div class="ma-t30px ma-b30px">
	<div class="fl" style="margin-left:200px;">
		<?php echo CHtml::image(resBu('images/user_success.jpg'));?>
	</div>
	<div class="fl ma-l30px">
		<div class="lh30px f16px fb ma-t30px"><?php echo $message?></div>
		<div class="cblack">
			<a class="user_btn ma-t20px fl" href="<?php echo url('my/integral/bcintegral')?>">点击查看</a>
			<?php if($referer):?>
			<a class="user_btn ma-t20px ma-l20px fl" href="<?php echo $referer?>">返回上一页</a>
			<?php endif;?>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>