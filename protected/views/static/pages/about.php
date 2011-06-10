<?php
	/**
	 * 设置面包屑导航
	 */
	$this->breadcrumbs = array(
		'关于我们' => url('static/service'),
	);
	$this->pageTitle = $location->name . '外卖';
?>
	<?php echo $this->renderPartial('pageleft', array('about' => 'select'));?>
	<div class="page-right fl pa-20px lh24px f14px">
        <h4 class="f16px cred">关于我们</h4>
    <p class="ma-t10px">我爱外卖（52wm.com）创建于2010年济南的冬天。</p>

	<p class="ma-t10px">我们是一群关注区域化电子商务的年轻人，来自各个领域，毕业于各个学校。</p>

	<p>我们有着共同的期望：希望通过互联网能让我们的生活更加便利，美好。</p>
	
	<p class="ma-t10px">我爱外卖不仅是一个能给宅男宅女们提供餐饮外卖的平台，</p>
	
	<p>我爱外卖更是一个同城功能型的便民服务平台，服务更多的用户，提供更多的便利项目。</p>
	
	
	
	<p class="ma-t10px">我们坚信客户的认可才是产品成功的关键，</p>
	
	<p>所以我们一直会尽我们所有的努力来完善这个平台，给商铺带来利润，给顾客带去方便。</p>
	
	
	<p class="ma-t10px">更多的选择，更方便的模式，更好的使用体验！</p>
	
	<p>我爱生活，我爱外卖。。。。</p>
    </div>
    <div class="clear"></div>