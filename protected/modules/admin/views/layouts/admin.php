<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title><?php echo $this->pageTitle;?>_我爱外卖网</title>
<?php echo CHtml::script('BU = \'' . abu() . '\'; RESBU = \'' . resBu() . '\'; SBU = \'' . sbu() . '\'');?>
</head>
<body>
<?php echo $content;?>
<div class="none fb color-black ajax-note" id="ajax-note"></div>
</body>
</html>
<?php
cs()->registerCssFile(resBu("admin/styles/admincp.css"), 'screen');
cs()->registerCoreScript('jquery');
cs()->registerScriptFile(resBu('scripts/jquery.tmpl.min.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('scripts/global.js'), CClientScript::POS_END);
cs()->registerScriptFile(resBu('admin/scripts/admincp.js'), CClientScript::POS_END);
?>

<script type="text/javascript">
/*<![CDATA[*/
$(function(){
	$(window).scroll(function(){
		var tthis = $(this);
		$('#ajax-note').css('top', tthis.scrollTop());
	});
});
/*]]>*/
</script>