<?php
class ChromeappController extends Controller
{
	public function actionRemind($cityId = null)
	{
		$cityId = intval($cityId);
		$cityId = $cityId ? $cityId : 1;
		
		/* 打印机不在线数量 */
		$data['p_unline'] = 0;
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id' => $cityId));
		$printer = Printer::model()->findAll($criteria);
		if($printer) {
			foreach ($printer as $v) {
				if($v->phone && $v->shop && $v->status==Printer::STATE_PRINTER_CONTINUE && $v->getPrinterState() != Printer::STATE_ONLINE){
					$data['p_unline']++;
				}
			}
		}
		
		/* 待审核订单数量 */
		$data['o_approve'] = 0;
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id' => $cityId, 'verify_state'=>STATE_DISABLED, 'buy_type'=>Shop::BUYTYPE_PRINTER, 'status'=>Order::STATUS_UNDISPOSED));
		$data['o_approve'] = Order::model()->count($criteria);
		
		echo json_encode($data);
	}
	
	public function actionUpdates()
	{

	}
}