<?php

class ShopCreditController extends Controller
{
	private function _getPages($criteria)
	{
		$pages = new CPagination(ShopCreditLog::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
    /**
     * 信用评价显示
     */
	public function actionList()
	{
		$sixMonth = time() - (180 * 24 * 60 * 60);
		$LastMonth = time() - (30 * 24 * 60 * 60);
		$LastWeek = time() - (7 * 24 * 60 * 60);
		
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time>=' . $LastWeek); 
	    $Num['week_1'] = ShopCreditLog::model()->count($condition);

		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time>=' . $LastWeek); 
	    $Num['week_0'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time>=' . $LastMonth); 
	    $Num['month_1'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time>=' . $LastMonth); 
	    $Num['month_0'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time>=' . $sixMonth); 
	    $Num['six_1'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time>=' . $sixMonth); 
	    $Num['six_0'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time<=' . $sixMonth); 
	    $Num['before_1'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time<=' . $sixMonth); 
	    $Num['before_0'] = ShopCreditLog::model()->count($condition);
	    
	    $Num['week'] = $Num['week_1'] + $Num['week_0'];
	    $Num['month'] = $Num['month_1'] + $Num['month_0'];
	    $Num['six'] = $Num['six_1'] + $Num['six_0'];
	    $Num['before'] = $Num['before_1'] + $Num['before_0'];
	    
	    $Num['amount_1'] = $Num['six_1'] + $Num['before_1'];
	    $Num['amount_0'] = $Num['six_0'] + $Num['before_0'];
	    $Num['amount'] = $Num['six'] + $Num['before'];
	    
	    if($Num['amount']) {
	    	$Num['probability'] = round(($Num['amount_1']/$Num['amount']) * 100, 2);
	    } else {
	    	$Num['probability'] = 0;
	    }
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->limit = 20;
	    $credit_list_10 = ShopCreditLog::model()->findAll($condition);
	    
		$data = array(
	    	'信用评价' => array(
	    		'id' => 'list',
	    		'content' => $this->renderPartial('list', array('credit_list'=>$credit_list_10, 'num'=>$Num), true)
	    	)
	    );
	    
	    $this->pageTitle = '信用评价';
		$this->render('/public/tab', array('tabs'=>$data));
	}
	
	/**
	 * 按日期范围显示评价
	 * Enter description here ...
	 */
	public function actionCreditSort($sort = '', $evaluate = '')
	{
		$sixMonth = time() - (180 * 24 * 60 * 60);
		$LastMonth = time() - (30 * 24 * 60 * 60);
		$LastWeek = time() - (7 * 24 * 60 * 60);
		
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time>=' . $LastWeek); 
	    $Num['week_1'] = ShopCreditLog::model()->count($condition);

		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time>=' . $LastWeek); 
	    $Num['week_0'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time>=' . $LastMonth); 
	    $Num['month_1'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time>=' . $LastMonth); 
	    $Num['month_0'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time>=' . $sixMonth); 
	    $Num['six_1'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time>=' . $sixMonth); 
	    $Num['six_0'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
	    $condition->addCondition('create_time<=' . $sixMonth); 
	    $Num['before_1'] = ShopCreditLog::model()->count($condition);
	    
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
	    $condition->addCondition('create_time<=' . $sixMonth); 
	    $Num['before_0'] = ShopCreditLog::model()->count($condition);
	    
	    $Num['week'] = $Num['week_1'] + $Num['week_0'];
	    $Num['month'] = $Num['month_1'] + $Num['month_0'];
	    $Num['six'] = $Num['six_1'] + $Num['six_0'];
	    $Num['before'] = $Num['before_1'] + $Num['before_0'];
	    
	    $Num['amount_1'] = $Num['six_1'] + $Num['before_1'];
	    $Num['amount_0'] = $Num['six_0'] + $Num['before_0'];
	    $Num['amount'] = $Num['six'] + $Num['before'];
	    
		if($Num['amount']) {
	    	$Num['probability'] = round(($Num['amount_1']/$Num['amount']) * 100, 2);
	    } else {
	    	$Num['probability'] = 0;
	    }
	    
	    $sort = strip_tags(trim($sort));
//	    $evaluate = (int)$evaluate;
		if ($sort){
			if ($sort == 'week') {
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			    if ($evaluate != ''){
					if ($evaluate == ShopCreditLog::EVALUATE_GOOD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
					}else if ($evaluate == ShopCreditLog::EVALUATE_BAD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
					}
			    }
			    $condition->addCondition('create_time>=' . $LastWeek); 
	    		$pages = $this->_getPages($condition);
			    $credit_list_sort = ShopCreditLog::model()->findAll($condition);
			}
			if ($sort == 'month') {
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			    if ($evaluate != ''){
					if ($evaluate == ShopCreditLog::EVALUATE_GOOD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
					}else if ($evaluate == ShopCreditLog::EVALUATE_BAD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
					}
			    }
			    $condition->addCondition('create_time>=' . $LastMonth); 
	    		$pages = $this->_getPages($condition);
			    $credit_list_sort = ShopCreditLog::model()->findAll($condition);
			}
			if ($sort == 'six') {
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			    if ($evaluate != ''){
					if ($evaluate == ShopCreditLog::EVALUATE_GOOD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
					}else if ($evaluate == ShopCreditLog::EVALUATE_BAD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
					}
			    }
			    $condition->addCondition('create_time>=' . $sixMonth); 
	    		$pages = $this->_getPages($condition);
			    $credit_list_sort = ShopCreditLog::model()->findAll($condition);
			}
			if ($sort == 'before') {
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			    if ($evaluate != ''){
					if ($evaluate == ShopCreditLog::EVALUATE_GOOD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
					}else if ($evaluate == ShopCreditLog::EVALUATE_BAD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
					}
			    }
			    $condition->addCondition('create_time<=' . $sixMonth); 
	    		$pages = $this->_getPages($condition);
			    $credit_list_sort = ShopCreditLog::model()->findAll($condition);
			}
			if ($sort == 'amount') {
				$condition = new CDbCriteria();
			    $condition->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			    if ($evaluate != ''){
					if ($evaluate == ShopCreditLog::EVALUATE_GOOD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_GOOD)); 
					}else if ($evaluate == ShopCreditLog::EVALUATE_BAD){
					    $condition->addColumnCondition(array('evaluate' => ShopCreditLog::EVALUATE_BAD)); 
					}
			    }
	    		$pages = $this->_getPages($condition);
			    $credit_list_sort = ShopCreditLog::model()->findAll($condition);
			}
		}
	    
		$data = array(
	    	'信用评价' => array(
	    		'id' => 'list',
	    		'content' => $this->renderPartial('list', array('credit_list'=>$credit_list_sort, 'num'=>$Num, 'pages'=>$pages), true)
	    	)
	    );
	    
	    $this->pageTitle = '信用评价';
		$this->render('/public/tab', array('tabs'=>$data));
		
	}
	
}