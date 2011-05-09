<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xn="http://www.renren.com/2009/xnml">
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
<div class="user-toolbar">
	<div class="wrapper">
		<?php $this->renderDynamic('getUserToolbar');?>
	</div>
</div>
<div class="wrapper">
    <div id="header">
        <div class="logo fl"><?php echo l(CHtml::image(resBu('images/logo.png'), '我爱外卖LOGO'), bu(), array('title'=>app()->name));?></div>
        <div class="location-nav fl ma-l10px lh15px">
        	<?php $this->renderDynamic('getUserLocation');?>
        </div>
        <a href="<?php echo url('miaosha/index');?>"><img src="<?php echo resBu('images/banner_r1_c2.png');?>" class="fr" /></a>
    	<div class="clear"></div>
    </div>
    <div class="topnav f14px fb">
        <ul class="fl subfl ma-l20px">
            <li class="ma-r10px"><a href="<?php echo url('site/index', array('f'=>STATE_ENABLED));?>" class="cwhite">首页</a></li>
            <li class="ma-r10px"><a href="<?php echo url('shop/list', array('cid'=>ShopCategory::CATEGORY_FOOD));?>" class="<?php echo ((int)$_GET['cid'] == ShopCategory::CATEGORY_FOOD) ? 'bg-pic select' : 'cwhite';?>">美食外卖</a></li>
            <!-- <li class="ma-r10px"><a href="<?php //echo url('shop/list', array('cid'=>ShopCategory::CATEGORY_CAKE));?>" class="<?php //echo ((int)$_GET['cid'] == ShopCategory::CATEGORY_CAKE) ? 'bg-pic select' : 'cwhite';?>">蛋糕外卖</a></li> -->
            <!-- <li class="ma-r10px"><a href="<?php echo url('groupon');?>" class="<?php echo ($this->id == 'groupon') ? 'bg-pic select' : 'cwhite';?>">同楼订餐</a></li> -->
            <li class="ma-r10px"><a href="<?php echo url('promotion');?>" class="<?php echo ($this->id == 'promotion') ? 'bg-pic select' : 'cwhite';?>">优惠信息</a></li>
            <li class="ma-r10px"><a href="<?php echo url('feedback');?>" class="<?php echo ($this->id == 'feedback') ? 'bg-pic select' : 'cwhite';?>">反馈留言</a></li>
            <li class="ma-r10px"><a href="<?php echo url('gift');?>" class="<?php echo ($this->id == 'gift') ? 'bg-pic select' : 'cwhite';?>">礼品中心</a></li>
        </ul>
        <ul class="fr subfl ma-r10px">
            <li class="ma-r10px"><a href="<?php echo url('tuannav/list');?>" class="<?php echo ($this->id == 'tuannav') ? 'bg-pic select' : 'cwhite';?>">团购导航</a></li>
        </ul>
    </div><div class="clear"></div>
    <?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'htmlOptions' => array('id'=>'breadcrumbs', 'class'=>'breadcrumbs ma-t10px ma-b10px'),
            'separator' => '<span class="cred">&nbsp;&gt;&nbsp;</span>',
            'homeLink' => l('地址搜索', url('site/index', array('f'=>STATE_ENABLED))),
            'links'=> $this->breadcrumbs,
        ));
    ?>
</div>
<div class="wrapper">
    <?php echo $content;?>
</div>
<div class="space10pxline"></div>
<?php $this->renderPartial('/public/footer');?>
<div class="space10pxline"></div>
<div class="pop-tag"></div>
<span class="none" id="overlayBox" cart="<?php echo aurl('cart/conflict');?>" location="<?php echo aurl('location/conflict');?>" selectBuilding="<?php echo aurl('cart/selectBuilding', array('shopid'=>$_GET['shopid'],'goodsid'=>$_GET['goodsid']));?>" noGroupInCart="<?php echo aurl('cart/noGroupInCart'); ?>" groupInCart="<?php echo aurl('cart/groupInCart'); ?>"></span>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu("styles/main.css"), 'screen');
//cs()->registerCssFile(resBu('styles/components.css'), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/global.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('scripts/main.js'), CClientScript::POS_END);
?>

<?php if (user()->isGuest):?>
<script type="text/javascript">
<!--
$(function(){
	loadRenrenLoader();
});
function onRenRenLogin()
{
    XN.Connect.requireSession(function(){
		window.location = '<?php echo bu('/connect/renren');?>';
    });
    return false;
}
//-->
</script>
<?php endif;?>
<script type="text/javascript">
<!--
//位置处理
var locationPop = $("div.location-pop");
$("a.underline").hover(function(){
	var position = $(this).position();
	locationPop.show();
	locationPop.css('left',position.left);
	locationPop.css('top',position.top + 14);
}, function(){
	  locationPop.hide();
});
locationPop.hover(function(){
	$(this).show();
}, function(){
	$(this).hide();
});
//-->
</script>

<?php echo $this->renderPartial('/public/tongji');?>