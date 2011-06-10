<div style="text-align:center; margin-top:20px;">
	<?php foreach ($list as $k=>$v):?>
		<?php echo CHtml::image(sbu($v->img));?>
		<?php if(($k+1)%4 == 0):?>
		<div style="page-break-after:always;"></div>
		<div style="margin-top:20px;"></div>
		<?php else:?>
		<div style="border-bottom:1px dashed #cccccc; font-size:0px; height:1px; margin:10px 0px;"></div>
		<?php endif;?>
	<?php endforeach;?>
</div>
<script>
window.print();
window.moveTo(100, 0);
window.resizeTo(800,1950);
</script>