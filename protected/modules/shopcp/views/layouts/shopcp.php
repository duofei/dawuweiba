<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title><?php echo $this->pageTitle;?> - 我爱外卖网商家管理系统</title>
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

<div class="shoptop ma-b10px cwhite">
<?php echo CHtml::beginForm(url('shopcp/order/search'),'get',array('name'=>'edit'));?>
    <div class="fl pa-l20px fb lh40px">
    	<span class="f20px"><?php echo l('我爱外卖网', app()->homeUrl, array('target'=>'blank'))?>商家管理系统</span>
    	<?php echo CHtml::textField('Order[order_sn]', '', array('class'=>'txt')); ?>
    	<?php
		$this->widget('zii.widgets.jui.CJuiButton',
			array(
				'name' => 'search',
				'caption' => '订单号查询',
			)
		);
		?>
    	<?php echo l('高级查询', url('shopcp/order/list'), array('class'=>'f14px'));?>
	</div>
<?php echo CHtml::endForm();?>
    <ul class="fr ar lh40px subfl">
		<li><?php echo l($_SESSION['shop']->shop_name, $_SESSION['shop']->absoluteUrl, array('target'=>'_blank'));?>：<?php echo $_SESSION['shop']->businessStateText;?></li><li>|</li>
		<li><?php echo user()->name?></li><li>|</li>
		<li>服务器时间:<?php echo date(param('formatTime'), $_SERVER['REQUEST_TIME']);?></li><li>|</li>
		<li><?php echo l('安全退出', url('site/logout'))?></li>
	</ul>
<div class="clear"></div>
</div>

<div class="wrapper">
    <div class="left fl">
    	<?php if($_SESSION['super_shop']):?>
    	<div  class="border lh30px f14px ma-b10px pa-l10px">
    		<?php echo l('我的商铺列表', url('shopcp/shop/list'));?>
    		<br />
    		<?php echo l('添加新商铺', url('shopcp/shop/create'));?>
    		<br />
    		<?php echo l('地图查看商铺', url('shopcp/shop/ditu'));?>
    	</div>
    	<?php endif;?>
    	
        <div class="border lh30px f14px ma-b10px pa-l10px" style="<?php if($_SESSION['shop']->buy_type==Shop::BUYTYPE_PRINTER) echo 'display:none'?>">
        <?php echo l('<div class="fl">未加工订单(</div> <div class="fl" id="handlenoNum"></div>)', url('shopcp/order/handleno'), array('class'=>'block'));?>
        </div>
        <?php if($_SESSION['shop']):?>
        <?php $menus = require(app()->basePath . '/config/shopcp_menu.php');?>
        <?php foreach ($menus as $v):?>
        <div class="border f14px ma-b10px" >
            <dl class="cblack">
                <dt class="menu-title pa-l10px ma-b10px fb lh30px"><?php echo $v['label'];?></dt>
                <?php foreach ($v['sub'] as $m):?>
                <dd ><?php echo l($m['label'], $m['url'], array('class'=>'normal lh30px block pa-l10px', 'target'=>$m['target'] ? $m['target'] : '_self'));?></dd>
                <?php endforeach;?>
            </dl>
        </div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
	<div class="fl right lh24px ma-l10px">
        <?php echo $content;?>
    </div>
</div>
</body>
</html>

<?php
cs()->registerCssFile(resBu('styles/base.inc.css'), 'screen');
cs()->registerCssFile(resBu("styles/shopcp.css"), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/global.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('scripts/shopcp.js'), CClientScript::POS_END);
?>

<script type="text/javascript">
$(function(){
	getHandleNoCount();
	setInterval(getHandleNoCount, 30000);
});

function getHandleNoCount()
{
    var postUrl = '<?php echo url('shopcp/default/handleNoOrderNum');?>';
    $.post(postUrl,function(data){
        if (isNaN(data)) return false;
    	$("#handlenoNum").html(parseInt(data));
    });
}
</script>