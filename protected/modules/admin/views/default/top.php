<div class="win-top color-white">
	<div class="fl top-logo ac"><h1>52wm.com</h1></div>
	<ul class="top-menu f16px fl fb cblack">
    	<?php
    	foreach ($menus as $menu):
    	    if ($menu['show']):
    	?>
    	<li><?php echo l($menu['label'], $menu['url'], array('target'=>'left'));?></li>
    	<?php endif; endforeach;?>
    </ul>
    <ul class="top-menu f14px fr fb">
    	<li><a href="<?php echo url('admin/default/start');?>" target="main">起始页</a></li>
    	<li><a href="<?php echo bu();?>" target="_blank">网站首页</a></li>
    	<li><a href="<?php echo url('admin/default/logout');?>" target="_top">退出系统</a></li>
    </ul>
    <div class="clear"></div>
</div>