<?php
class CdcBetaTools
{
    const FILE_NO_EXIST = -1; // '目录不存在并且无法创建';
    const FILE_NO_WRITABLE = -2; // '目录不可写';
    
    public static $version = '0.1 20101111';
    public static $officialUrl = 'http://www.52wm.com/';
    
    /**
     * 过滤处理超级链接标签a，去掉无用的属性，并且添加class
     * @param $html string 需要处理的包含超级链接标签的html代码
     * @return string 处理后的html代码
     */
    public static function purifyLinkTag($html)
    {
        $p = '/<a(.*?)href="(.+?)"(.*?)>(.+?)<\/a>/ism';
        $r = '<a href="$2" target="_blank">$4</a>';
        $html = preg_replace($p, $r, $html);
        return $html;
    }
    
    
    /**
     * 过滤处理img标签
     * @param $html string 需要处理的html代码
     * @param $alt string 处理替换的alt属性值
     * @param $title string 为img加上的超级链接的title属性
     * @return string 处理之后的html代码
     */
    public static function purifyImgTag($html, $title = null)
    {
        $p = '/<img .*?src="?(.+?)"?( .*?|\/|)>/ism';
        $r = sprintf('<img src="$1" class="content-pic" alt="图片：%s" />', $title);
        return preg_replace($p, $r, $html);
    }
    
    
    /**
     * 使用CHtmlPurify过滤html代码
     * @param $content string 需要过滤的html代码
     * @return string 过滤之后的 html代码
     */
    public static function purify($content, $section)
    {
        static $purifier;
        if($purifier[$section] === null) {
            $purifier[$section] = new CHtmlPurifier();
            $options = require(dirname(__FILE__) . DS . '..' . DS . 'config' . DS . 'purifier.ini.php');
            $purifier[$section]->options = $options[$section];
        }
        return $purifier[$section]->purify($content);
    }
    
    
    /**
     * 获取客户端IP地址
     * @return string 客户端IP地址
     */
    public static function getClientIp()
    {
        if ($_SERVER['HTTP_CLIENT_IP']) {
	      $ip = $_SERVER['HTTP_CLIENT_IP'];
	 	} elseif ($_SERVER['HTTP_X_FORWARDED_FOR']) {
	      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	 	} else {
	      $ip = $_SERVER['REMOTE_ADDR'];
	 	}
        
        return $ip;
    }
    
	/**
     * 过滤敏感关键字，如果replace字段替换字符串为空，则显示param('spamReplace')指定的默认字符串
     * 如果不为空，则替换为replace字段指定值
     * 支持不连续关键字过滤，如subject字段为 a{1}b{1}c，则可以过滤abc，axbc，abxc，axbxc等敏感词
     * @param $html string 需要过滤的字符串
     * @return string 过滤之后的字符串
     */
    public static function purifySpamWords($html)
    {
        // 读取所有敏感词语
        static $words;
        if ($words === null)
            $words = SpamWords::model()->getAllValidWords();
        foreach ($words as $w) {
            if (empty($w->replace)) $w->replace = param('spamReplace');
            $p = '/(.+?)\{(\d+?)\}/ism';
            $w->subject = preg_replace($p, '$1.{0,$2}', $w->subject);
            $html = preg_replace('/' . $w->subject . '/ism', '<i>' . $w->replace . '</i>', $html);
        }
        
        return $html;
    }
    
    
    /**
     * 返回当前程序版本
     * @return string $version
     */
    public static function getVersion()
    {
        return self::$version;
    }
    
    /**
     * 返回上传后的文件路径
     * @return string|Array 如果成功则返回路径地址，如果失败则返回错误号和错误信息
     * -1 目录不存在并且无法创建
     * -2 目录不可写
     */
    public static function makeUploadPath($additional = null)
    {
        $relativePath = (($additional === null) ? '' : $additional . '/')
            . date('Y/m/d/', $_SERVER['REQUEST_TIME']);
        
        $path = param('staticBasePath') . $relativePath;
        
        if (!file_exists($path) && !mkdir($path, 0755, true)) {
            return self::FILE_NO_EXIST;
        } else if (!is_writable($path)) {
            return self::FILE_NO_WRITABLE;
        } else
            return array(
            	'absolute' => $path,
                'relative' => $relativePath,
            );
    }
    
    public static function makeUploadFileName($extension)
    {
        $extension = strtolower($extension);
        return date('YmdHis_', $_SERVER['REQUEST_TIME'])
            . uniqid()
            . ($extension ? '.' . $extension : '');
    }
    
