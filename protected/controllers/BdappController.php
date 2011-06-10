<?php
class BdappController extends Controller
{
    const API_KEY = '7224d1f9252c829b6b8e67dee1edd7fc';
    const API_URL = 'http://www.52wm.com/api/';
    
    const BAIDU_API_ID = '107588';
    const BAIDU_API_KEY = 'q8dvgraoD3xxaiZTXGqATliD';
    const BAIDU_API_SECRET = 'xdLlpreDeryhxgLMbPOFKKPKXcsuuCZq';
    
    public function init()
    {
        $this->layout = 'baidu';
    }
    
    public function actionIndex($cityid = 1)
    {
        $hot = array(
            '历下区' => array(
                10234 => '数码港大厦',
                1427 => '山东师范大学',
                9995 => '山工大厦 ',
                4943 => '赛博数码广场',
                4663 => '泉城广场',
                10868 => '科技市场',
                4583 => '泉城公园',
                5425 => '趵突泉社区',
                1431 => '山东中医药大学',
                2387 => '山东电视台',
                11066 => '济南电视台',
                10165 => '创展中心',
                10291 => '百脑汇',
            ),
            '市中区' => array(
                4653 => '英雄山绿荫广场',
                1531 => '山东财政学院',
                10940 => '人民商场',
                10088 => '利豪大厦',
                10053 => '大观园',
                10048 => '鲁能大厦',
                9987 => '明珠商务港',
                9979 => '景丽商务大厦',
                10248 => '舜耕国际会展中心',
                4909 => '万达购物广场',
                10066 => '济南邮政大厦',
                7446 => '八一立交桥',
            ),
            '历城区' => array(
                4958 => '洪楼广场',
                1475 => '山东大学中心校区',
                10209 => '东环国际广场',
                5887 => '南全福小区',
                10082 => '火炬大厦',
                5900 => '富翔天地',
                10035 => '东方丽景大厦',
                10311 => '山东大学洪家楼校区',
                5903 => '上海花园',
                5883 => '七里堡',
                5164 => '留学人员创业园',
                10133 => '中润世纪广场',
            ),
            '槐荫区' => array(
                10086 => '八里桥商业楼',
                10008 => '成隆商务大厦',
                9975 => '泰山国际大厦',
                10011 => '金顺写字楼',
                10031 => '青年公园社区经济园',
                9972 => '军泰写字楼',
                9970 => '泉景同润商务大厦',
                10258 => '荣祥商务楼',
                10245 => '阳光商务中心',
                10217 => '世宏商务',
            ),
            '天桥区' => array(
                10933 => '银座家居',
                11053 => '长途汽车站',
                11017 => '火车站',
                10096 => '嘉汇环球广场',
                10065 => '招银大厦',
                10300 => '泺口服装大厦',
                10221 => '欧亚电子大厦',
                10218 => '白鹤商厦',
                10304 => '齐鲁鞋城写字楼',
            ),
        );
        
        $history = self::getBdLocationHistory();
        
        $this->render('index', array(
            'hot' => $hot,
            'history' => $history,
        ));
    }
    
    public function actionLocationSearch($kw, $cityid = 1)
    {
        $kw = urldecode(strip_tags(trim($kw)));
        if (empty($kw))
            $this->redirect(aurl('bdapp'));
            
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'location.getList',
            'pagenum' => 20,
            'keyword' => $kw,
            'city_id' => $cityid,
        );
        if (isset($_GET['page'])) $args['page'] = $_GET['page'];
        
        $url = buildApiUrl(self::API_URL, $args);
        $data = api_execute($url);
        $data = json_decode($data, true);
        
        $gdata = self::googleSearch($kw);
        
        $data = array_merge($data, $gdata);
        $history = self::getBdLocationHistory();
        
        if (empty($data)) {
            $this->render('no_location', array(
                'history' => $history,
            ));
            app()->end();
        }
        
