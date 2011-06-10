<?php

class DenyipController extends Controller
{
	public function actionCreate()
	{
	    $model = new DenyIp();
	    
	    if (app()->request->isPostRequest && isset($_POST['DenyIp'])) {
	        $model->attributes = $_POST['DenyIp'];
	        
	        if ($model->save())
                $this->redirect(url('super/denyip/list'));
            else {
                $model->ip_start = long2ip($model->ip_start);
                $model->ip_end = long2ip($model->ip_end);
            }
	    }
	    
		$this->render('create', array('model' => $model));
	}
	
	public function actionList()
	{
	    $limit = 30;
	    $criteria = new CDbCriteria();
        $criteria->limit = $limit;
	    $pages = new CPagination(DenyIp::model()->count($criteria));
		$pages->pageSize = $limit;
		$pages->applyLimit($criteria);
		
		$sort = new CSort('DenyIp');
        $sort->defaultOrder = 't.id desc';
        $sort->applyOrder($criteria);
	    $ips = DenyIp::model()->findAll($criteria);
	    
	    $data = array(
	        'pages' => $pages,
	        'sort' => $sort,
	        'ips' => $ips,
	    );
	    $this->render('list', $data);
	}
	
	public function actionDelete($ipid)
	{
		$ipid = (int)$ipid;
		if (!empty($ipid)) {
			$result = DenyIp::model()->findByPk($ipid)->delete();
			$data['result'] = $result ? 1 : 0;
			$data['message'] = '删除' . ($result ? '成功' : '失败');
		} else {
		    $data['result'] = -1;
			$data['message'] = '参数错误';
		}
		echo json_encode($data);
	}
}