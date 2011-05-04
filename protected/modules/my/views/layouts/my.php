<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title><?php echo $this->pageTitle;?> - 用户中心 - 我爱外卖网</title>
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
</div>
<div class="wrapper">
	<?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'htmlOptions' => array('id'=>'breadcrumbs', 'class'=>'breadcrumbs ma-t10px ma-b10px'),
            'separator' => '<span class="cred">&nbsp;&gt;&nbsp;</span>',
            'homeLink' => l('首页', url('site/index', array('f'=>STATE_ENABLED))),
            'links'=> $this->breadcrumbs,
        ));
    ?>
	<div class="left corner-top corner-bottom cblack">
    	<h2>个人中心</h2>
     	<dl>
     		<dd><a href="<?php echo url('my/order/uncomplete'); ?>" class=<?php if ($this->id == 'order') echo 'active'; else echo 'normal';?>><span class="ico5">我的订单</span></a></dd>
            <dd><a href="<?php echo url('my/favorite'); ?>" class="<?php if ($this->id == 'favorite') echo 'active'; else echo 'normal';?>"><span class="ico6">我的收藏</span></a></dd>
            <dd><a href="<?php echo url('my/question/list'); ?>" class="<?php if ($this->id == 'question') echo 'active'; else echo 'normal';?>"><span class="ico17">我的留言</span></a></dd>
     		<dd><a href="<?php echo url('my/address/list'); ?>" class="<?php if ($this->id == 'address') echo 'active'; else echo 'normal';?>"><span class="ico7">我的地址</span></a></dd>
     		<dd><a href="<?php echo url('my'); ?>" class="<?php if ($this->id == 'default' && $this->action->id == 'index') echo 'active'; else echo 'normal';?>"><span class="ico1">账户概览</span></a></dd>
     		<dd><a href="<?php echo url('my/default/profile'); ?>" class="<?php if ($this->id == 'default' && $this->action->id == 'profile') echo 'active'; else echo 'normal';?>"><span class="ico2">个人资料</span></a></dd>
     		<dd><a href="<?php echo url('my/message/list'); ?>" class="<?php if ($this->id == 'message') echo 'active'; else echo 'normal';?>"><span class="ico3">系统消息</span></a></dd>
     		<dd><a href="<?php echo url('my/tuannav/favorite'); ?>" class="<?php if ($this->id == 'tuannav') echo 'active'; else echo 'normal';?>"><span class="ico4">团购管理</span></a></dd>
     		<dd><a href="<?php echo url('my/default/inviteurl'); ?>" class="<?php if ($this->id == 'default' && $this->action->id == 'inviteurl') echo 'active'; else echo 'normal';?>"><span class="ico8">邀请好友</span></a></dd>
     	</dl>
 	</div>
	<div class="right corner-top corner-bottom">
	    <?php echo $content; ?>
	</div>
	<div class="clear"></div>
</div>
<div class="space10pxline"></div>

<div id="footer" class="wrapper lh30px">
	<div class="gray5pxline"></div>
    <ul class="subfl">
        <li><?php echo l('联系我们', url('static/contact'), array('target'=>'_blank'));?></li><li>|</li>
        <li><?php echo l('隐私安全', url('static/safety'), array('target'=>'_blank'));?></li><li>|</li>
        <li><?php echo l('反馈留言', url('feedback'), array('target'=>'_blank'));?></li><li>|</li>
        <li><?php echo l('关于我们', url('static/about'), array('target'=>'_blank'));?></li><li>|</li>
        <li><?php echo l('服务条款和协议', url('static/service'), array('target'=>'_blank'));?></li><li>|</li>
        <li><?php echo l('鲁ICP备10205272号-1', 'http://www.miitbeian.gov.cn', array('target'=>'_blank'));?></li>
    </ul>
</div>
<script>
$(function(){
	$("li.normal").hover(function(){
	  $(this).removeClass("normal").addClass("hover")
	},
	function(){
	  $(this).removeClass("hover").addClass("normal")
	});
});
</script>
<div class="integral"></div>

</body>
</html>
<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu("styles/my.css"), 'screen');
//cs()->registerCssFile(resBu('styles/components.css'), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/global.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('scripts/my.js'), CClientScript::POS_END);
?>
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

<?php $this->renderPartial('//public/tongji');?>