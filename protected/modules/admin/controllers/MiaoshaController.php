<?php

class MiaoshaController extends Controller
{
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 't.active_time desc';
		$miaosha = Miaosha::model()->with('shop')->findAll($criteria);
		$this->render('list', array(
			'miaosha' => $miaosha
		));
	}
	
	public function actionEdit()
	{
		$id = intval($_GET['id']);
		$miaosha = null;
		if($id) {
			$miaosha = Miaosha::model()->findByPk($id);
		}
		if(null === $miaosha) {
			$miaosha = new Miaosha();
			$miaosha->active_num = 5;
			$miaosha->untrue_num = 3;
			$miaosha->active_time = time();
			$miaosha->state = STATE_ENABLED;
		}
		if(app()->request->isPostRequest && isset($_POST['Miaosha'])) {
			$miaosha->attributes = $_POST['Miaosha'];
			$active_time = mktime(intval($_POST['h']),intval($_POST['i']),0,intval($_POST['m']),intval($_POST['d']),intval($_POST['y']));
			$miaosha->active_time = $active_time;
			$goodsIds = $_POST['goods_id'];
			$miaosha->save();
			if($miaosha->id) {
				$sql = "delete from wm_MiaoshaGoods where miaosha_id=". $miaosha->id;
				$command = app()->db->createCommand($sql);
				$command->execute();
				if($goodsIds) {
					$sql = "insert into wm_MiaoshaGoods values ";
					$dot = '';
					foreach ($goodsIds as $gid) {
						$sql .=  $dot . "(null, $miaosha->id, $gid)";
						$dot = ',';
					}
					$command = app()->db->createCommand($sql);
					$command->execute();
				}
			}
			$this->redirect(url('admin/miaosha/list'));
			exit;
		}
		$this->render('edit', array(
			'miaosha' => $miaosha
		));
	}
	
	public function actionSearchgoods($shopid,$miaoshaid)
	{
		$shopid = intval($shopid);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('shop_id'=>$shopid));
		$goods = Goods::model()->with('foodGoods')->findAll($criteria);
		
		$miaoshaid = intval($miaoshaid);
		$checkArray = array();
		if($miaoshaid) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('miaosha_id'=>$miaoshaid));
			$goodsId = MiaoshaGoods::model()->findAll($criteria);
			foreach ((array)$goodsId as $g) {
				$checkArray[] = $g->goods_id;
			}
		}
		$this->render('searchgoods', array(
			'goods'=>$goods,
			'checkArray' => $checkArray
		));
	}

	public function actionResult($id)
	{
		$miaosha_id = intval($id);
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('miaosha_id'=>$miaosha_id));
		$criteria->order = 't.id desc';
		$result = MiaoshaResult::model()->with('miaosha', 'user', 'goods')->findAll($criteria);
		$this->render('result', array(
			'result' => $result
		));
	}
}