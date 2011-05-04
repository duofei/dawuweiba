<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title><?php echo $this->pageTitle . $this->city['name'] . '外卖网_' . $this->city['name'] . '快餐_' . app()->name;?>_爱生活 爱外卖</title>
<?php
echo CHtml::metaTag('document', 'Resource-Type');
echo CHtml::metaTag('global', 'Distribution');
echo CHtml::metaTag('Chris Chen', 'Author');
echo CHtml::metaTag('24beta.com', 'Generator');
echo CHtml::metaTag('Copyright (c) 2009 24Beta.com. All Rights Reserved.', 'CopyRight');
echo CHtml::metaTag('general', 'rating');
echo CHtml::linkTag('shortcut icon', 'image/x-icon', '/favicon.ico');
echo CHtml::script('BU = \'' . abu() . '\'; RESBU = \'' . resBu() . '\'; SBU = \'' . sbu() . '\'');
?>
</head>
<body>
<div class="wrapper">
    <div id="header">
        <div class="logo fl"><?php echo l(CHtml::image(resBu('images/logo.png'), '我爱外卖LOGO'), bu(), array('title'=>app()->name));?></div>
        <div class="fr al lh20px fb tright cgray ma-t20px ma-r20px">
            <p class="bg-icon pa-l10px left-trigon">更多品种，实时更新的商品</p>
            <p class="bg-icon pa-l10px left-trigon">方便、优惠、永不占线</p>
            <p class="bg-icon pa-l10px left-trigon">还有积分回馈</p>
        </div>
    	<div class="clear"></div>
    </div>
    <div class="wrapper gray5pxline"></div>
</div>

<div class="wrapper">
    <?php echo $content;?>
</div>
<div class="space10pxline"></div>
<?php $this->renderPartial('/public/footer');?>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu("styles/classic.css"), 'screen');
//cs()->registerCssFile(resBu('styles/components.css'), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/global.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('scripts/main.js'), CClientScript::POS_END);
?>

<?php echo $this->renderPartial('/public/tongji');?>