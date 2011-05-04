<div class="win-left">
	<ul class="radius-top cblack">
		<li class="title fb"><?php echo $menu['label'];?></li>
		<?php
		    foreach ($menu['sub'] as $m):
		    if (is_array($m)):
		?>
			<li><?php echo l($m['label'], $m['url'], array('target'=>'main'));?></li>
		<?php else:?>
			<li class="separation-line"></li>
		<?php endif; endforeach;?>
	</ul>
</div>

<script type="text/javascript">
$(function(){
	$('.win-left a').click(function(){
		$('.win-left a').removeClass('clicked');
		$(this).addClass('clicked');
	});
});
</script>