<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * 城市数组
     * @var array(id, code, name, map_x, map_y)
     */
    public $city;
    
    /**
     * 任务标题
     * @var string
     */
    public $taskTitle;

    /**
     * 购物车token，取值md5(sessionID)
     * @var string
     */
    public $token;
    
    public function init()
    {
        parent::init();
        self::checkIpDenyAccess();
        $ip = CdcBetaTools::getClientIp();
        $this->city = CdcBetaTools::getCityInfo($ip);
        $this->token = CdcBetaTools::getSiteToken();
        
//        if (!user()->isGuest) {
//            $session = app()->session;
//            $attrs = array(
//                'user_id' => (int)user()->id,
//            );
//            $shops = Shop::model()->findAllByAttributes($attrs);
//            if (1 == count($shops)) {
//                $session['shop'] = $shops[0];
//    		}
//        }
    }
    
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'application.extensions.CdcCaptchaAction',
				'backColor' => 0xFFFFFF,
				'height' => 22,
				'width' => 70,
				'maxLength' => 4,
				'minLength' => 4,
		        'foreColor' => 0xFF0000,
		        'padding' => 3,
		        'testLimit' => 1,
			),
		);
	}
	
    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $actions = array('login', 'signup', 'error', 'favorite');
        $actionId = $this->action->id;
        if (!in_array($actionId, $actions))
            user()->loginUrl = array('site/login', 'referer' => urlencode(abu(app()->request->url)));
        return true;
    }
    
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * 生成首页(classic布局)用户导航栏
	 * 使用页面缓存后对动态内容使用renderDynamic调用
	 * @return string 导航栏html
	 */
	public function getClassicUserNav()
	{
	    if (user()->isGuest) {
	        $data =  array(
	            array('text'=>'登录', 'link'=>user()->loginUrl, 'target'=>'_self', 'title'=>'马上登录'),
	            array('text'=>'注册', 'link'=>url('site/signup'), 'target'=>'_self', 'title'=>'成为我爱外卖网会员'),
	            array('text'=>'关于我们', 'link'=>url('static/about'), 'target'=>'_self'),
	            array('text'=>'反馈留言', 'link'=>url('feedback'), 'target'=>'_blank', 'title'=>'您有什么好的点子可以告诉我们哟'),
	            array('text'=>'收藏我们', 'link'=>'javascript:void(0);', 'onclick'=>'bookmark();', 'title'=>'这么方便的网站，赶快收藏起来吧'),
	            array('text'=>'我要开店', 'link'=>url('shop/checkin'), 'target'=>'_self'),
	            array('text'=>'团购导航', 'link'=>url('tuannav/list'), 'target'=>'_self'),
	        );
	    } else {
	        if (empty($_SESSION['shop'])) {
	            $cp = array('text'=>'个人中心', 'link'=>url('my'), 'target'=>'_self', 'title'=>'进入个人中心');
	            $my = array('text'=>user()->screenName, 'link'=>url('my'), 'target'=>'_self', 'title'=>'进入个人中心');
	        } else {
	            $cp = array('text'=>'商家中心', 'link'=>url('shopcp'), 'target'=>'_blank', 'title'=>'进入商家管理中心');
	            $my = array('text'=>user()->screenName, 'link'=>url('shopcp'), 'target'=>'_blank', 'title'=>'进入商家管理中心');
	        }
	        $data =  array(
	            $my,
	            $cp,
	            array('text'=>'关于我们', 'link'=>url('static/about'), 'target'=>'_self'),
	            array('text'=>'反馈留言', 'link'=>url('feedback'), 'target'=>'_blank', 'title'=>'您有什么好的点子可以告诉我们哟'),
	            array('text'=>'收藏我们', 'link'=>'javascript:void(0);', 'onclick'=>'bookmark();', 'title'=>'这么方便的网站，赶快收藏起来吧'),
	        );
	        if (empty($_SESSION['shop']))
	        	$data[] = array('text'=>'我要开店', 'link'=>url('shop/checkin'), 'target'=>'_self');
	        	
	        $data[] = array('text'=>'团购导航', 'link'=>url('tuannav/list'), 'target'=>'_self');
	        $data[] = array('text'=>'退出', 'link'=>url('site/logout'), 'target'=>'_self');
	    }
	    $html = '';
	    foreach ($data as $v) {
	        $htmlOptions = array('target'=>$v['target'], 'title'=>$v['title']);
	        if (isset($v['onclick'])) $htmlOptions['onclick'] = $v['onclick'];
	        $html .= '<li>' . l($v['text'], $v['link'], $htmlOptions) . '</li>';
	    }
	    return $html;
	}
	
	/**
	 * 生成顶部(main布局)用户导航栏
	 * 使用页面缓存后对动态内容使用renderDynamic调用
	 */
	public function getUserToolbar()
	{
	    $cartGoodsNums = sprintf('(%d个美食)', Cart::getGoodsCount());
	    if (user()->isGuest) {
	        $data1 = array(
	            '您好！欢迎光临我爱外卖',
	            l('登录', user()->loginUrl),
	            l('免费注册', url('site/signup')),
	            CHtml::image(resBu('images/renren.png'), 'Renren Connect', array('onclick'=>'onRenRenLogin();', 'id'=>'xn_login_image', 'class'=>'xnconnect_login_button')),
	        );
	        $data2 = array(
	            l('购物车', url('cart/checkout')) . $cartGoodsNums,
	            l('个人中心', url('my')),
	            l('收藏夹', url('my/favorite')),
	            l('我要开店', url('shop/checkin')),
	        );
	    } else {
	        $data1 = array(
	            '亲爱的' . user()->screenName . '，欢迎光临我爱外卖',
	            l('安全退出', url('site/logout')),
	        );
	        
	        $noRatingNums = sprintf('(%d个订单未点评)', User::getUserNoRatingNums(user()->id));
	        $unrated = l($noRatingNums, url('my/order/norating'));
	        if (empty($_SESSION['shop']))
	            $cp = l('个人中心', url('my')) . $unrated;
	        else
	            $cp = l('商家中心', url('shopcp'), array('target'=>'_blank'));
	        $data2 = array(
	            l('购物车', url('cart/checkout')) . $cartGoodsNums,
	            $cp,
	            l('收藏夹', url('my/favorite')),
	        );
	        if (empty($_SESSION['shop']))
	        	$data2[] = l('我要开店', url('shop/checkin'));
	    }
	    $html1 = $html2 = '';
	    $len1 = count($data1);
	    foreach ($data1 as $k => $v) {
	        $html1 .= '<li>' . $v . '</li>';
	        if ($k < $len1-1) $html1 .= '<li>|</li>';
	    }
	    
	    $len2 = count($data2);
	    foreach ($data2 as $k => $v) {
	        $html2 .= '<li>' . $v . '</li>';
	        if ($k < $len2-1) $html2 .= '<li>|</li>';
	    }
	    
	    return sprintf('<ul class="fl subfl">%s</ul><ul class="fr subfl">%s</ul>', $html1, $html2);
	}

	public function getUserLocation()
	{
	    $html = '';
		$lastvisit = Location::getLastVisit();
		$location = Location::getSearchHistoryData();
		$locaName = '';
		$html = l('切换当前位置', url('at/switch'), array('class'=>'underline')) . '<div class="location-pop none"><span class="bg-icon"></span><div>';
		$nums = 10; // 显示地址记录条数
		$i = 0;
		foreach ((array)$location as $row) {
			if($lastvisit == $row->id) {
				$html .=  '<p class="location-list cblack bg-icon selected">' . l($row->name, $row->shopListUrl) . '</p>';
				$locaName = $row->name;
			} else
				$html .=  '<p class="location-list cblack">' . l($row->name, $row->shopListUrl) . '</p>';
			$i++;
			if($i >= $nums) break;
		}
		$html .= '<p class="bg-icon location-search">' . l('搜索新地址', url('site/index', array('f'=>STATE_ENABLED))) . '</p></div></div>';
		if(is_array($lastvisit)) {
			$html = '<p class="cgray">位置：' . $lastvisit[0] . ',' . $lastvisit[1] . '</p>' . $html;
		} else {
			$html = '<p class="cgray">' . $locaName . '</p>' . $html;
		}
		
		return $html;
	}
	
	public function getBannerImg()
	{
		$html = l(CHtml::image(resBu('images/banner_r1_c2.png')), url('miaosha/index'));
		return $html;
	}
	
	public function getUserSearchLocationHistory()
	{
	    $html = '';
	    $history = Location::getSearchHistoryData(3);
	    foreach ((array)$history as $v) {
        	$html .= $v->nameLinkHtml . '&nbsp;&nbsp;';
	    }
	    return $html;
	}
	
	
	protected function setPageKeyWords($text = '')
	{
	    $kw = str_replace('[CITY]', $this->city['name'], param('keywords'));
	    $text = $text ? ($text . ',') : $text;
	    $kw = sprintf($kw, $text);
	    cs()->registerMetaTag($kw, 'keywords');
	}
	
    protected function setPageDescription($text = '')
	{
	    $desc = str_replace('[CITY]', $this->city['name'], param('description'));
	    $text = $text ? ($text . '，') : $text;
	    $desc = sprintf($desc, $text);
	    cs()->registerMetaTag($desc, 'description');
	}
	
    /**
     * 判断用户IP是否被加入黑名单，并且是被禁止访问类型
     */
    private static function checkIpDenyAccess()
    {
    	$ip = CdcBetaTools::getClientIp();
        if (DenyIp::checkIpState($ip) === DenyIp::TYPE_ACCESS) {
            header('Content-type: text/html, charset=' . app()->charset);
            echo '<h1 style="font-size:100px;">您的IP已经被禁止访问</h1>';
            exit(0);
        }
    }
}