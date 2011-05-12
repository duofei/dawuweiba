<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title><?php echo $this->pageTitle . '_' . $this->city['name'] . '外卖网_' . $this->city['name'] . '快餐_' . app()->name;?></title>
<?php
echo CHtml::metaTag('document', 'Resource-Type');
echo CHtml::metaTag('global', 'Distribution');
echo CHtml::metaTag('我爱外卖网52wm.com', 'Author');
echo CHtml::metaTag('52wm.com', 'Generator');
echo CHtml::metaTag('Copyright (c) 2010 52wm.com. All Rights Reserved.', 'CopyRight');
echo CHtml::metaTag('general', 'rating');
echo CHtml::linkTag('shortcut icon', 'image/x-icon', '/favicon.ico');
echo CHtml::script('BU = \'' . abu() . '\'; RESBU = \'' . resBu() . '\'; SBU = \'' . sbu() . '\'');
?>
</head>
<body>
<div class="header">
	<div class="header-info">
		<div class="header-logo fl ma-t10px header-logo-select"></div>
		<div class="user-info fr pa-l10px pa-r10px cwhite">Bevin您好 | <a href="#">用户中心</a> | <a href="#">退出</a></div>
		<div class="clear"></div>
		<div class="header-h215px"></div>
		<ul class="header-nav lh30px cwhite f12px cursor">
			<li class="select"><a href="#">5月9日(进行中)</a></li>
			<li class="hover"><a href="#">5月0日</a></li>
			<li><a href="#">5月11日</a></li>
			<li><a href="#">5月11日</a></li>
			<li><a href="#">5月11日</a></li>
			<li><a href="#">5月11日</a></li>
			<li><a href="#">5月11日</a></li>
			<li><a href="#">5月11日</a></li>
		</ul>
		<div class="clear"></div>
	</div>
</div>
<div class="content">
	<div class="c-left fl">
		<div class="c-left-top"></div>
		<div class="c-left-content">
			<div class="cblack ma-l20px">您的位置：<?php echo l('首页', app()->homeUrl);?> > <a href="#">一元秒杀</a></div>
			<div class="today-shop"></div>
			<div class="shop-logo">
				<div class="s-logo"><img src="http://img.baidu.com/img/baike/logo-baike.gif" width="100" height="100" /></div>
				<div class="s-logo"><img src="http://img.baidu.com/img/baike/logo-baike.gif" width="100" height="100" /></div>
				<div class="clear"></div>
			</div>
			<div class="shop-area ac fb f16px cgray lh30px pa-t5px">活动区域</div>
			<div class="shop-maps">活动区域</div>
			<div class="shop-maps-color">
				<div class="fl" style="background:#00CCFF; width:50px; height:14px;"></div>
				<div class="fl ma-l5px">家家欢乐餐厅</div>
				<div class="clear"></div>
			</div>
			<div class="shop-maps-color">
				<div class="fl" style="background:#fad401; width:50px; height:14px;"></div>
				<div class="fl ma-l5px">百碗香洪家楼西路店</div>
				<div class="clear"></div>
			</div>
			<div class="ac ma-t10px">
				<?php echo CHtml::image(resBu('miaosha2/images/gz52wm_sina.gif'));?>
			</div>
			<div class="shop-box-top"></div>
			<div class="shop-box">
				<div class="pa-t10px pa-l10px"><?php echo CHtml::image(resBu('miaosha2/images/yjfk.gif'));?></div>
				<div class="pa-l10px lh30px">请<?php echo l('点击这里', url('feedback/index'));?>提交意见反馈</div>
			</div>
			<div class="shop-box-bottom"></div>
			
			<div class="shop-box-top"></div>
			<div class="shop-box">
				<div class="pa-t10px pa-l10px"><?php echo CHtml::image(resBu('miaosha2/images/smshhd.gif'));?></div>
				<div class="ma-t5px ma-b5px pa-l10px">
					<div class="fl"><input type="text" style="width:140px; height:20px; border:1px solid #b43700;" /></div>
					<div class="fl ma-l5px"><input type="image" src="<?php echo resBu('miaosha2/images/2_r13_c8.gif');?>" /></div>
					<div class="clear"></div>
				</div>
				<div class="pa-l10px lh20px pa-r10px">我们会通过邮件在第一时间通知您最新的活动(随时可以取消)。</div>
			</div>
			<div class="shop-box-bottom"></div>
		
			<div class="shop-box-top"></div>
			<div class="shop-box">
				<div class="pa-t10px pa-l5px"><?php echo CHtml::image(resBu('miaosha2/images/taolq.gif'));?></div>
			</div>
			<div class="shop-box-bottom"></div>
		</div>
		<div class="c-left-bottom"></div>
	</div>
	<div class="c-right fl">
		<div class="c-right-top"></div>
		<div class="c-right-conten">
			<div class="invite">
				<div class="fl ma-t10px ma-l10px"><?php echo CHtml::image(resBu('miaosha2/images/new.gif'));?></div>
				<div class="fl ma-l5px">邀请好友有奖：成功邀请好友，同获10元返利(无上限)。　邀请好友:</div>
				<div class="fl ma-t5px"><?php echo CHtml::image(resBu('miaosha2/images/invite_icon.jpg'));?></div>
				<div class="fr ma-r5px ma-t10px"><?php echo CHtml::image(resBu('miaosha2/images/close.gif'));?></div>
				<div class="clear"></div>
			</div>
			<div class="m-info">
				<div class="fl  fb">价格:</div>
				<div class="fl ma-l5px"><?php echo CHtml::image(resBu('miaosha2/images/1yuan.gif'));?></div>
				<div class="fl ma-l20px "><span class="fb">总计:</span>50单(已抢30单)</div>
				<div class="clear"></div>
			</div>
			<div class="goods-list">
				<div class="fl  fb lh30px">菜品:</div>
				<ul class="fl subfl ma-l5px" style="width:560px">
					<li class="shop-name pa-l10px fb"><a href="#">家家欢乐餐厅</a></li>
					<li class="ma-r5px"><?php echo CHtml::image(resBu('miaosha2/images/M_r11_c34.jpg'));?></li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods goods-select">友有奖：成功</li>
					<li class="goods goods-hover">无可奈何花落去</li>
					<li class="goods">功邀请好友，</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">友有奖：成功</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">无可奈何花落去</li>
					<li class="clear" style="font-size:0px; height:0px;"></li>
				</ul>
				<div class="clear"></div>
			</div>
			<div class="goods-list">
				<div class="fl  fb lh30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
				<ul class="fl subfl ma-l5px" style="width:560px">
					<li class="shop-name pa-l10px fb"><a href="#">家家欢乐餐厅</a></li>
					<li class="ma-r5px"><?php echo CHtml::image(resBu('miaosha2/images/M_r11_c34.jpg'));?></li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods goods-select">友有奖：成功</li>
					<li class="goods goods-hover">无可奈何花落去</li>
					<li class="goods">功邀请好友，</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">友有奖：成功</li>
					<li class="goods">无可奈何花落去</li>
					<li class="goods">无可奈何花落去</li>
					<li class="clear" style="font-size:0px; height:0px;"></li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div class="c-right-bottom"></div>
	</div>
	<div class="clear"></div>
</div>
<div class="footer">

</div>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu('miaosha2/styles/miaosha.css'), 'screen');
cs()->registerCoreScript('jquery');
?>