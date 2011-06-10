<?php

return array(
    /*
     * 以下内容在更换域名或服务器迁移的时候可能需要修改
     */

    'keywords' => '%s[CITY]快餐,[CITY]外卖,[CITY]网上订餐,[CITY]订餐网,[CITY]蛋糕网,[CITY]电话叫外卖,[CITY]蛋糕,[CITY]快餐,[CITY]外卖[CITY]蛋糕,[CITY]好利来,[CITY]A里,[CITY]蛋糕打折,[CITY]蛋糕优惠,[CITY]午餐 ',
    'description' => '%s最好的[CITY]快餐,[CITY]外卖,[CITY]网上订餐信息就在我爱外卖网。我爱外卖网是一个功能型的同城服务，外卖交易平台，提供网络订餐、订蛋糕等便民服务。',
    
    // 登陆后返回的地址
    
    'staticBaseUrl' => 'http://s1.52wm.cn/', // 最后带 /
    'staticBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'attachments' . DS,
    // resource Url和路径
    'resourceBaseUrl' => 'http://res.52wm.cn/',
    'resourceBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'resources' . DS,
    // 系统数据文件目录
    'dataPath' => dirname(__FILE__) . DS . '..' . DS . 'data' . DS,

    // cookie 域
    'cookieDomain' => '.52wm.cn',
    // cookie路径
    'cookiePath' => '/',

    // 默认城市，要与City表中的对应
    'defaultCityId' => 1,
    // 默认城市名称，实际用不到
    'defaultCityName' => '济南',
    // 默认头像路径，相对于resBu()路径
    'defaultPortrait' => 'images/portrait.jpg',
    // 默认商铺缩略图路径，相对于resBu()路径
    'defaultShop' => 'images/shop.png',
    
    
	/*****************************************************************/
    /*
     * 以下内容如果没有必要的情况，请勿进行修改！！
     */
    'sinaApiKey' => '692781524',
    'sinaApiSecret' => '8c226416c62e0479ae1b451bb6d31e5d',
    'sinaApiCallback' => 'http://www.52wm.com/connect/sinat',
    'renrenApiKey' => '49e422d84b694b69ba5e3c5809db4102',
    'renrenApiSecert' => '7f886c49d1fe4f4bad93f68a3b1c2007',

    'qqtUrl' => 'http://t.qq.com/my52wm',
    'sinatUrl' => 'http://t.sina.com.cn/my52wm',
    'renrenUrl' => 'http://www.renren.com/52wm',
    'kaixin001Url' => 'http://www.kaixin001.com/home/?uid=90451083',

	// 地图选择
	'map' => 'google',	// google | mapabc
	// mapABC地图 APIKey
    'mapABCKey' => '87bd9850fba7f687d0d2bfb94ea3e43cb3fbc504fb10e8621c7b7049a0092b46186d27acda494552',

    // comm100 在线客服弹出页面地址
    'comm100Url' => 'http://chatserver.comm100.cn/ChatWindow.aspx?siteId=80015254&planId=1098&visitType=1&byHref=1',

    // 备案号
    'miibeian' => '京ICP备09098940号',
	// 管理员邮箱
	'adminEmail'=>'contact@52wm.com',

    // 客服电话
    'servicePhone' => '0531-55500071',

    // 是否开放评论
    'globalAllowComment' => true,
    // 默认评论是否需要审核
    'defaultCommentIsShow' => true,
    // 发表评论的间隔时间
    'commentInterval' => 10,
    // 找回密码有效期，默认3天
    'getPasswordUrlExpire' => 3*24*60*60,

    // 邮件优先级
    'priorityRegister' => 1000,
    'priorityForgetPasswd' => 1000,
    
    
    // 初始访问量
    'startVisitNums' => mt_rand(100, 200),
    // 浏览量刷新一次增加的数量
    'visitNumsStep' => mt_rand(10, 20),
    // rss页面文章数量
    'rssPostNums' => 50,
    // sitemap 输出文章数量
    'sitemapPostNums' => 500,
    
    
    /**
     * ！！以下内容如果没有必要的情况，请勿进行修改！！
     */
    // ip地址库存储方式，Mongodb or Mysql
    'ipAddressMedia' => 'Mysql',

    /*
     * 时间格式
     */
    // 日期时间格式
    'formatDateTime' => 'Y-m-d H:i:s',
    'formatShortDateTime' => 'Y-m-d H:i',
    'formatDate' => 'Y-m-d',
    'formatTime' => 'H:i:s',
    'formatShortTime' => 'H:i',
    
    /**
     * cooke 名称
     */
    'cookieCurrentLocation' => md5('currentLocation'),
    'cookieCityInfo' => md5('cityInfo'),
    'cookieCartToken' => md5('cartToken'),

    /* 时间 */
    // 自动登录
    'autoLoginDuration' => 7*24*60*60,
    // 同楼订餐截止时间
    'grouponEndTime' => '10:00:00',
    
    
    // 是否开启缓存功能
    'caching' => 0,    // 0 or 1
    // 敏感词语列表缓存超时时间
    'expireSpamWords' => 3600 * 24,
    // 广告列表缓存超时时间
    'expireAdsList' => 3600 * 24 * 7,
    // Rss文章列表缓存超时时间
    'expireRssPosts' => 30,
    // 友情链接列表缓存超时时间
    'expireFriendLinks' => 3600 * 24 * 7,
    // 评论列表缓存超时时间
    'expireCommentList' => 10,
    
    /*
     * 积分设置
     */
	'markUserGradeGoods' => 10,
	'markShopGradeGoods' => 10,
    'markUserAddOrder' => 10, 		//用户成功下订单所增加的积分
    'markUserSignup' => 100,
    'markMapMark' => 50,
    'markCompleteProfile' => 50,
    'markInviteFriend' => 50,
    'markUserFindError' => 20,

	/*
	 * 白吃点设置
	 */
	'defaultInviterBcIntegral' => 10,	// 默认邀请增加的白吃点数

    // 分享文本模板
    'share_title' => '我在我爱外卖网上发现一个非常不错的餐馆：%s，推荐给大家。',
    'share_description' => '%s，%s',

	'mailSMTPSecure' => 'ssl',
	'mailHost' => 'smtp.exmail.qq.com',
	'mailPort' => '465',
	'mailUsername' => 'noreply@52wm.com',
	'mailPassword' => '52wm123123',

	'alipayPartner' => '2088002072586114',
	'alipaySecurity' => 'pnfr5nkcjgpq5jwp3x3njoyd26r1dcv7',
	'alipaySeller' => 'davidwu1971@gmail.com',

	// 设置参数
	's_orderApprove' => 'order_approve',
	's_orderApproveCloseTime' => 'order_approve_close_time',

	// 秒杀时间
	'miaoshaStartTime' => @mktime(0,0,0,5,9,2011),
	'miaoshaEndTime' => @mktime(23,59,59,5,18,2011),

	/*
     * winic.org的账号 SMS
     */
    'winic_userid' => 'davidwu',
    'winic_passwd' => '711226',

	/*
	 * http://yzt.136u.com
	 */
	'yzt_userid' => '10279',
	'yzt_key' => "uksGMJCfrt9ykVE8qmZGsVyTYFEeLiH8",
);