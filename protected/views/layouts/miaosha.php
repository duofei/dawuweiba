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
<!--header begin-->
<div class="header">
<div class="logo fl"></div>
<div class="fr login">
	<div class="denglu"><?php $this->renderPartial('/public/usertoolbar');?></div>
    <div class="list01">
    <?php $this->renderPartial('/public/newmenu');?>
    </div>
</div>
<div class="clear"></div>
</div>
<!--header end-->
<div class="bigbanner">
	<div class="bannertime">4月18日-4月22日 每日50份一元午餐限时抢</div>
    <div><a class="guize" href="<?php echo url('miaosha/rules');?>" target="_blank">确定</a></div>
</div>
<!--main beigin-->
<?php echo $content;?>
<!--copyright begin-->
<?php $this->renderPartial('/public/newfooter');?>
<!--copyright end-->
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu('miaosha/styles/miaosha.css'), 'screen');
cs()->registerCoreScript('jquery');
?>