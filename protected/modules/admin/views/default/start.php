<div id="start-page">
	<h3>欢迎使用我爱外卖系统</h3>
	<ul>
		<li class="fb">订单信息</li>
		<li>今日订单量：<?php echo Order::getCountOfToday($cityId);?>单</li>
		<li>今日订单总额：<?php echo Order::getAmountOfToday($cityId);?>元</li>
		<li>最近7天订单量：<?php echo Order::getCountOfDays(7, $cityId);?>单</li>
		<li>最近7天订单总额：<?php echo Order::getAmountOfDays(7, $cityId);?>元</li>
		<li>最近1个月订单量：<?php echo Order::getCountOfDays(30, $cityId);?>单</li>
		<li>最近1个月订单总额：<?php echo Order::getAmountOfDays(30, $cityId);?>元</li>
		<li>总订单量：<?php echo Order::getCountOfCity($cityId);?>单</li>
		<li>总订单额：<?php echo Order::getAmountOfCity($cityId);?>元</li>
	</ul>
	<div class="space10pxline"></div>
	<ul>
		<li class="fb">店铺信息</li>
		<li>餐馆数量：<?php echo Shop::getFoodShopCount($cityId);?>家</li>
		<li>蛋糕店数量：<?php echo Shop::getCakeShopCount($cityId);?>家</li>
	</ul>
	<div class="space10pxline"></div>
	<ul>
		<li class="fb">用户信息</li>
		<li>今日注册用户量：<?php echo User::getCountOfDays(1);?>位</li>
		<li>最近7天注册用户量：<?php echo User::getCountOfDays(7);?>位</li>
		<li>最近1月注册用户量：<?php echo User::getCountOfDays(30);?>位</li>
		<li>总用户数：<?php echo User::getCountOfCity();?>位</li>
		<li>人人网用户数：<?php echo User::getCountOfRenren();?>位</li>
		<li>新浪微博用户数：<?php echo User::getCountOfSina();?>位</li>
	</ul>
	<div class="space10pxline"></div>
	<ul>
		<li class="fb">系统信息</li>
		<li>程序版本：<?php echo CdcBetaTools::getVersion();?></li>
		<li>服务器IP：<?php echo $_SERVER['SERVER_ADDR'];?></li>
		<li>操作系统及PHP：<?php echo sprintf('%s / PHP v%s', PHP_OS, PHP_VERSION);?></li>
		<li>服务器软件：<?php echo $_SERVER['SERVER_SOFTWARE'];?></li>
		<li>数据库：<?php echo sprintf('%s-%s', ucfirst(app()->db->driverName), app()->db->serverVersion);?></li>
		<li>网站路径：<?php echo $_SERVER['DOCUMENT_ROOT'];?></li>
		<li>上传许可：<?php echo ini_get('file_uploads') ? ini_get('upload_max_filesize') : '禁止上传';?></li>
	</ul>
</div>