<?php
	/**
	 * 设置面包屑导航
	 */
	$this->breadcrumbs = array(
		'加入我们' => url('static/service'),
	);
	$this->pageTitle = $location->name . '外卖';
?>
	<?php echo $this->renderPartial('pageleft', array('joinus' => 'select'));?>
	<div class="page-right fl pa-20px lh24px indent-p f14px">
        <h4 class="f16px cred">加入我们</h4>   
    </div>
    <div class="clear"></div>