<div class="pa-l20px f14px lh24px location-switch">
<?php foreach($location as $r):?>
	<div class="cblack bg-icon ma-t10px"><?php echo l($r->name, $r->shopListUrl);?></div>
<?php endforeach;?>
	<p class="ma-t10px bg-icon"><?php echo l('搜索新地址', url('site/index', array('f'=>STATE_ENABLED)));?></p>
</div>