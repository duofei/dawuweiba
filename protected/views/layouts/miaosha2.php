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
			<div class="miaosha-info">
				<div class="invite">
					<div class="fl ma-t10px ma-l10px"><?php echo CHtml::image(resBu('miaosha2/images/new.gif'));?></div>
					<div class="fl ma-l5px">邀请好友有奖：成功邀请好友，同获10元返利(无上限)。　邀请好友:</div>
					<div class="fl ma-t5px"><?php echo CHtml::image(resBu('miaosha2/images/invite_icon.jpg'));?></div>
					<div class="fr ma-r5px ma-t10px"><?php echo CHtml::image(resBu('miaosha2/images/close.gif'));?></div>
					<div class="clear"></div>
				</div>
				<div class="m-info ma-t10px">
					<div class="fl  fb">价格:</div>
					<div class="fl ma-l5px"><?php echo CHtml::image(resBu('miaosha2/images/1yuan.gif'));?></div>
					<div class="fl ma-l20px "><span class="fb">总计:</span>50单(已抢30单)</div>
					<div class="clear"></div>
				</div>
				<div class="goods-list ma-t10px">
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
				<div class="goods-list ma-t10px">
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
				<div class="pa-b5px ma-t10px">
					<div class="fl  fb lh30px">秒杀:</div>
					<div class="fl showtime">
						<span>01</span>
						<span>12</span>
						<span>34</span>
						<div class="clear"></div>
					</div>
					<div class="fl ma-l10px">
						<div class=""><span class="fb">已选择：</span>无可奈何花落去</div>
						<div class="ma-t5px"><?php echo CHtml::image(resBu('miaosha2/images/btn1.jpg'));?></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="mline1px"></div>
				<div class="ma-t10px fb">秒杀流程</div>
				<div class="ma-t5px ma-b20px">
					<div class="fl mslc">请登陆</div>
					<div class="fl jiantou"></div>
					<div class="fl mslc">选择您的地址(未选择)</div>
					<div class="fl jiantou"></div>
					<div class="fl mslc">填写送餐地址(未填写)</div>
					<div class="fl jiantou"></div>
					<div class="fl mslc">用户认证(未认证)</div>
					<div class="fl jiantou"></div>
					<div class="fl mslc">等待秒杀</div>
					<div class="clear"></div>
				</div>
				<div class="mline1px"></div>
				<div class="ma-t10px fb">秒杀技巧</div>
				<div class="ma-t5px">我爱外卖抢购午餐是以下定单最快为抢购成功，所以要在抢购前准备好4步，首先注册用户，其次要选择自己所在的地址，再次要填写好送餐的地址，最后一定要进行用户认证。</div>
				<div class="ma-t10px"><?php echo CHtml::image(resBu('miaosha2/images/yaoqing.png'));?></div>
				<div class="ma-t10px" style="height:390px"> 
					<iframe scrolling="no" frameborder="0" src="http://www.connect.renren.com/widget/liveWidget?api_key=49e422d84b694b69ba5e3c5809db4102&url=http%3A%2F%2Fwww.52wm.com%2Fmiaosha&desp=%E4%B8%80%E5%85%83%E5%8D%88%E9%A4%90%E7%81%AB%E7%83%AD%E8%BF%9B%E8%A1%8C%E4%B8%AD" style="width:610px;height:390px;"></iframe> 
				</div> 
			</div>
		</div>
		<div class="c-right-bottom"></div>
	</div>
	<div class="clear"></div>
</div>
<div class="footer ma-t20px">
	<ul class="cwhite lh30px">
		<li class="fl" style="border-left:0px;">
			<div class="f16px fb">关于我们</div>
			<div>关于工工</div>
		</li>
		<li class="fl">
			<div class="f16px fb">订餐指南</div>
			<div>关于工工</div>
		</li>
		<li class="fl">
			<div class="f16px fb">订餐指南</div>
			<div>关于工工</div>
		</li>
		<li class="fl" style="border-right:0px;">
			<div class="f16px fb">订餐指南</div>
			<div>关于工工</div>
		</li>
		<div class="clear"></div>
	</ul>
	<div class="fb f14px; ma-t20px ma-b20px ac cblack">
		本站所有店铺图片资料及文字资料未经书面许可 不得转载 违者必究 2010-2011 我爱外卖 网 52WM.com
	</div>
</div>
<script language="JavaScript">
<!--
$(function(){
	/* 设置左右高 */
	var rightHeight = $('.c-right-conten').height();
	$('.c-left-content').height(rightHeight);
});
//-->
</script>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu('miaosha2/styles/miaosha.css'), 'screen');
cs()->registerCoreScript('jquery');
?>