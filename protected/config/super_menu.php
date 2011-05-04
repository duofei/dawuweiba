<?php

$shop = array(
    array('label' => '待审商铺', 'url'=>url('super/shop/unverify')),
    array('label' => '许可证审核', 'url'=>url('super/shop/approve')),
    array('label' => '最近加盟', 'url'=>url('super/shop/today')),
    array('label' => '商铺统计', 'url'=>url('super/shop/statistics')),
    '/',
    array('label' => '商铺分类', 'url'=>url('super/shop/category')),
);

$order = array(
    array('label' => '今日订单', 'url'=>url('super/order/today')),
    array('label' => '订单统计', 'url'=>url('super/order/statistics')),
);

$location = array(
    array('label' => '待审楼宇', 'url'=>url('super/building/unverify')),
    array('label' => '楼宇查询', 'url'=>url('super/building/search')),
    array('label' => '楼宇统计', 'url'=>url('super/building/statistics')),
    '/',
    array('label' => '待审地址', 'url'=>url('super/location/unverify')),
    array('label' => '地址查询', 'url'=>url('super/location/search')),
    array('label' => '地址统计', 'url'=>url('super/location/statistics')),
    '/',
    array('label' => '搜索记录查看', 'url'=>url('super/searchLog/search')),
    array('label' => '搜索记录统计', 'url'=>url('super/searchLog/statistics', array('id'=>'1'))),
);

$user = array(
    array('label' => '用户查询', 'url'=>url('super/user/search')),
    array('label' => '用户列表', 'url'=>url('super/user/list')),
    array('label' => '禁止用户', 'url'=>url('super/user/denyuser')),
    '/',
    array('label' => '管理人员', 'url'=>url('super/user/team')),
    array('label' => '用户组', 'url'=>url('super/user/group')),
    array('label' => '权限', 'url'=>url('super/user/auth')),
    '/',
    array('label' => '禁用IP', 'url'=>url('super/denyip/create')),
    array('label' => '禁用IP列表', 'url'=>url('super/denyip/list')),
);

$cityadmin = array(
    array('label' => '城市列表', 'url'=>url('super/cityadmin/citylist')),
    array('label' => '增加城市', 'url'=>url('super/cityadmin/addcity')),
    array('label' => '管理员列表', 'url'=>url('super/cityadmin/managerlist')),
    array('label' => '增加管理员', 'url'=>url('super/cityadmin/addmanager')),
);

$other = array(
    array('label' => '广告列表', 'url'=>url('super/ad/list')),
    '/',
    array('label' => '友情链接', 'url'=>url('super/friendlink/friend')),
    array('label' => '添加友情链接', 'url'=>url('super/friendlink/create')),
    '/',
    array('label' => '礼品列表', 'url'=>url('super/gift/list')),
    array('label' => '添加礼品', 'url'=>url('super/gift/create')),
    array('label' => '礼品兑换记录', 'url'=>url('super/gift/exchangelog')),
);

$system = array(
    array('label' => '网站公告', 'url'=>'#'),
    array('label' => '网站通知', 'url'=>'#'),
    array('label' => '更新缓存', 'url'=>'#'),
    array('label' => '积分策略', 'url'=>'#'),
    array('label' => '数据库', 'url'=>'#'),
    array('label' => '管理记录', 'url'=>'#'),
);

$tool = array(
    array('label' => '网站公告1', 'url'=>'#'),
    array('label' => '网站通知', 'url'=>'#'),
    array('label' => '更新缓存', 'url'=>'#'),
    array('label' => '积分策略', 'url'=>'#'),
    array('label' => '数据库', 'url'=>'#'),
    array('label' => '管理记录', 'url'=>'#'),
);

$shortcut = array(
    array('label' => '待审商铺', 'url'=>url('super/shop/unverify')),
    array('label' => '今日订单', 'url'=>url('super/order/today')),
    array('label' => '待审楼宇', 'url'=>url('super/building/unverify')),
    array('label' => '用户查询', 'url'=>url('super/user/search')),
);

return array(
    'shop' => array(
    	'label'  	=> '商铺',
        'url'    	=> url('super/default/left', array('sub'=>'shop')),
    	'sub'    	=> $shop,
        'show'		=> true,
    ),
    'order' 		=> array(
    	'label'  	=>'订单',
        'url'    	=> url('super/default/left', array('sub'=>'order')),
    	'sub'		=> $order,
        'show'		=> true,
    ),
    'location' 		=> array(
    	'label'  	=>'楼宇',
        'url'    	=> url('super/default/left', array('sub'=>'location')),
    	'sub'		=> $location,
        'show'		=> true,
    ),
    'user' => array(
    	'label'		=> '用户',
        'url'    	=> url('super/default/left', array('sub'=>'user')),
    	'sub'		=> $user,
        'show'		=> true,
    ),
    'cityadmin' => array(
    	'label'		=> '城市代理',
    	'url'    	=> url('super/default/left', array('sub'=>'cityadmin')),
    	'sub'		=> $cityadmin,
        'show'		=> true,
    ),
    'other' => array(
    	'label'		=> '综合',
    	'url'		=> url('super/default/left', array('sub'=>'other')),
    	'sub'		=> $other,
        'show'		=> true,
    ),
    'system' => array(
    	'label'		=> '系统',
    	'url'		=> url('super/default/left', array('sub'=>'system')),
    	'sub'		=> $system,
        'show'		=> true,
    ),
    'tool' => array(
    	'label'		=> '工具',
    	'url'		=> url('super/default/left', array('sub'=>'tool')),
    	'sub'		=> $tool,
        'show'		=> true,
    ),
    'shortcut' => array(
    	'label'		=> '快捷方式',
    	'url'		=> url('super/default/left', array('sub'=>'shortcut')),
    	'sub'		=> $shortcut,
        'show'		=> true,
    ),
    
);