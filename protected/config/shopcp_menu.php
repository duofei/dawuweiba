<?php
if($_SESSION['shop']->buy_type == Shop::BUYTYPE_PRINTER) {
	$orderUrl = url('shopcp/orderprinter/finish');
} else {
	$orderUrl = url('shopcp/order/handleing');
}
$common = array(
    array('label' => '订单管理', 'url'=>$orderUrl),
    array('label' => '用户留言', 'url'=>url('shopcp/question/noreply')),
    array('label' => '更改营业状态', 'url'=>url('shopcp/shop/state')),
//    array('label' => '团购订单管理', 'url'=>url('shopcp/order/list')),
);

$shopSetting = array(
    array('label' => '商品管理', 'url'=>url('shopcp/goods/list')),
    array('label' => '每日菜单', 'url'=>url('shopcp/goods/daylist')),
    array('label' => '配送员管理', 'url'=>url('shopcp/delivery/list')),
    array('label' => '优惠信息管理', 'url'=>url('shopcp/promotion/list')),
    array('label' => '优惠券管理', 'url'=>url('shopcp/voucher/list')),
    array('label' => '店铺设置', 'url'=>url('shopcp/shop/profile')),
    array('label' => '商家认证', 'url'=>url('shopcp/shop/approve')),
    array('label' => '修改密码', 'url'=>url('shopcp/shop/editpassword')),
);
if($_SESSION['shop']->buy_type == Shop::BUYTYPE_TELPHONE) {
	unset($shopSetting[4]);
}
if ($_SESSION['shop']->category_id != ShopCategory::CATEGORY_FOOD) {
	unset($shopSetting[1]);
}

$data = array(
    array('label' => '销量统计', 'url'=>url('shopcp/statistics/sales')),
    array('label' => '用户统计', 'url'=>url('shopcp/statistics/user')),
    array('label' => '配送员统计', 'url'=>url('shopcp/statistics/delivery')),
);

$other = array(
    array('label' => '信用评价', 'url'=>url('shopcp/shopCredit/list')),
    array('label' => '网店合同', 'url'=>url('shopcp/shop/contract')),
    array('label' => '更新日志', 'url'=>url('shopcp/shop/log')),
    array('label' => '我的网店', 'url'=>url('shop/show', array('shopid'=>$_SESSION['shop']->id)), 'target'=>'_blank'),
);

/* 如果是业务员的话只显示如下菜单 */
if($_SESSION['super_shop'] && !$_SESSION['manage_city_id'] && !$_SESSION['super_admin']) {
	return array(
		array(
			'label'  	=>'商家设置',
    		'sub'		=> array(
			    array('label' => '商品管理', 'url'=>url('shopcp/goods/list')),
				array('label' => '每日菜单', 'url'=>url('shopcp/goods/daylist')),
			    array('label' => '优惠信息管理', 'url'=>url('shopcp/promotion/list')),
			    array('label' => '店铺设置', 'url'=>url('shopcp/shop/profile')),
				array('label' => '我的网店', 'url'=>url('shop/show', array('shopid'=>$_SESSION['shop']->id)), 'target'=>'_blank'),
			),
        	'show'		=> true,
		)
	);
}

return array(
    array(
    	'label'  	=> '常用功能',
    	'sub'    	=> $common,
        'show'		=> true,
    ),
    array(
    	'label'  	=>'商家设置',
    	'sub'		=> $shopSetting,
        'show'		=> true,
    ),
    array(
    	'label'  	=>'数据统计',
    	'sub'		=> $data,
        'show'		=> true,
    ),
    array(
    	'label'		=> '其他',
    	'sub'		=> $other,
        'show'		=> true,
    ),
);