    /**
     * 将汉字转换成拼音，只保留汉字及数字，其它字符过滤掉，拼音之间用-连接
     * @param string $str 待转换字符串
     * @return string 拼音字符串
     */
    public static function makePinyin($str, $separate = '-')
    {
        if (empty($str)) return false;
        
        $pinyins = require('PinyinArray.php');
	    $len = mb_strlen($str);
	    for ($i=0; $i<$len; $i++) {
	        $word = mb_substr($str, $i, 1, app()->charset);
	        if (array_key_exists($word, $pinyins)) {
	            if (!empty($tmp)) {
	                $pinyin[] = $tmp;
	                unset($tmp);
	            }
	            $pinyin[] = $pinyins[$word];
	        } else {
	            if (preg_match('/^[\w\d]+$/i', $word)) $tmp .= $word;
	        }
	    }
	    
        if (!empty($tmp)) {
            $pinyin[] = $tmp;
            unset($tmp);
        }
	    return join($pinyin, $separate);
    }
    
    /**
     * 将汉字首字母转换成拼音
     * @param $str 待处理字符串
     * @return char 字符串拼音首字母
     */
    public static function getFirstLetter($str)
    {
        static $pinyins = null;
    	if (empty($str)) return false;
    	$word = mb_substr($str, 0, 1, app()->charset);
    	if (strlen($word) == 1) {
    		return strtoupper($word);
    	} else {
    		$pinyins = (null === $pinyins) ? require('PinyinArray.php') : $pinyins;
    		return $pinyins[$word] ? strtoupper(substr($pinyins[$word], 0, 1)) : '';
    	}
    }
    
    /**
     * 返回 Powered信息
     * @return string Powered Html
     */
    public static function getPowered()
    {
        return Chtml::link('Powered&nbsp;By&nbsp;<strong>52wm.com</strong>&nbsp;' . self::getVersion(), self::$officialUrl, array('target'=>'_blank'));
    }
    
    /**
     * 获取IP所属的城市及IP段信息
     * @param string $ip IP地址，可选，如果没有此参数则获取用户的IP地址
     * @return array 从mongodb中查询出的$ip的IP段及所属城市信息(startip, endip, code, city)
     */
    public static function checkIpInfo($ip = null)
    {
        $ip = $ip ? $ip : self::getClientIp();
        $ip = ip2long($ip);
        
        $db = ucfirst(strtolower(param('ipAddressMedia')));
        $method = checkIpInfoFrom . $db;
        $data = self::$method($ip);
        return $data;
    }
    
    private static function checkIpInfoFromMongodb($ip)
    {
        app()->mdb->initConnection();
        $ipCollection = app()->mdb->setCollection('wm_Ipaddress');
        $condition = array(
        	'startip' => array('$lte' => $ip),
        	'endip' => array('$gte' => $ip),
        );
        
        $row = $ipCollection->findOne($condition);
        $city = array(
            'startip' => $row['startip'],
        	'endip' => $row['endip'],
        	'code' => $row['code'],
        	'city' => $row['city'],
        );
        return $city;
    }
    
    private static function checkIpInfoFromMysql($ip)
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('startip <= ' . $ip);
        $criteria->addCondition('endip >= ' . $ip);
        
