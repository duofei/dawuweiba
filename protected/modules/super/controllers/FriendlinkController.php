<?php

class FriendlinkController extends Controller
{
	/**
	 * 友情链接列表
	 */
    public function actionFriend($city_id = 0, $keyword = '')
	{
		$city_id = intval($city_id);
		$keyword = trim(strip_tags($keyword));
		$criteria = new CDbCriteria();
		if($city_id) {
			$criteria->addColumnCondition(array('city_id'=>$city_id));
		}
		if($keyword) {
			$criteria->addSearchCondition('name', $keyword);
		}
		$criteria->order = 'order_id desc, id desc';
		$links = FriendLink::model()->findAll($criteria);
		$city = City::getCityArray();
		$this->render('list', array(
			'links' => $links,
			'city' => $city
		));
	}
	
	/**
	 * 删除友情链接
	 */
	public function actionDelete($fid)
	{
		$fid = intval($fid);
		FriendLink::model()->deleteByPk($fid);
		$this->redirect(app()->request->urlReferrer);
	}
	
	/**
	 * 更新友情链接
	 */
	public function actionUpdate()
	{
        foreach ($_POST['name'] as $fid => $v) {
            $attributes = array(
                'order_id' => $_POST['order_id'][$fid],
                'name' => $_POST['name'][$fid],
            	'homepage' => $_POST['homepage'][$fid],
                'logo' => $_POST['logo'][$fid],
                'desc' => $_POST['desc'][$fid],
            	'city_id' => $_POST['city_id'][$fid]
            );
            $model = FriendLink::model()->findByAttributes($attributes);
            if (null !== $model) continue;
            
            $model = FriendLink::model()->findByPk($fid);
            $model->attributes = $attributes;
            $result = $model->save();
            if ($result === false) {
                throw new CHttpException(400, $v . ' 更新错误...');
            }
        }
        $this->redirect(app()->request->urlReferrer);
	}
	
	/**
	 * 更改友情链接状态 
	 */	
	public function actionValid($fid)
	{
		$fid = intval($fid);
		$friendlind = FriendLink::model()->findByPk($fid);
		if($friendlind) {
			$friendlind->isvalid = intval(!$friendlind->isvalid);
			$friendlind->save();
		}
		$this->redirect(app()->request->urlReferrer);
	}
	
    public function actionCreate()
	{
	    $friendlink = new FriendLink();
	    if (app()->request->isPostRequest && isset($_POST['FriendLink'])) {
	        $friendlink->attributes = $_POST['FriendLink'];
	        if ($friendlink->save()) {
                $this->redirect(url('super/friendlink/friend'));
	        }
        }
        $city = City::getCityArray();
	    $this->render('create', array(
	    	'friendlink'=>$friendlink, 
	    	'city' => $city
	    ));
	}

}