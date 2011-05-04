<?php
define('STATE_ENABLED', 1);
define('STATE_DISABLED', 0);

return array(
    // 网站名称
    'sitename' => 'M9魅软网',
    // 备案号
    'miibeian' => '鲁ICP备09073747号',
	// 管理员邮箱
	'adminEmail'=>'contact@meiapps.com',
    // cookie 域
    'cookieDomain' => '.meiapps.com',
    // cookie路径
    'cookiePath' => '/',
    // 评论下方第三个用户互动显示的文字
    'popularWord' => '给力',
    
    // 访客投递和访客评论显示的名字
    'guest' => '匿名煤油',
    'shortdesc' => '专注于M9应用',
    'description' => '魅软网',

    // api key secret
    'qqtApiKey' => '02bb71e5aee24993a39d2cc618186155',
    'qqtApiSecret' => 'b4b38adc4225a4e32f971d5b551dcd2f',
    'sinatApiKey' => '1680523446',
    'sinatApiSecret' => '412d55077e55d72525f45ff904474a3a',
        
    // 系统数据文件目录
    'dataPath' => dirname(__FILE__) . DS . '..' . DS . 'data' . DS,
    
    'staticBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'attachments' . DS,
    // 最后带 /
    'staticBaseUrl' => 'http://s200.cdcidc.com/',

    // resource 路径
    'resourceBasePath' => dirname(__FILE__) . DS . '..' . DS . '..' . DS . 'resources' . DS,
    'resourceBaseUrl' => 'http://res2.cdcidc.com/',
    
    // 注册用户是否需要email激活
    'signupEmailActive' => false,
    // 用户大名保留词语，模糊匹配
    'reservedWords' => (array)include(dirname(__FILE__) . DS . 'reservedUsers.php'),

	/*****************************************************************/
    // 编码
    'charset' => 'utf-8',
    // 语言
    'language' => 'zh_cn',
    // 模板
    'theme' => 'default',
    /*
     * url类型 ，可取值为get或path，如果要实现伪静态，请设置为path
     * 并且将public目录下的.htaccess.bak文件改名为.htaccess
     */
    'urlFormat' => 'path',
    
    // 生成二维码的google api地址
    'googleQrApi' => 'http://chart.apis.google.com/chart?chs=150x150&cht=qr&chld=L|1&choe=UTF-8&chl=%s',
    
    // 时区
    'timezone' => 'Asia/Shanghai',
    // 默认投递文章是否通过验证
    'defaultIsAuth' => STATE_DISABLED,
    // 默认投递的文章是否直接可以显示
    'defaultIsShow' => STATE_ENABLED,
    //默认发表的文章是否允许评论
    'defaultAllowComment' => STATE_ENABLED,
    // 默认热门文章缩略图，可以是绝对路径，也可以是相对于resource文件夹的相对路径
    'defaultThumbnail' => 'images/thumbnail.jpg',
    // 默认用户头像
    'defaultUserHeadPic' => '',
    // 默认敏感词语替换字符串
    'spamReplace' => '文明用语',
    // 如果发表人为空的话，默认使用的编辑的名字
    'defaultEditor' => '小编',
    // 是否使用严格规则禁止重复digg文章，严格模式只判断ip，模糊模式，只要关了浏览器，再打开还可以顶文章
    'isStrictDiggMode' => true,
    // 默认是否显示digg按钮
    'defaultShowDiggBtn' => false,
    // 默认是否使用ajax方式来载入文章列表
    'useAjaxLoadPosts' => true,
    // 是否开放评论
    'globalAllowComment' => true,
    // 默认评论是否需要审核
    'defaultCommentIsShow' => true,

    // 最大可小传的文件大小,注意不能超过php设置的最大值
    'maxUploadSize' => '50MB',
    // 文件上传最多可以上传多少个附件
    'maxUploadNums' => 10,
    // 文件上传类型
    'uploadFilesTypes' => '*.apk;*.jpg;*.gif;*.png;*.zip;*.rar',

    
    // 发表评论的间隔时间
    'commentInterval' => 10,
    // 支持评论的间隔时间
    'commentOperateExpire' => 5,
    
    
    // 默认文章每页显示数量
    'listPostsNums' => 15,
    // 详情页相关文章显示数量
    'relativePostsNums' => 10,
    // top10文章显示数量
    'topPostsNums' => 9,
    // 热门文章显示数量
    'hotPostsNums' => 4,
    // 编辑推荐文章显示数量
    'editorRecommendNums' => 18,
    // 编辑推荐评论显示数量
    'recommendCommentNums' => 20,
    // 初始访问量
    'startVisitNums' => mt_rand(100, 200),
    // 浏览量刷新一次增加的数量
    'visitNumsStep' => mt_rand(10, 20),
    // 初始支持数
    'startDiggNums' => 0, //mt_rand(20, 50),
    // 文章支持数点一次增加的数量
    'diggNumsStep' => mt_rand(1, 3),
    // 成为热门评论需要的支持数
    'hotCommentSupportNums' => 10,
    // 文章评论每页显示数量
    'commentListNums' => 100,
    // 文章热门评论条数，原则上不限
    'postHotCommentNums' => 50,
    // rss页面文章数量
    'rssPostNums' => 50,
    // wap版显示文章列表数量
    'wapPostNums' => 50,
    // sitemap 输出文章数量
    'sitemapPostNums' => 500,
    // 默认评论支持数量
    'startCommentSupportNums' => mt_rand(1, 20),
    // 默认评论反对数量
    'startCommentOpposeNums' => mt_rand(1, 20),
    // 默认评论中立数量
    'startCommentNeutralNums' => mt_rand(1, 20),
    // 评论最大楼层数
    'floorMaxNums' => 10,
    // 友情链接首页显示数量
    'friendLinkNums' => 9,

    // 用户登陆失败几次后显示验证码
    'maxLoginErrorNums' => 3,
    // 上传时脚本最大执行时间(单位秒)
    'maxUploadExecutionTime' => 300,
    
    
    /**
     * ！！以下内容如果没有必要的情况，请勿进行修改！！
     */

    // 用户登录如果选择在记住状态，默认cookie保存时间
    'autoLoginDuration' => 30*24*60*60,

    /*
     * Cookie名称
     */
    'cookieSiteToken' => md5('siteToken'),
    'cookiePostCommentInterval' => md5('cookiePostCommentInterval'),
    'cookieCommentInterval' => md5('cookieCommentInterval'),

    /*
     * 时间格式
     */
    // 日期时间格式
    'formatDateTime' => 'Y-m-d H:i:s',
    'formatShortDateTime' => 'Y-m-d H:i',
    'formatDate' => 'Y-m-d',
    'formatTime' => 'H:i:s',
    'formatShortTime' => 'H:i',
    
    /*
     * 上传图片宽度和高度
     */
    'uploadImageWidth' => 570,
    'uploadImageHeight' => 1100,
    
    
    // 是否开启缓存功能
    'caching' => 1,    // 0 or 1
    // 首页缓存超时时间
    'expireIndexPosts' => 60,
    // 文章详情缓存超时时间
    'expirePost' => 300,
    // 专题事件列表缓存超时时间
    'expireTopicEventList' => 24 * 3600,
    // 文章列表缓存超时时间
    'expireListPosts' => 30,
    // 静态内容缓存超时时间
    'expireStatic' => 3600 * 24,
    // 编辑推荐文章和评论缓存超时时间
    //'expireEditorRecommend' => 3600 * 24,
    // sitemap文件列表缓存超时时间
    'expireSitemapPosts' => 3600,
    // 主题文章列表缓存超时时间
    //'expireTopicPosts' => 3600,
    // 敏感词语列表缓存超时时间
    'expireSpamWords' => 3600 * 24,
    // 广告列表缓存超时时间
    //'expireAdsList' => 3600 * 24 * 7,
    // Rss文章列表缓存超时时间
    'expireRssPosts' => 300,
    // 友情链接列表缓存超时时间
    //'expireFriendLinks' => 3600 * 24 * 7,
    // 评论列表缓存超时时间
    'expireCommentList' => 10,
    // Top10 页面缓存超时时间
    'expireTop10Post' => 3600 * 24,
    // 搜索结果缓存超时时间
    'expireSearch' => 3600*12,
    
    
    // 文章相关文章列表缓存id
    'cacheIdRelativePosts' => 'RelativePosts_%s',
    // 热门文章缓存id
    'cacheIdHotPosts' => 'HotPosts_%s',
    // 文章列表缓存id
    'cacheIdListPosts' => 'ListPost_%s_%s_%s',
    // 访问量前10的文章列表缓存id
    'cacheIdTopVisitPosts' => 'TopVisit_%s_%s',
    // 评论数前10的文章列表缓存id
    'cacheIdTopCommentPosts' => 'TopComment_%s_%s',
    // 编辑推荐文章列表缓存id
    'cacheIdEditorRecommendPosts' => 'EditorRecommendPosts_%s_%s',
    // 编辑推荐评论列表缓存id
    'cacheIdRecommendComment' => 'RecommentComment',
    // 敏感词语列表缓存id
    'cacheIdSpamWords' => 'SpamWords',
    // 广告列表缓存id
    'cacheIdExpireAdsList' => 'AllAdsList',
    // Rss文章列表缓存id
    'cacheIdRssPosts' => 'RssPosts',
    // FriendLinks 友情链接缓存id
    'cacheIdFriendLinks' => 'FriendLinks',
    // Comment列表缓存id
    'cacheIdCommentList' => 'CommentList_%d',

);