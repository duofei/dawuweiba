<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title>baidu app test</title>
<style type="text/css">
body {margin:0px;}
</style>
<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
</head>
<body>
<div class="wrapper">
	<?php echo $content;?>
</div>
<script type="text/javascript">
XN_RequireFeatures(["Connect"], function(){
    XN.Main.init("e7785c96a90c4d63bbeaa44909ada1f7", "/renren/xd_receiver.html");
});
</script>
</body>
</html>
