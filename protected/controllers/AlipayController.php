<?php

class AlipayController extends Controller
{
	public function actionPay($orderid)
	{
		$order = Order::model()->findByPk($orderid);
		if($order->pay_type != Shop::PAYTYPE_ONLINE) {
			throw new CHttpException(500);
		}
		if($order->is_pay == STATE_ENABLED) {
			echo '此订单已付款';
			exit;
		}
		require 'alipay/alipay_service.php';

		//构造要请求的参数数组
		$parameter = array(
//			"service"         => "create_partner_trade_by_buyer",	//接口名称，不需要修改
//	        "price"				=> $order->amountPrice,
//			"quantity"			=> 1,
//			"logistics_fee"		=> '0.00',
//			"logistics_type"	=> 'POST',
//			"logistics_payment"	=> 'BUYER_PAY',
			
		   	"service"         => "create_direct_pay_by_user",	//接口名称，不需要修改
		  	"payment_type"    => "1",               			//交易类型，不需要修改
		
			//获取配置文件中的值
			"partner"         => param('alipayPartner'),
			"seller_email"    => param('alipaySeller'),
			"return_url"      => aurl('alipay/return'),
		  	"notify_url"      => aurl('alipay/notify'),
		   	"_input_charset"  => 'utf-8',
		  	"show_url"        => aurl('order/view', array('orderid'=>$orderid)),
		
		   	//从订单数据中动态获取到的必填参数
		  	"out_trade_no"    => $order->orderSn,		//请与贵网站订单系统中的唯一订单号匹配
		   	"subject"         => $order->orderSn,		//订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
		  	"body"            => '来自我爱外卖网的商品',	//订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
			"total_fee"       => $order->amountPrice,	//订单总金额，显示在支付宝收银台里的“应付总额”里
		   	
		   	//扩展功能参数——网银提前
		    //"paymethod"	  => 'directPay', 			//默认支付方式，四个值可选：bankPay(网银); cartoon(卡通); directPay(余额); CASH(网点支付)
		    //"defaultbank"	  => '', 					//默认网银代号，代号列表见http://club.alipay.com/read.php?tid=8681379
		
		   	//扩展功能参数——防钓鱼
		    //"anti_phishing_key"	=> $encrypt_key,		//防钓鱼时间戳，初始值
			//"exter_invoke_ip"  	=> $exter_invoke_ip,	//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
		
		   	//扩展功能参数——分润(若要使用，请取消下面两行注释)
		 	"royalty_type"   		=> "10",	  			//提成类型，不需要修改
		  	"royalty_parameters" 	=> "bevin1984@gmail.com^0.1^分润备注一",
		 	//提成信息集，与需要结合商户网站自身情况动态获取每笔交易的各分润收款账号、各分润金额、各分润说明。最多只能设置10条
			//提成信息集格式为：收款方Email_1^金额1^备注1|收款方Email_2^金额2^备注2
		
		  	//扩展功能参数——自定义超时(若要使用，请取消下面一行注释)。该功能默认不开通，需联系客户经理咨询
			//"it_b_pay"	    	=> "1c",	  			//超时时间，不填默认是15天。八个值可选：1h(1小时),2h(2小时),3h(3小时),1d(1天),3d(3天),7d(7天),15d(15天),1c(当天)
		
			//扩展功能参数——自定义参数
			//"buyer_email"	    	=> $buyer_email,		//默认买家支付宝账号
		   	//"extra_common_param"	=> $extra_common_param	//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
		);

		$alipay = new alipay_service($parameter, param('alipaySecurity'), 'MD5');
		$url = $alipay->create_url();
		echo "<script>window.location =\"$url\";</script>";
	}
	
	public function actionReturn()
	{
		require_once("alipay/alipay_notify.php");
		$alipay = new alipay_notify(param('alipayPartner'), param('alipaySecurity'), 'MD5', 'utf-8', 'http');
		$verify_result = $alipay->return_verify();
		if($verify_result) {
		    $orderSn = $_GET['out_trade_no'];
		    $orderAmount = $_GET['total_fee'];
		    $orderid = Order::getOrderId($orderSn);
		    $order = Order::model()->findByPk($orderid);
		    if($order) {
			    if($_GET['trade_status'] == 'TRADE_FINISHED' ||$_GET['trade_status'] == 'TRADE_SUCCESS') {
			    	if($order->is_pay==STATE_ENABLED) {
			    		exit('此订单已付过款');
			    	}
			    	// 如果金额与订单金额符合 那么改变订单状态
			    	if($orderAmount >= $order->amount) {
			    		Order::model()->updateByPk($orderid, array('is_pay'=>1));
			    		// 支付操作成功写入到日志
				    	$paylog = new UserPayLog();
				    	$paylog->user_id = $order->user_id;
				    	$paylog->order_id = $orderid;
				    	$paylog->pay_price = $orderAmount;
				    	$paylog->save();
				    	
				    	$this->breadcrumbs = array(
							'我的订单' => url('my/order/list'),
					        '支付成功',
					    );
					    $this->pageTitle = '支付成功';
					    $this->setPageKeyWords($this->pageTitle);
					    $this->setPageDescription($this->pageTitle);
						$this->render('return_success', array(
							'order'=>$order
						));
						exit;
			    	}
			    }
		    }
		}
		$this->breadcrumbs = array(
			'我的订单' => url('my/order/list'),
	        '支付失败',
	    );
	    $this->pageTitle = '支付失败';
	    $this->setPageKeyWords($this->pageTitle);
	    $this->setPageDescription($this->pageTitle);
		$this->render('return_fail');
	}
	
	public function actionNotify()
	{
		require_once("alipay/alipay_notify.php");
		$alipay = new alipay_notify(param('alipayPartner'), param('alipaySecurity'), 'MD5', 'utf-8', 'http');
		$verify_result = $alipay->return_verify();
		if($verify_result) {
		    $orderSn = $_GET['out_trade_no'];
		    $orderAmount = $_GET['total_fee'];
		    $orderid = Order::getOrderId($orderSn);
		    $order = Order::model()->findByPk($orderid);
		    if(!$order) {
		    	exit('不存在此订单');
		    }
		    if($_GET['trade_status'] == 'TRADE_FINISHED' ||$_GET['trade_status'] == 'TRADE_SUCCESS') {
		    	if($order->is_pay==STATE_ENABLED) {
		    		exit('此订单已付过款');
		    	}
		    	// 如果金额与订单金额符合 那么改变订单状态
		    	if($orderAmount >= $order->amount) {
		    		Order::model()->updateByPk($orderid, array('is_pay'=>1));
		    		// 支付操作成功写入到日志
			    	$paylog = new UserPayLog();
			    	$paylog->user_id = $order->user_id;
			    	$paylog->order_id = $orderid;
			    	$paylog->pay_price = $orderAmount;
			    	$paylog->save();
			    	echo "success";
		    	} else {
		    		echo "success";
		    	}
		    } else {
		     	echo "success";
		     	exit;
		    }
		}
		else {
			// 订单错误
		    echo "fail";
		}
	}
}