<?php

$shop = array(
    array('label' => '待审商铺', 'url'=>url('admin/shop/unverify')),
    array('label' => '许可证审核', 'url'=>url('admin/shop/approve')),
    array('label' => '最近加盟', 'url'=>url('admin/shop/today')),
    array('label' => '商铺查询', 'url'=>url('admin/shop/search')),
    array('label' => '全部商铺', 'url'=>url('admin/shop/all')),
    array('label' => '商铺评论', 'url'=>url('admin/shop/shopcomment')),
    array('label' => '商品评论', 'url'=>url('admin/shop/goodsratelog')),
    array('label' => '商铺统计', 'url'=>url('admin/shop/statistics')),
    array('label' => '用户推荐商铺', 'url'=>url('admin/shop/shopsuggest')),
    array('label' => '添加临时商铺', 'url'=>url('admin/shopinside/create')),
    array('label' => '临时商铺列表', 'url'=>url('admin/shopinside/list')),
//    '/',
//    array('label' => '商铺分类', 'url'=>url('admin/shop/category')),
);

$order = array(
    array('label' => '今日订单', 'url'=>url('admin/order/today')),
    array('label' => '订单查询', 'url'=>url('admin/order/search')),
    array('label' => '待审订单', 'url'=>url('admin/order/approve')),
    array('label' => '待审取消订单', 'url'=>url('admin/order/customercancelstate')),
    array('label' => '电话处理订单', 'url'=>url('admin/order/phoneorder')),
    '/',
    array('label' => '未加工订单', 'url'=>url('admin/order/handleno')),
    array('label' => '加工中订单', 'url'=>url('admin/order/handleing')),
    array('label' => '配送中订单', 'url'=>url('admin/order/dispatching')),
    array('label' => '已完成订单', 'url'=>url('admin/order/finish')),
    array('label' => '申请取消订单', 'url'=>url('admin/order/cancelstate')),
    array('label' => '已取消订单', 'url'=>url('admin/order/cancel')),
    array('label' => '无效订单', 'url'=>url('admin/order/invalid')),
    array('label' => '订单统计', 'url'=>url('admin/order/statistics')),
);

$location = array(
    array('label' => '待审楼宇', 'url'=>url('admin/building/unverify')),
    array('label' => '楼宇查询', 'url'=>url('admin/building/search')),
    array('label' => '添加楼宇', 'url'=>url('admin/building/edit')),
    array('label' => '楼宇统计', 'url'=>url('admin/building/statistics')),
    '/',
    array('label' => '待审地址', 'url'=>url('admin/location/unverify')),
    array('label' => '地址查询', 'url'=>url('admin/location/search')),
    array('label' => '添加地址', 'url'=>url('admin/location/edit')),
    array('label' => '地址统计', 'url'=>url('admin/location/statistics')),
    '/',
    array('label' => '行政区域列表', 'url'=>url('admin/district/list')),
    array('label' => '添加行政区域', 'url'=>url('admin/district/edit')),
    '/',
    array('label' => '地图区域列表', 'url'=>url('admin/region/list')),
    array('label' => '添加地图区域', 'url'=>url('admin/region/edit')),
    '/',
    array('label' => '搜索记录查看', 'url'=>url('admin/searchLog/search')),
    array('label' => '搜索记录统计', 'url'=>url('admin/searchLog/statistics')),
);

$user = array(
    array('label' => '用户查询', 'url'=>url('admin/user/search')),
//    array('label' => '用户列表', 'url'=>url('admin/user/list')),
    array('label' => '禁止用户', 'url'=>url('admin/user/denyuser')),
    array('label' => '待认证用户', 'url'=>url('admin/user/approve')),
    '/',
    array('label' => '管理人员', 'url'=>url('admin/user/team')),
//    array('label' => '用户组', 'url'=>url('admin/user/group')),
//    array('label' => '权限', 'url'=>url('admin/user/auth')),
//    array('label' => '禁止IP', 'url'=>url('admin/user/denyip')),
    '/',
    array('label' => '用户邀请列表', 'url' => url('admin/inviter/list')),
    array('label' => '邀请隐藏码列表', 'url' => url('admin/inviterhidecode/list')),
    array('label' => '添加邀请隐藏码', 'url' => url('admin/inviterhidecode/create')),
);

