<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title>baidu app test</title>
<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
</head>
<body>
<div class="wrapper">
	<?php echo $content;?>
</div>
<script type="text/javascript">
XN_RequireFeatures(["Connect"], function(){
	XN.Main.init("e7785c96a90c4d63bbeaa44909ada1f7", "/renren/xd_receiver.html");
    var callback = function(){
        top.location = "http://apps.renren.com/renrenwm/";
    };

    var cancel = function(){
		alert('Authorize failed!');
    };
    
    XN.Connect.get_status().waitUntilReady(function(login_state) {
        if (login_state != XN.ConnectState.connected) {
        	XN.Connect.showAuthorizeAccessDialog(callback, cancel);
        }
    });
});
</script>
</body>
</html>