        /*
         * 获取数量
         */
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'location.getCount',
            'keyword' => $kw,
            'city_id' => $cityid,
        );
        $url = buildApiUrl(self::API_URL, $args);
        $count = api_execute($url);
        $count = json_decode($count, true) + count($gdata);
        
        $pages = new CPagination($count);
        $pages->setPageSize(15);
        
        
        $this->render('locationlist', array(
        	'data' => $data,
            'pages' => $pages,
            'history' => $history,
        ));
    }
    
    private static function googleSearch($kw)
    {
	    /* 通过google map 取地址数据 */
	    //$cityname = $this->city['name'];
	    $cityname = '济南';
	    $g_kw = $kw;
	    if(stripos($kw, $cityname) === false) {
	    	$g_kw = $cityname . $kw;
	    }
	    $google_geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' .urlencode($g_kw). '&sensor=false&region=cn&language=zh-CN');
	    $googledata = CJSON::decode($google_geocode);
		if($googledata['status'] == 'OK') {
			$result = $googledata['results'];
			foreach ($result as $r) {
				if($r['address_components'][0]['types'][0] == 'street_number') {
					$name = $r['address_components'][1]['long_name'] . $r['address_components'][0]['long_name'];
				} else {
					$name = $r['address_components'][0]['long_name'];
				}
				$name = str_replace(array('（', '）'), array('(', ')'), trim($name));
				if(!in_array($name, (array)$namearray)) {
					$newdata[] = array(
						'map' => true,
						'name' => $name,
		    			'map_x' => $r['geometry']['location']['lng'],
		    			'map_y' => $r['geometry']['location']['lat'],
		    			'address' => $r['formatted_address']
					);
				}
			}
		}
		if(count($newdata) == 1) {
			if($newdata[0]['name'] == '济南' && $newdata[0]['map']) {
				$newdata = array();
			}
		}
		return $newdata;
    }
    
    public function actionShopSearch($locid = 0, $lat = 0, $lon = 0, $cityid = 1)
    {
        $locid = (int)$locid;
        $lat = strip_tags(trim($lat));
        $lon = strip_tags(trim($lon));
        $cityid = (int)$cityid;
        
        if (empty($locid) && (empty($lat) || empty($lon)))
            $this->redirect(aurl('bdapp'));
        
        $history = self::getBdLocationHistory();
        Location::addSearchHistory($locid);
        
        if ($locid) {
            $args = array(
                'apikey' => self::API_KEY,
                'method' => 'shop.getListByLocation',
                'location_id' => $locid,
                'city_id' => $cityid,
            );
        }
        else {
            $args = array(
                'apikey' => self::API_KEY,
                'method' => 'shop.getListByCoordinate',
                'lat' => $lat,
                'lon' => $lon,
                'city_id' => $cityid,
            );
        }
        
        
        $url = buildApiUrl(self::API_URL, $args);
        $data = json_decode(api_execute($url), true);
        
        if (empty($data) || $data['errorCode']) {
            $this->render('no_shop', array(
                'history' => $history,
            ));
            app()->end();
        }
        
        $pages = new CPagination(count($data));
        $pages->setPageSize(15);
        
    
        $page = (int)$_GET['page'];
    	$offset = $page ? ($page-1)*10 : 0;
    	$data = array_slice($data, $offset, 5, true);
        
        $this->render('shoplist', array(
        	'data' => $data,
            'pages' => $pages,
            'history' => $history,
        ));
    }
    
    public function actionShop($shopid, $cityid = 1)
    {
        $page_size = 20;
        $shopid = (int)$shopid;
        $cityid = (int)$cityid;
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'shop.getInfo',
            'shop_id' => $shopid,
            'city_id' => $cityid,
        );
        
        $url = buildApiUrl(self::API_URL, $args);
        $shop = api_execute($url);
        $goods = self::getGoodsList($shopid);
        
        $count = count($goods);
        $pages = new CPagination($count);
        $pages->setPageSize($page_size);
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        $goods = self::sortGoodsList($goods, $page, $page_size);
        $this->render('shopinfo', array(
        	'shop' => json_decode($shop, true),
            'goods' => $goods,
            'pages' => $pages,
        ));
    }
    
    private static function getGoodsList($shopid)
    {
        $shopid = (int)$shopid;
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'goods.getFoodList',
            'shop_id' => $shopid,
        );
        
        $url = buildApiUrl(self::API_URL, $args);
        $data = json_decode(api_execute($url), true);
        return $data;
    }
    
    private static function sortGoodsList($data, $page = 1, $count = 0)
    {
        if (empty($data)) return null;
        
        foreach ((array)$data as $v) {
            $goods[$v['category_name']][] = $v;
        }
        unset($data);
        if (0 === $count) return $goods;
        
        $nums = 0;
        $offset = 0;
        $start = ($page - 1) * $count;
        foreach ((array)$goods as $k=>$v) {
            foreach ((array)$v as $vv) {
                $offset++;
                if ($offset < $start) continue;
                
                $data[$k][] = $vv;
                $nums++;
                
                if ($nums >= $count) break;
            }
            if ($nums >= $count) break;
        }
        return $data;
    }
    
    
    public function actionGoodsInfo($goodsid)
    {
        $goodsid = (int)$goodsid;
        $args = array(
            'apikey' => self::API_KEY,
            'method' => 'goods.getInfo',
            'goods_id' => $goodsid,
        );
        
        $url = buildApiUrl(self::API_URL, $args);
        $data = api_execute($url);
        $this->render('goodsinfo', array(
        	'data' => json_decode($data, true),
        ));
    }
    
    
    public function actionMonitor()
    {
        echo '<!--STATUS OK-->';
        exit(0);
    }
    
    private static function getBdLocationHistory()
    {
        $html = '';
	    $history = Location::getSearchHistoryData(3);
	    foreach ((array)$history as $v) {
        	$html .= l($v->name, aurl('bdapp/shopSearch', array('locid'=>$v->id)), array('title'=>$v->name)) . '&nbsp;&nbsp;';
	    }
	    return $html;
    }
    
    public function actionGoodsSearch($kw)
    {
        $cid == ShopCategory::CATEGORY_FOOD;
	    $atid = Location::getLastVisit();
    	$kw = urldecode(strip_tags(trim($kw)));
    	
	    if (empty($atid) || empty($kw)) $this->redirect(request()->url);
    	
	    if(!is_array($atid)) {
	    	$location = Location::model()->findByPk($atid);
	    	$atid = $location;
	    }
    	
    	/*
	     * 获取商铺
	     */
	    $criteria = new CDbCriteria();
	    $criteria->addCondition('business_state != ' . Shop::BUSINESS_STATE_CLOSE);
	    $data = Shop::getLocationShopList($atid, $cid, $criteria);
	    $shopIds = CHtml::listData((array)$data['shops'], 'id', 'id');
	    unset($data);
	    
	    $criteria = new CDbCriteria();
		$criteria->addInCondition('shop_id', $shopIds);
		$criteria->addSearchCondition('name', $kw);
	    $goods = Goods::model()->with('shop', 'foodGoods')->findAll($criteria);
	    
        if (empty($goods)) {
            $this->render('no_goods');
            app()->end();
        }
	    
	    $goodscount = 0;
	    foreach ($goods as $v) {
	        $data[$v->shop->shop_name][] = $v;
	        $goodscount++;
	    }
//	    echo count($data);exit;
	    $page_size = 20;
	    $pages = new CPagination($goodscount);
        $pages->setPageSize($page_size);
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;
        
        $data = self::sortGoodsSearchList($data, $page, $page_size);
		$this->render('goods_search', array(
		    'data' => $data,
		    'pages' => $pages
		));

    }
    
    private static function sortGoodsSearchList($data, $page = 1, $count = 0)
    {
        $nums = 0;
        $offset = 0;
        $start = ($page - 1) * $count;
        foreach ((array)$data as $k=>$v) {
            foreach ((array)$v as $vv) {
                $offset++;
                if ($offset < $start) continue;
                
                $goods[$k][] = $vv;
                $nums++;
                
                if ($nums >= $count) break;
            }
            if ($nums >= $count) break;
        }
        return $goods;
    }

    public function actionAddapp()
    {
        
    }

    public function actionRemove()
    {

    }
}

/**
 * 创建执行url
 * @param string $apiurl api地址
 * @param array $args 需要传递的参数
 * @return string 格式化之后的url地址
 */
function buildApiUrl($apiurl, $args)
{
    if (empty($apiurl) || !is_array($args))
        return null;
        
    return $apiurl . '?' . http_build_query($args);
}

/**
 * 执行api
 * @param string $url
 * @return string 返回JSON编码的字符串
 */
function api_execute($url, $args = null, $user = null, $pass = null)
{
    if (empty($url)) return false;
    if (false === filter_var($url, FILTER_VALIDATE_URL)) return false;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
    
    if ($user && $pass)
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $pass);

    if ($args) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    }
    
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

