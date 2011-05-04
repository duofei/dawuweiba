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
        <?php if (isset($_GET['kw'])):?>
        <?php echo CHtml::beginForm(url('at/search'), 'get');?>
        <div class="fl search-box ma-l10px lh15px">
            <input class="txt f16px fb cgray" name="kw" type="text" value="<?php echo strip_tags(str_replace(array(' ', '.', '。'),'',$_GET['kw']));?>" />
        	<input class="cred btn-two fb" type="submit" value="搜&nbsp;索" />
        </div>
        <?php echo CHtml::endForm();?>
        <?php endif;?>
        <div class="clear"></div>
    </div>
    <?php if ($this->taskTitle):?>
    <div class="topnav">
    	<h3 class="fl task-title ma-l30px lh24px f14px"><?php echo $this->taskTitle;?></h3>
	</div>
	<?php else:?>
	<div class="wrapper gray5pxline"></div>
	<?php endif;?>
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
cs()->registerCssFile(resBu("styles/search.css"), 'screen');
//cs()->registerCssFile(resBu('styles/components.css'), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/global.js'), CClientScript::POS_END);
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
		window.location = '<?php echo aurl('connect/renren');?>';
    });
    return false;
}
//-->
</script>
<?php endif;?>

<?php echo $this->renderPartial('/public/tongji');?>