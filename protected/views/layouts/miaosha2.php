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
		<div class="header-logo fl ma-t10px cursor"></div>
		<div class="user-info fr pa-l10px pa-r10px cwhite">
		<?php if (user()->isGuest):?>
		您好！ | <?php echo l('登录', user()->loginUrl);?> | <?php echo l('免费注册', url('site/signup'));?>
		<?php else:?>
		<?php echo user()->screenName;?>您好 | <?php echo l('个人中心', url('my'));?> | <?php echo l('安全退出', url('site/logout'));?>
		<?php endif;?>
		</div>
		<div class="clear"></div>
		<div class="header-h215px"></div>
		<ul class="header-nav lh30px cwhite f12px">
		<?php $t = $_GET['t'] ? $_GET['t'] : time();?>
		<?php for ($i=param('miaoshaStartTime'); $i<=param('miaoshaEndTime'); $i=$i+86400):?>
			<?php if(date('Ymd', $i) == date('Ymd')):?>
			<li class="today <?php if(date('Ymd', $i)!=date('Ymd', $t)) echo ''; else echo 'today-select';?>"><a href="<?php echo url('miaosha2/index', array('t'=>$i));?>"><?php echo date('m月d日', $i);?>(进行中)</a></li>
			<?php else:?>
			<li class="<?php if(date('Ymd', $i)!=date('Ymd', $t)) echo ''; else echo 'select';?>"><a href="<?php echo url('miaosha2/index', array('t'=>$i));?>"><?php echo date('m月d日', $i);?></a></li>
			<?php endif;?>
		<?php endfor;?>
		</ul>
		<div class="clear"></div>
	</div>
</div>
<div class="content">
<?php echo $content;?>
</div>
<div class="footer ma-t20px">
	<ul class="cwhite lh30px">
		<li class="fl" style="border-left:0px;">
			<div class="f16px fb">关于我们</div>
			<div><a href="<?php echo url('static/about');?>">关于52wm</a></div>
			<div><a href="<?php echo url('static/contact');?>">联系我们</a></div>
            <div>人才招聘</div>
		</li>
		<li class="fl">
			<div class="f16px fb">订餐指南</div>
			<div>客服电话55500071</div>
			<div>客服QQ</div>
            <div>客服在线时间</div>
            <div>(周一至周五 9:00-18:00)</div>
		</li>
		<li class="fl">
			<div class="f16px fb">社区活动</div>
			<div><?php echo l('奖品兑换', url('gift/index'));?></div>
		</li>
		<li class="fl" style="border-right:0px;">
			<div class="f16px fb">其他</div>
			<div><?php echo l('店铺加盟', url('shop/checkin'));?></div>
			<div><?php echo l('友情链接', url('friendlink/index'));?></div>
            <div><?php echo l('反馈留言', url('feedback'), array('target'=>'_blank'));?></div>
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
	setLeftRightHeight();
	
	/* logo显示效果 */
	$('.header-logo').hover(function(){
		$(this).addClass('header-logo-select');
	}, function(){
		$(this).removeClass('header-logo-select');
	});
	$('.header-logo').click(function(){
		location.href = BU;
	});
	
	/* header-nav效果 */
	var navClass = false;
	$('.header-nav li').hover(function(){
		if($(this).hasClass('select') || $(this).hasClass('today-select')) {
			navClass = true;
		} else {
			if($(this).hasClass('today')) {
				$(this).addClass('today-select');
			} else {
				$(this).addClass('select');
			}
		}
	},function(){
		if(!navClass) {
			$(this).removeClass('today-select').removeClass('select');
		}
		navClass = false;
	});
	
	/* 显示地图 */
	jQuery.getScript('http://ditu.google.com/maps/api/js?sensor=false', function(){
		showMap();
	});
});
function setLeftRightHeight()
{
	var lHeight = $('.c-left-content').height();
	var rHeight = $('.c-right-content').height();
	if(lHeight > rHeight) {
		$('.c-right-content').height(lHeight);
	} else {
		$('.c-left-content').height(rHeight);
	}
}
//-->
</script>
<script type="text/javascript" src="http://ditu.google.com/maps/api/js?sensor=false"></script>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu('miaosha2/styles/miaosha.css'), 'screen');
cs()->registerCoreScript('jquery');
?>