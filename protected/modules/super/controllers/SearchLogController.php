<?php
class SearchLogController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(SearchLog::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	/**
	 * 查看
	 * Enter description here ...
	 */
	public function actionSearch()
	{
		foreach ((array)$_GET['Search'] as $key=>$val){
			$search[$key] = strip_tags(trim($val));
		}
		$citylist = City::getCityArray();
		if($search) {
			$start_time = strtotime($search['create_time_start']);
			$end_time = strtotime($search['create_time_end']);
			$end_time = strtotime('next Day', $end_time);
			
	    	if ($search['city_id'] != '') {
				$condition = new CDbCriteria();
			    if ($search['keywords'] != '') {
			    	$condition->addSearchCondition('keywords', $search['keywords']);
			    }
			    if ($search['user_id'] != '') {
			    	$condition->addColumnCondition(array('user_id' => $search['user_id']));
			    }
			    if ($search['create_ip'] != '') {
			    	$condition->addColumnCondition(array('create_ip' => $search['create_ip']));
			    }
				if ($search['create_time_start']) {
			    	$condition->addCondition('create_time>=' . $start_time);
			    }
			    if ($search['create_time_end']) {
			    	$condition->addCondition('create_time<=' . $end_time);
			    }
	   			$condition->addColumnCondition(array('city_id' => $search['city_id']));
			    $condition->order = 'id desc';
			    $pages = $this->_getPages($condition);
			    $searchs = SearchLog::model()->findAll($condition);
	    	}
		    $this->render('search', array('search' => $search, 'searchs' => $searchs, 'citylist'=>$citylist, 'pages' => $pages));
		}else{
			$condition = new CDbCriteria();
	    	$condition->order = 'id desc';
	   		$condition->limit = 10;
	   		$searchs = SearchLog::model()->findAll($condition);
		    $this->render('search', array('searchs' => $searchs, 'citylist'=>$citylist));
		}
	}
	
	/**
	 * 统计
	 * Enter description here ...
	 */
	public function actionStatistics($id = 0)
	{
		$id = (int)$id;
		$citylist = City::getCityArray();
		if($id) {
			$condition = new CDbCriteria();
			$searchs = app()->db->createCommand("select keywords,count(*) as c from {{SearchLog}} as t where city_id=".$id." group by keywords ORDER BY c desc limit 30")->queryAll();
		}
	    $this->render('statistics', array('searchs' => $searchs, 'citylist'=>$citylist));
	}
	
}