$other = array(
    array('label' => '今日团购', 'url'=>url('admin/tuannav/today')),
    array('label' => '团购列表', 'url'=>url('admin/tuannav/list')),
    array('label' => '添加团购', 'url'=>url('admin/tuannav/create')),
    array('label' => '团购网管理', 'url'=>url('admin/tuannav/tuandata')),
    array('label' => '团购网添加', 'url'=>url('admin/tuannav/tuancreate')),
    array('label' => '二手交易管理', 'url'=>url('admin/tuannav/tuansecond')),
    array('label' => '用户举报管理', 'url'=>url('admin/tuannav/report')),
    array('label' => '用户推荐', 'url'=>url('admin/tuannav/post')),
    '/',
    array('label' => '秒杀列表', 'url'=>url('admin/miaosha/list')),
    array('label' => '添加秒杀', 'url'=>url('admin/miaosha/edit')),
    /*
    '/',
    array('label' => '广告列表', 'url'=>url('admin/ad/list')),
    '/',
    array('label' => '友情链接', 'url'=>url('admin/friendlink/friend')),
    array('label' => '添加友情链接', 'url'=>url('admin/friendlink/create')),
    */
);

$system = array(
//    array('label' => '网站公告', 'url'=>'#'),
    array('label' => '管理记录', 'url'=>url('admin/manage/record')),
    array('label' => '参数设置', 'url'=>url('admin/setting/list')),
    '/',
    array('label' => '用户纠错', 'url'=>url('admin/correction/list')),
);

$tool = array(
    array('label' => '网站公告1', 'url'=>'#'),
    array('label' => '网站通知', 'url'=>'#'),
    array('label' => '更新缓存', 'url'=>'#'),
    array('label' => '积分策略', 'url'=>'#'),
    array('label' => '数据库', 'url'=>'#'),
    array('label' => '管理记录', 'url'=>'#'),
);

$printer = array(
    array('label' => '打印机列表', 'url' => url('admin/printer/list')),
    array('label' => '添加打印机', 'url' => url('admin/printer/create')),
);

$shortcut = array(
    array('label' => '待审商铺', 'url'=>url('admin/shop/unverify')),
    array('label' => '今日订单', 'url'=>url('admin/order/today')),
    array('label' => '待审楼宇', 'url'=>url('admin/building/unverify')),
    array('label' => '用户查询', 'url'=>url('admin/user/search')),
    '/',
    array('label' => '今日团购', 'url'=>url('admin/tuannav/list')),
    array('label' => '添加团购', 'url'=>url('admin/tuannav/create')),
);

$caiji = array(
	array('label' => '采集列表', 'url'=>url('admin/caiji/list')),
);

return array(
    'shop' => array(
    	'label'  	=> '商铺',
        'url'    	=> url('admin/default/left', array('sub'=>'shop')),
    	'sub'    	=> $shop,
        'show'		=> true,
    ),
    'order' 		=> array(
    	'label'  	=>'订单',
        'url'    	=> url('admin/default/left', array('sub'=>'order')),
    	'sub'		=> $order,
        'show'		=> true,
    ),
    'location' 		=> array(
    	'label'  	=>'楼宇',
        'url'    	=> url('admin/default/left', array('sub'=>'location')),
    	'sub'		=> $location,
        'show'		=> true,
    ),
    'user' => array(
    	'label'		=> '用户',
        'url'    	=> url('admin/default/left', array('sub'=>'user')),
    	'sub'		=> $user,
        'show'		=> true,
    ),
    
    'printer' => array(
    	'label'		=> '打印机',
        'url'    	=> url('admin/default/left', array('sub'=>'printer')),
    	'sub'		=> $printer,
        'show'		=> true,
    ),
    'other' => array(
    	'label'		=> '综合',
    	'url'		=> url('admin/default/left', array('sub'=>'other')),
    	'sub'		=> $other,
        'show'		=> true,
    ),
    'system' => array(
    	'label'		=> '系统',
    	'url'		=> url('admin/default/left', array('sub'=>'system')),
    	'sub'		=> $system,
        'show'		=> true,
    ),
    'tool' => array(
    	'label'		=> '工具',
    	'url'		=> url('admin/default/left', array('sub'=>'tool')),
    	'sub'		=> $tool,
        'show'		=> false,
    ),
    'shortcut' => array(
    	'label'		=> '快捷方式',
    	'url'		=> url('admin/default/left', array('sub'=>'shortcut')),
    	'sub'		=> $shortcut,
        'show'		=> true,
    ),
    'caiji' => array(
    	'label'		=> '采集',
    	'url'		=> url('admin/default/left', array('sub'=>'caiji')),
    	'sub'		=> $caiji,
    	'show'		=> true,
    )
);