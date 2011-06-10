<?php
class OrderController extends Controller
{
	public function actionView($orderid, $ordersn = null)
	{
	    $orderid = (int)$orderid;
	    $ordersn = strip_tags(trim($ordersn));
	    
		$order = Order::model()->with('orderGoods', 'shop')->findByPk($orderid);
		if(null === $order) throw new CHttpException(404);
		
		$atid = Location::getLastVisit();
	    $location = Location::model()->findByPk($atid);
	    
	    if ($location)
    	    $this->breadcrumbs = array(
    			$location->name => url('shop/list', array('atid'=>$location->id, 'cid'=>$order->shop->category_id)),
    			$order->shop->shop_name => $order->shop->relativeUrl,
    			'订单处理完成'
    		);
    	else
	        $this->breadcrumbs = array(
    			$order->shop->shop_name => $order->shop->relativeUrl,
    			'订单处理完成'
    	    );
    	
    	$integral['user_integral'] = $_SESSION['integral'];
    	$integral['lastintegral'] = $integral['user_integral'] - param('markUserAddOrder');
    	$integral['min_integral'] = $integral['lastintegral'];
    	$integral['max_integral'] = $integral['user_integral'] + floor(param('markUserAddOrder')/3);
    	$integral['mid_integral'] = $integral['lastintegral'] + floor(($integral['max_integral'] - $integral['min_integral'])/2);
    	
    	$this->pageTitle = '订单处理完成';
	    $this->render('view', array('order' => $order, 'integral'=>$integral));
	}

}