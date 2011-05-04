<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title><?php echo ($this->pageTitle ? $this->pageTitle . '_' : '') . $this->city['name'] . '外卖网_' . $this->city['name'] . '快餐_' . app()->name;?></title>
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
<div style="width:970px;  margin:0 auto; display:none;">
<script type="text/javascript">alimama_bm_revision = "20110413";alimama_bm_bid = "20329952";alimama_bm_width = 950;alimama_bm_height = 150;alimama_bm_xmlsrc = "http://img.uu1001.cn/x2/2011-04-16/15-14/2011-04-16_0bfbf8e141acdfa3725062df3c22aa17_0.xml";alimama_bm_link = "http%3A%2F%2Fshop36633991.taobao.com";alimama_bm_ds = "";alimama_bm_as = "default"</script><script type="text/javascript" src="http://img.uu1001.cn/bmv3.js?v=20110413"></script>
<script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','970','height','40','src','<?php echo resBu('images/newindex/banner01');?>','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','<?php echo resBu('images/newindex/banner01');?>' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="970" height="40">
    <param name="movie" value="<?php echo resBu('images/newindex/banner01.swf');?>" />
    <param name="quality" value="high" />
    <embed src="<?php echo resBu('images/newindex/banner01.swf');?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="970" height="40"></embed>
  </object>
</noscript>
</div>

<div style="background:url(<?php echo resBu('images/newindex/topbg.jpg');?>) repeat-x;height:108px; overflow:hidden; ">
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
</div>
<!--header end-->
<?php echo $content?>
<!--copyright begin-->
<?php $this->renderPartial('/public/newfooter');?>
<!--copyright end-->
<div class="pop-tag"></div>
<span class="none" id="overlayBox" cart="<?php echo aurl('cart/conflict');?>" location="<?php echo aurl('location/conflict');?>" selectBuilding="<?php echo aurl('cart/selectBuilding', array('shopid'=>$_GET['shopid'],'goodsid'=>$_GET['goodsid']));?>" noGroupInCart="<?php echo aurl('cart/noGroupInCart'); ?>" groupInCart="<?php echo aurl('cart/groupInCart'); ?>"></span>
</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
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