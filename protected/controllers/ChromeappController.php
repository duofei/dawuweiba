<?php
class ChromeappController extends Controller
{
	public function actionRemind($cityId = null)
	{
		$cityId = intval($cityId);
		$cityId = $cityId ? $cityId : 1;
		
		/* 待审核订单数量 */
		$data['o_approve'] = 0;
//		$criteria = new CDbCriteria();
//		$criteria->addColumnCondition(array('city_id' => $cityId, 'verify_state'=>STATE_DISABLED, 'buy_type'=>Shop::BUYTYPE_PRINTER, 'status'=>Order::STATUS_UNDISPOSED));
//		$data['o_approve'] = Order::model()->count($criteria);

		/* 打印机不在线数量 */
		$data['p_unline'] = 0;
//		$criteria = new CDbCriteria();
//		$criteria->addColumnCondition(array('city_id' => $cityId));
//		$printer = Printer::model()->findAll($criteria);
//		if($printer) {
//			foreach ($printer as $v) {
//				if($v->phone && $v->shop && $v->status==Printer::STATE_PRINTER_CONTINUE && $v->getPrinterState() != Printer::STATE_ONLINE){
//					$data['p_unline']++;
//				}
//			}
//		}
		
		/* 打印机商铺待处理订单 */
		$data['o_undisposed'] = 0;
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id' => $cityId, 'buy_type'=>Shop::BUYTYPE_PRINTER, 'status'=>Order::STATUS_UNDISPOSED));
		$criteria->addCondition("create_time < " . time()-180);
		$criteria->addCondition('t.shop_id != 20');
		$data['o_undisposed'] = Order::model()->count($criteria);
		
		/* 客服处理电话订单提醒 */
		$data['o_phoneorder'] = 0;
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id' => $cityId, 'buy_type'=>Shop::BUYTYPE_TELPHONE, 'status'=>Order::STATUS_UNDISPOSED));
		$criteria->addCondition('t.consignee != ""');
		$criteria->addCondition('t.shop_id != 20');
		$data['o_phoneorder'] = Order::model()->count($criteria);
		
		echo json_encode($data);
	}
	
	public function actionUpdates()
	{
		$this->layout = 'blank';
		$this->render('updates');
	}
}