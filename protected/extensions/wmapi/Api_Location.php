<?php
/**
* 地址API文件
*/

/**
 * 地址API类
 */
class Api_Location
{
	/**
	 * 获取地址列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET location.getList</li>
	 *  <li>(required) keyword string GET 关键字</li>
	 *  <li>(required) city_id integer GET 城市id</li>
	 *  <li>type integer GET 类型：默认0，查询所有地址， 可行(1写字楼|2小区)</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getList($args, $format)
	{
		$keyword = trim(strip_tags($args['keyword'])); 	// 关键字
		$cityId = intval($args['city_id']);				// 城市id
		$type = intval($args['type']);					// 类型：1写字楼|2小区
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		if(empty($keyword)) {
			throw new CException('keyword参数错误', ApiError::ARGS_INVALID);
		}
		if(intval($cityId) <= 0) {
			throw new CException('city_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('name', $keyword);
		if($type && key_exists($type, Location::$types)) {
			$criteria->addColumnCondition(array('type'=>$type));
		}
		$criteria->addColumnCondition(array('city_id'=>$cityId, 'state'=>STATE_ENABLED));
		$pages = new CPagination(Location::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$criteria->order = 'use_nums desc';
    	$locations = Location::model()->findAll($criteria);
    	
		$attributes = array('id', 'city_id', 'district_id', 'name', 'map_x', 'map_y', 'food_nums', 'cake_nums', 'address', 'pinyin', 'use_nums', 'type', 'letter');
		$array = array();
		foreach ($locations as $l) {
			$array[] = ApiBase::object2array($l, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取地址列表数量
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET location.getCount</li>
	 *  <li>(required) keyword string GET 关键字</li>
	 *  <li>(required) city_id integer GET 城市id</li>
	 *  <li>type integer GET 类型：默认0，查询所有地址， 可行(1写字楼|2小区)</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @@return integer 获取到的地址的数量
	 */
	public function getCount($args, $format)
	{
		$keyword = trim(strip_tags($args['keyword'])); 	// 关键字
		$cityId = intval($args['city_id']);				// 城市id
		$type = intval($args['type']);					// 类型：1写字楼|2小区
		
		if(empty($keyword)) {
			throw new CException('keyword参数错误', ApiError::ARGS_INVALID);
		}
		if(intval($cityId) <= 0) {
			throw new CException('city_id参数错误', ApiError::ARGS_INVALID);
		}
		
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('name', $keyword);
		if($type && key_exists($type, Location::$types)) {
			$criteria->addColumnCondition(array('type'=>$type));
		}
		$criteria->addColumnCondition(array('city_id'=>$cityId, 'state'=>STATE_ENABLED));
		$criteria->order = 'use_nums desc';
    	$count = Location::model()->count($criteria);
    	
		return $count;
	}
	
}