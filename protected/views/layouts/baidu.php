<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title>baidu app test</title>
</head>
<body>
<div class="main540" id="canvas">
    <div style=" width:535px; margin:0 auto;">
        <div class="toolbar">
            <div style="float:left; margin-right:10px;"><a class="houtui" href="javascript:history.back();">后退</a></div>
            <div style="float:left;"><a class="qianjin" href="javascript:history.forward();">前进</a></div>
            <div style="float:right; "><a class="shouye" href="javascript:void(0);" onclick="location.href='<?php echo aurl('bdapp');?>';">首页</a></div>
            <!--<div style="float:right; margin-right:10px; "><a class="denglu">登陆</a></div>-->
            <div class="clear"></div>
        </div>
        <div class="container"><?php echo $content;?></div>
        <div><img src="<?php echo resBu('baidu/img/pic02.png');?>" /></div>
    </div>
</div>
<script src="http://app.baidu.com/static/appstore/scripts/bdjs_client-1.0.js"></script>
</body>
</html>

<?php
cs()->registerCssFile(resBu('baidu/styles/baidu.css'), 'screen');
cs()->registerCoreScript('jquery');
?>
<script type="text/javascript">
$(function(){
	ResizeIframe();
});

function ResizeIframe()
{
	try {
        var h = $('#canvas').height();
        if (h > 0) {
            bdjs.clearAutoHeight();
            bdjs.setHeight(h);
        }
        else {
            setTimeout("ResizeIframe", 50);
        }
	}
	catch(e) {}
}
</script>