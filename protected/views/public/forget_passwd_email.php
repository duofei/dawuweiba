<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我爱外卖网密码找回邮件</title>
</head>
<body style="color:#666; font:14px Verdana, Lucida, Helvetica, Arial, sans-serif; background:#ececec;">

<table width="652px" style="font-size:14px; border-collapse:collapse; border:1px solid #c9c9c9; background:#fff; line-height:24px; color:#666;">
	<tr >
         <td height="65px"><img src="<?php echo resBu('images/mail.jpg');?>" width="650" height="65" /></td>
    </tr>
    <tr>
    	<td style="padding:20px 20px 0 20px; color:#006dd4; font-weight:bold">尊贵的 <?php echo $username;?> 您好:</td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px;  ">这封信是由 <?php echo app()->name;?> 发送的。</td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px">您收到这封邮件，是因为在<?php echo app()->name;?>上这个邮箱地址被登记为用户邮箱，且该用户请求使用电子邮箱密码重置功能所致。</td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px">如果您没有提交密码重置的请求或不是我们网站的注册用户，请立即忽略并删除这封邮件。只在您确认需要重置密码的情况下，才继续阅读下面的内容。</td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px; color:#006dd4; font-weight:bold">密码重置说明：</td>
    </tr>
    <tr>
        <td style="padding:0 20px">您只需在提交请求后的三天之内，通过点击下面的链接重置您的密码：</td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px"><?php echo l($setPasswdUrl, $setPasswdUrl, array('style'=>'color:#006dd4'));?></td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px">如果上面的地址在您的信箱中并没有显示为一个超级连接，您可以复制上面的地址到您浏览器的地址栏中，然后点击"转到"按钮。</td>
    </tr>
    <tr>
        <td style="padding:20px 20px 0 20px">上面的页面打开后，输入新的密码后提交，之后您即可使用新的密码登录网站了。</td>
    </tr>
    <tr>
        <td style="padding:0 20px">您可以在用户控制面板中随时修改您的密码。</td>
    </tr>
    <tr>
  	    <td style="padding:20px;  ">我爱外卖网全体团队成员祝您健康愉快!</td>
    </tr>
    <tr>
        <td style="padding:0 20px "><a href="http://www.52wm.com" style="color:#006dd4;">我爱外卖网_爱生活 爱外卖</a></td>
    </tr>
    <tr>
        <td style="padding:0 20px">网址：<a href="http://www.52wm.com" style="color:#006dd4;">http://www.52wm.cn/</a></td>
    </tr>
    <tr>
        <td style="padding:0 20px">客服电话：0531-5555547</td>
    </tr>
    <tr>
        <td style="padding:0 20px 20px 20px">客服邮箱：<a href="mailto:contact@52wm.com" style="color:#006dd4;" target="_blank">contact@52wm.com</a></td>
    </tr>
</table>
</body>
</html>

