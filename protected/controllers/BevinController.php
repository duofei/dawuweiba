<?php
//set_time_limit(3600);
class BevinController extends Controller
{

	public function actionTest()
	{
		$key = 'aaaaaa';
		$valueq = md5('aaaaa' . time());
		app()->fileCache->set($key, $valueq);
		echo app()->fileCache->get($key);
	}
	
	public function actionSendmail()
	{
		$array = array(
			//'4' => 'bevin1984@gmail.com',
			'41' => '57847014@qq.com',
			//'555' => 'davidabm@163.com'
		);
		foreach ($array as $k=>$v) {
			$uid = $k;
			$email = $v;
			// 生成订阅
			$key = 'quit_subscription_' . $uid;
			$valueq = md5('quit_subscription_' . $uid . time());
			app()->fileCache->set($key, $valueq);
			
			// 生成白吃点
			$key = 'getBcnum_' . $uid;
			$valueb = md5('getBcnum_' . $uid . time());
			app()->fileCache->set($key, $valueb);
	
			$mailhtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>白吃点</title>
	<style>
	img {border:0px; margin:0px; padding:0px;}
	</style>
	</head>
	<body>
	<table width="635" border="0" cellspacing="0" cellpadding="0" style="font-size:12px; line-height:20px;">
	  <tr>
	    <td height="30" colspan="2" align="center">请将该电子邮件地址添加至您的邮箱地址簿，以确保收到来自我爱外卖的电子邮件。</td>
	  </tr>
	  <tr>
	    <td><a href="http://www.52wm.com" target="_blank"><img src="http://res.52wm.com/images/sendmail/bcd_mail_r1_c1.jpg" width="535" height="36" /></a></td>
	    <td><a href="http://www.52wm.com/feedback.html" target="_blank"><img src="http://res.52wm.com/images/sendmail/bcd_mail_r1_c2.jpg" width="100" height="36" /></a></td>
	  </tr>
	  <tr>
	    <td colspan="2"><img src="http://res.52wm.com/images/sendmail/bcd_mail_r2_c1.jpg" width="635" height="151" /></td>
	  </tr>
	  <tr>
	    <td colspan="2"><img src="http://res.52wm.com/images/sendmail/bcd_mail_r3_c1.jpg" width="635" height="151" /></td>
	  </tr>
	  <tr>
	    <td colspan="2"><a href="' . aurl('user/getbcnum', array('uid'=>$uid, 'sid'=>$valueb)) . '" target="_blank"><img src="http://res.52wm.com/images/sendmail/bcd_mail_r4_c1.jpg" width="635" height="81" /></a></td>
	  </tr>
	  <tr>
	    <td colspan="2"><img src="http://res.52wm.com/images/sendmail/bcd_mail_r5_c1.jpg" width="635" height="81" /></td>
	  </tr>

	  <tr>
	    <td height="40" colspan="2" bgcolor="#E7E7E7" style="padding-left:55px;">如果此邮件打扰到了您，请<a href="' . aurl('user/quitsubscription', array('uid'=>$uid, 'sid'=>$valueq)) . '" target="_blank">点击这里退订。</a></td>
	  </tr>
	</table>
	</body>
	</html>';
			$mailtitle = '我爱外卖网送您价值10元的白吃点，请领取！';
			$result = SendMail::addMailQueue($mailtitle, $mailhtml, $email);

			$msgcontent = '"我爱外卖" 正式上线，为感谢用户支持， 送您10点白吃点（1白吃点=1元）
请于7月31日前领取，过期作废。 <a href="' . aurl('user/getbcnum', array('uid'=>$uid, 'sid'=>$valueb)) . '" class="f14px fb">马上领取</a>';
			Message::sendMessage($uid, $mailtitle, $msgcontent, 0);

		}
	}
}