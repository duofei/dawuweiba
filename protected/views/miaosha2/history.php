<div class="c-left fl">
	<div class="c-left-top"></div>
	<div class="c-left-content">
		<?php $this->renderPartial('/miaosha2/left', array(
			'todayShops'=>$todayShops,
			'center' => $center,
			'colors' => $colors,
			'lastLatLng' => $lastLatLng,
		));?>
	</div>
	<div class="c-left-bottom"></div>
</div>
<div class="c-right fl">
	<div class="c-right-top"></div>
	<div class="c-right-content">
		<div class="miaosha-info pa-t10px">
			<div class="bgred cwhite fb lh30px ma-l10px ma-r20px pa-l10px"><?php echo date('m月d日')?>成功秒杀展示</div>
			<?php if($history):?>
			<?php foreach ((array)$history as $k=>$h):?>
				<div class="lh30px ma-l10px ma-r20px pa-l10px <?php if($k%2){echo 'bgf3';}?>">
					<span class="fr pa-r5px"><?php echo date('H:i:s', $h->create_time);?></span>
					<span class="pa-l10px"><?php echo $h->goods->name;?></span>
					<span class="fl" style="width:130px; overflow:hidden;"><?php echo str_pad(mb_substr($h->user->username, 0, 2), strlen($h->user->username)>16 ? 16 : strlen($h->user->username), '*');?></span>
				</div>
			<?php endforeach;?>
			<?php else:?>
			<div class="lh30px ma-l10px ma-r20px pa-l10px bgf3 ac">
				暂无记录
			</div>
			<?php endif;?>
		</div>
	</div>
	<div class="c-right-bottom"></div>
</div>
<div class="clear"></div>