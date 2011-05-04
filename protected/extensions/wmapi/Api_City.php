<?php
/**
* 开通城市API文件
*/

/**
 * 开通城市API类
 */
class Api_City
{
	/**
	 * 获取开通城市列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET city.getList</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getList($args, $format)
	{
		$citys = City::model()->findAll();
		if(!$citys) {
			return ApiBase::$noResult;
		}
		$attributes = array('id', 'name', 'map_x', 'map_y');
		$array = array();
		foreach ($citys as $city) {
			$array[] = ApiBase::object2array($city, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取一个城市信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET city.getInfo</li>
	 *  <li>(required) city_id integer GET 城市id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$cityId = intval($args['city_id']);
		if($cityId <= 0) {
			throw new CException('city_id参数错误', ApiError::ARGS_INVALID);
		}
		$city = City::model()->findByPk($cityId);
		if(!$city) {
			throw new CException('city_id参数错误', ApiError::ARGS_INVALID);
		}
		$attributes = array('id', 'name', 'map_x', 'map_y');
		$array = array();
		return ApiBase::object2array($city, $attributes);
	}
	
}

