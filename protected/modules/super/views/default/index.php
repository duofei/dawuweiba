<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo CHtml::metaTag('text/html; charset=' . app()->charset, null, 'Content-Type');?>
<title>我爱外卖 管理员控制面板</title>
</head>
<frameset rows="40,*" frameborder="0" border="0">
    <frame src="<?php echo url('super/default/top');?>" scrolling="no" frameborder="0" />
    <frameset cols="150,*">
    	<frame src="<?php echo url('super/default/left', array('sub'=>'shortcut'));?>" name="left" frameborder="0" />
    	<frame src="<?php echo url('super/default/start');?>" name="main" frameborder="0" />
	</frameset>
</frameset>
</html>