        $row = IpAddress::model()->find($criteria);
        if (null === $row) return null;
        $city = array(
            'startip' => $row->startip,
        	'endip' => $row->endip,
        	'code' => $row->code,
        	'city' => $row->city,
        );
        return $city;
    }
    
    /**
     * 获取用户IP地址对应的城市的相关信息(name, code, id)，
     * @param string $ip IP地址，可选，如果没有此参数则获取用户的IP地址
     * @return array 城市信息(name, code, id)
     */
    public static function getCityInfo($ip = null)
    {
        $city = app()->request->cookies[param('cookieCityInfo')];
        
        if ($city)
            return json_decode(base64_decode($city->value), true);

        $ipInfo = self::checkIpInfo($ip);
        
        /*
         * 此处是根据name在City表中查询分站城市的ID号
         */
        if ($ipInfo)
            $cityModel = City::model()->findByAttributes(array('name'=>$ipInfo['city']));
        else
        	$ipInfo['code'] = '0531';
        
        if (empty($cityModel))
            $cityModel = City::model()->findByPk(param('defaultCityId'));
        $city = array(
            'id' => $cityModel->id,
            'code' => $ipInfo['code'],
            'name' => $cityModel['name'],
	        'map_x' => $cityModel->map_x,
	        'map_y' => $cityModel->map_y
        );
        self::setClientCity($city);
        return $city;
    }
    
    /**
     * 设置客户端所在城市的cookie信息
     * @param array $city 城市资料，必须包含3个基本元素name，code, id(根据name在city表中查询出id来)
     * @return boolean 返回setcookie的返回值
     */
    public static function setClientCity(array $city)
    {
        $cityInfo = base64_encode(json_encode($city));
        $cookie = new CHttpCookie(param('cookieCityInfo'), $cityInfo);
	    $cookie->expire = $_SERVER['REQUEST_TIME'] + 24*60*60*30;
	    $cookie->path = param('cookiePath');
	    $cookie->domain = param('cookieDomain');
	    app()->request->cookies[param('cookieCityInfo')] = $cookie;
    }
    
    /**
     * 判断一个点是否在一组点组成的面中
     * @param array $points 组成面的点的坐标二维数组
     * @example array(
     * 		array(35.9823, 23.2432),
     * 		array(15.9823, 13.2432),
     * 		array(5.9823, 3.2432),
     * )
     * @param double $lat
     * @param double $lon
     * @return boolean 在面外返回false，在面内或在边上返回true
     */
    public static function pointInPolygon(array $points, $lat, $lon)
    {
        $j = count($points) - 1;
        $inPoly = false;
        $len = count($points);
        for ($i = 0; $i < $len; $i++) {
            $pi = $points[$i];
            $pj = $points[$j];
            if ($pi['1'] == $lon && $pi['0'] == $lat) return true;
            
            if ($pi['1'] < $lon && $pj['1'] >= $lon || $pj['1'] < $lon && $pi['1'] >= $lon) {
                $tmpLat = $pi['0'] + ($lon - $pi['1']) / ($pj['1'] - $pi['1']) * ($pj['0'] - $pi['0']);
                if ($tmpLat == $lat) return true;
                if ($tmpLat < $lat) $inPoly = !$inPoly;
            }
            $j = $i;
        }
        return $inPoly;
    }

	
	public static function setSiteToken()
	{
	    $cookie = new CHttpCookie(param('cookieCartToken'), md5(app()->session->sessionID));
	    $cookie->expire = $_SERVER['REQUEST_TIME'] + 30*24*60*60;
	    $cookie->domain = param('cookieDomain');
	    $cookie->path = '/';
	    app()->request->cookies[param('cookieCartToken')] = $cookie;
	    return $cookie;
	}
	
	public static function getSiteToken()
	{
	    $cookie = app()->request->cookies[param('cookieCartToken')];
	    if (null === $cookie) $cookie = self::setSiteToken();
	    return $cookie->value;
	}
	
	public static function getReferrer()
	{
	    $referer = urldecode($_GET['referer']);
	    $referer = $_GET['referer'] ?  $referer : app()->request->urlReferrer;
	    $referer = $referer ? $referer : app()->homeUrl;
	    return $referer;
	}
	
	/**
	 * 通过$attributes从$postData里取出指定的属性对应的值的数组
	 * @example ilterPostData(array('id','name'), array('id'=>1, 'name'=>'name', 'age'=>30))
	 * @param array $attributes
	 * @param array $postDate
	 * @return array 返回新数组
	 */
	public static function filterPostData(array $attributes, array $postData)
	{
		$attributes_array = array();
		$attributes_array = array_flip($attributes);
		return array_intersect_key($postData, $attributes_array);
	}

	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $dir
	 */
	public static function getOctagonCoordinate($_radius, $coordinate)
	{
		$radiusX = 360/38000000 * $_radius;	// 半径
		$radiusY = 360/42000000 * $_radius;	// 半径
		
		$shortLineX = sin(deg2rad(22.5)) * $radiusX;
		$longLineX = cos(deg2rad(22.5)) * $radiusX;
		$shortLineY = sin(deg2rad(22.5)) * $radiusY;
		$longLineY = cos(deg2rad(22.5)) * $radiusY;
		
		$croords = array();
		$croords[] = array($coordinate['x'] - $longLineX, $coordinate['y'] + $shortLineY);
		$croords[] = array($coordinate['x'] - $shortLineX, $coordinate['y'] + $longLineY);
		$croords[] = array($coordinate['x'] + $shortLineX, $coordinate['y'] + $longLineY);
		$croords[] = array($coordinate['x'] + $longLineX, $coordinate['y'] + $shortLineY);
		$croords[] = array($coordinate['x'] + $longLineX, $coordinate['y'] - $shortLineY);
		$croords[] = array($coordinate['x'] + $shortLineX, $coordinate['y'] - $longLineY);
		$croords[] = array($coordinate['x'] - $shortLineX, $coordinate['y'] - $longLineY);
		$croords[] = array($coordinate['x'] - $longLineX, $coordinate['y'] - $shortLineY);
		return $croords;
	}

	/**
	 * 计算两个点之间的距离，返回值单位为米
	 * @param array $p1 第一个点，数组，格式：array('lat'=>lat, 'lon'=>lon)
	 * @param array $p2 第一个点，数组，格式：array('lat'=>lat, 'lon'=>lon)
	 * @return integer 两点间的距离，单位:米
	 */
    public static function distanceBetweenPoints($p1, $p2)
    {
        if (!$p1 || !$p2) return 0;
        
        $R = 6371000;
        $dLat = ($p2['lat']- $p1['lat']) * M_PI / 180;
        $dLon = ($p2['lon'] - $p1['lon']) * M_PI / 180;
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($p1['lat'] * M_PI / 180) * cos($p2['lat'] * M_PI / 180) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        return (int)$d;
    }
}