<?php
/**
* 系统信息API文件
*/

/**
 * 系统信息API类
 */
class Api_Message
{
	/**
	 * 获取系统信息列表
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET message.getList</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  <li>page integer GET 页码, 默认为:1</li>
	 *  <li>pagenum integer GET 返回的数据格式, 默认为:50</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getList($args, $format)
	{
		$page = intval($args['page']);
		$page = $page ? $page : ApiBase::DEFAULT_PAGE;
		$pageNum = intval($args['pagenum']);
		$pageNum = $pageNum ? $pageNum : ApiBase::DEFAULT_PAGENUM;
		
		$criteria = new CDbCriteria();
		$pages = new CPagination(Message::model()->count($criteria));
	    $pages->pageSize = $pageNum;
	    $pages->currentPage = ($page-1);
		$pages->applyLimit($criteria);
		$criteria->order = 'create_time desc';
		$message = Message::model()->findAll();
		if(!$message) {
			return ApiBase::$noResult;
		}
		
		$attributes = array('id', 'title', 'content', 'create_time');
		$array = array();
		foreach ($message as $m) {
			$array[] = ApiBase::object2array($m, $attributes);
		}
		return $array;
	}
	
	/**
	 * 获取一个系统信息
	 * @param array $args
	 * 	<ul>
	 *  <li>(required) apikey string GET 申请的APIKEY</li>
	 *  <li>(required) method string GET message.getInfo</li>
	 *  <li>(required) message_id integer GET 系统信息id</li>
	 *  <li>format string GET 返回的数据格式, 默认为:JSON, 可选:(XML|JSON)</li>
	 *  </ul>
	 * @param string $format 返回的数据格式
	 * @return mixed 根据format参数返回(XML|JSON)
	 */
	public function getInfo($args, $format)
	{
		$messageId = intval($args['message_id']);
		if($messageId <= 0) {
			throw new CException('message_id参数错误', ApiError::ARGS_INVALID);
		}
		$message = Message::model()->findByPk($messageId);
		if(!$message) {
			throw new CException('message_id参数错误', ApiError::ARGS_INVALID);
		}
		$attributes = array('id', 'title', 'content', 'create_time');
		$array = array();
		return ApiBase::object2array($message, $attributes);
	}
	
}

