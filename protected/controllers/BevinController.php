<?php
//set_time_limit(3600);
class BevinController extends Controller
{

	public function actionTest()
	{
//		$criteria = new CDbCriteria();
//		$criteria->addCondition('clear_password !="xxxxxx"');
//		$criteria->addColumnCondition(array('super_shop'=>STATE_DISABLED, 'super_admin'=>STATE_DISABLED));
//		$criteria->addNotInCondition('id', array(5,13,16,60,103,145,168,169,192,193,194,195,196,197,206,207,209,210,212,224,225,228,229,230,231,288,314,330,349,352,381,398,406,412,413,417,442,443,447,455,478,479,480,482,483,498,501,509,526,527,535,536,539,541,542,560,563,566,570,578,579,580,595,599,1578,1592,1611,1625,1639,1961,2115));
//		$criteria->select = 'id, email, clear_password, super_shop, super_admin';
//		$criteria->addBetweenCondition('id', '118', '200');
//		$users = User::model()->findAll($criteria);
//		echo count($users);
//		foreach ($users as $v) {
//			$uid = $v->id;
//			$email = $v->email;
//			echo 'UID:' . $uid . '|||Email:' . $email . '<BR>';
//		}
		
//		$array = array(
//			'36' => 'bevin1984@gmail.com',
//		);
//		foreach ($array as $k=>$v) {
//			$uid = $k;
//			$email = $v;
//
//			// 生成白吃点
//			$key = 'getBcnum_' . $uid;
//			$valueb = md5('getBcnum_' . $uid . time());
//			app()->fileCache->set($key, $valueb);
//		}
//
//		exit;
//		$key = 'aaaaaa';
//		$valueq = md5('aaaaa' . time());
//		app()->fileCache->set($key, $valueq);
//		echo app()->fileCache->get($key);
	}
	
	public function actionSendmail()
	{
		echo time();
		exit;
//		//最后一个用户id 2382
//		$criteria = new CDbCriteria();
//		$criteria->addCondition('clear_password !="xxxxxx"');
//		$criteria->addBetweenCondition('id', '2001', '2500');
//		$criteria->addColumnCondition(array('super_shop'=>STATE_DISABLED, 'super_admin'=>STATE_DISABLED));
//		$criteria->addNotInCondition('id', array(5,13,16,60,103,145,168,169,192,193,194,195,196,197,206,207,209,210,212,224,225,228,229,230,231,288,314,330,349,352,381,398,406,412,413,417,442,443,447,455,478,479,480,482,483,498,501,509,526,527,535,536,539,541,542,560,563,566,570,578,579,580,595,599,1578,1592,1611,1625,1639,1961,2115));
//		$criteria->select = 'id, email, clear_password, super_shop, super_admin';
//		$users = User::model()->findAll($criteria);
		foreach ($users as $v) {
			$uid = $v->id;
			$email = $v->email;
			// 生成订阅
			$key = 'quit_subscription_' . $uid;
			$valueq = md5('quit_subscription_' . $uid . time());
			app()->fileCache->set($key, $valueq);
			
			// 生成白吃点
			$key = 'getBcnum_' . $uid;
			$valueb = md5('getBcnum_' . $uid . time());
			app()->fileCache->set($key, $valueb);
	
			if($email) {
				// 邮件内容
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
				//SendMail::addMailQueue($mailtitle, $mailhtml, $email);
			}
			
			$msgcontent = '"我爱外卖" 正式上线，为感谢用户支持， 送您10点白吃点（1白吃点=1元）
请于7月31日前领取，过期作废。 <a href="' . aurl('user/getbcnum', array('uid'=>$uid, 'sid'=>$valueb)) . '" class="f14px fb">马上领取</a>';
			//Message::sendMessage($uid, $mailtitle, $msgcontent, 0);

		}
	}
}