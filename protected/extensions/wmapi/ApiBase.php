<?php
class ApiBase
{
	const DEFAULT_PAGE = 1; 									// 默认页码
	const DEFAULT_PAGENUM = 50; 								// 默认一页的数量
	
	public static $noResult = array(); 							// 没有查询结果
	public static $success = array('result'=>STATE_ENABLED);	// 操作成功
	public static $fail = array('result'=>STATE_DISABLED);		// 操作失败

	/**
	 * 检查数据返回格式
	 * @param string $format 返回格式，json|xml，默认为json
	 * @return string 检查之后的数据返回格式
	 */
	public static function checkFormat($format = 'json')
	{
	    $format = strtolower(strip_tags(trim($format)));
	    if (empty($format) || !in_array($format, array('json', 'xml')))
	        $format = 'json';
	    return $format;
	}
	
	/**
	 * 检查api命令
	 * @param string $method api命令
	 * 格式：class.method
	 * @throws CException 如果method不可用则抛出异常
	 * @return array array($class, $method)
	 */
	public static function checkMethod($method)
	{
	    $method = trim($method);
	    if (empty($method))
	        throw new CException('缺少method参数', ApiError::METHOD_NO_PARAM);
	    
	    $method = explode('.', $method);
	    $class = 'Api_' . ucfirst(strtolower(trim($method[0])));
	    $method = strtolower(trim($method[1]));
	    if (!@class_exists($class, true) || !method_exists($class, $method))
	        throw new CException('参数不正确', ApiError::METHOD_INVALID);
	    return array($class, $method);
	}
	
	/**
	 * 输出数据
	 * @param string $data 输出数据
	 * @param string $format 输出格式 json|xml
	 */
	public static function renderData($data, $format = 'json')
	{
	    $method = 'render' . ucfirst($format);
	    if (method_exists('ApiBase', $method))
	        self::$method($data);
	    else
	        throw new CException('缺少输出方法', ApiError::RENDER_NO_METHOD);
	}
	
	/**
	 * 输出Json数据
	 * @param string $data 输出数据
	 */
	public static function renderJson($data)
	{
	    echo CJSON::encode($data);
		exit;
	}
	/**
	 * 输出Xml数据
	 * @param string $data 输出数据
	 */
	public static function renderXml($data, $cdata = array())
	{
	    header('Content-type: text/xml; charset=' . app()->charset);
		$dom = new DOMDocument('1.0', 'utf-8');
	    $root = $dom->createElement('items');
	    $dom->appendChild($root);
		$dom = ApiBase::array2xml($dom, $root, $data, array('remark', 'desc', 'info', 'content'));
        echo $dom->saveXml();
	}

	/**
	 * 检查ApiKey是否正确
	 * @param string $apiKey
	 * @return api用户对应的user_id
	 */
	public static function checkApiKey($apiKey)
	{
	    if (empty($apiKey))
	        throw new CException('缺少apikey参数', ApiError::APIKEY_NO_EXIST);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('state'=>STATE_ENABLED, 'api_key'=>$apiKey));
		$userapi = UserApi::model()->find($criteria);
		if (null === $userapi) {
			throw new CException('api_key 不可用', ApiError::APIKEY_INVALID);
		} else {
			$userapi->request_nums++;
			$userapi->save(true, array('request_nums'));
			return $userapi->user_id;
		}
	}

	/**
	 * 用户名和密码是否正确
	 * @return 用户对象
	 */
	public static function checkUserPw($format)
	{
		$user = $_SERVER['PHP_AUTH_USER'];
		$pw = $_SERVER['PHP_AUTH_PW'];
		$criteria = new CDbCriteria();
		$criteria->select = 'id, username, state';
		$criteria->addColumnCondition(array('username'=>$user, 'password'=>md5($pw), 'state'=>STATE_ENABLED));
		$user = User::model()->find($criteria);
		if(null === $user) {
		    $data = array('errorCode' => ApiError::USER_CHECK_INVALID, 'errorMessage'=>'用户验证失败');
			self::renderData($data, $format);
		} else {
			return $user;
		}
		exit(0);
	}
	
	/**
	 * 数组转化为xml
	 * @param DOMDocument $dom Dom对象
	 * @param DOMElement $root Dom对象根结点
	 * @param array $data 需要转化的数组
	 * @param array $cdata 需要使用CDATA输出的元素
	 * @return DOMDocument 转化之后Dom对象
	 */
	public static function array2xml($dom, $root, $data, $cdata = array())
	{
		if(is_array($data)) {
		    foreach ($data as $k => $v) {
	            if (is_int($k)) {
	                $element = $dom->createElement('item');
	                $dom = self::array2xml($dom, $element, $v, $cdata);
	            }
	            if (is_string($k) && is_array($v)) {
	                $element = $dom->createElement($k);
	                $dom = self::array2xml($dom, $element, $v, $cdata);
	            }
	            if (is_string($k) && !is_array($v)) {
	                $element = $dom->createElement($k);
	                $node = in_array($k, $cdata) ? $dom->createCDATASection($v) : $dom->createTextNode($v);
	                $element->appendChild($node);
	            }
	            $root->appendChild($element);
	        }
		}
        return $dom;
	}

	/**
	 * 对象格式化成相应的数组
	 * @param object $object
	 * @param array $attributes 属性数组
	 * @throws CException 如果object或attributes不正确
	 * @return array 返回格式化后的数组
	 */
	public static function object2array($object, $attributes)
	{
		if(!is_object($object) || !is_array($attributes)) {
			 throw new CException('参数错误', ApiError::BASE_PARAM_INVALID);
		}
		$array = array();
		foreach($attributes as $a) {
			if(null !== $object->$a) {
				$array[$a] = $object->$a;
			}
		}
		return $array;
	}
}