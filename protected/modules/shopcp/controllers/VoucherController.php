<?php

class VoucherController extends Controller
{
	/**
	 * 优惠券列表
	 */
	public function actionList()
	{
		$condition = new CDbCriteria();
	    $condition->addColumnCondition(array('t.shop_id' => $_SESSION['shop']->id));
		$condition->order = 't.end_time';
	    
	    $vouchers = Voucher::model()->with('goods')->findAll($condition);
	    
	    $this->pageTitle = '优惠券列表';
	    $this->render('list', array(
	    	'vouchers' => $vouchers
	    ));
	}
	
    /**
     * 添加优惠券
     */
	public function actionCreate()
	{
		$voucher = new Voucher();
		$c = new CDbCriteria();
		$c->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		$goods_list = Goods::model()->findAll($c);
		$error = array();
		if(app()->request->isPostRequest && isset($_POST)) {
			$post['goods_id'] = intval($_POST['goodsid']);
			$post['shop_id'] = $_SESSION['shop']->id;
			$post['price'] = floatval($_POST['price']);
			$post['end_time'] = strtotime($_POST['end_time']) + 86399;
			
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('goods_id'=>$post['goods_id']));
			$countGoods = Voucher::model()->count($criteria);
			
			if(!$post['goods_id']) $error[] = '没有选择商品';
			if(!$post['price']) $error[] = '没有填写优惠价';
			if($countGoods > 0) $error[] = '此商品已添加优惠券，如果要重新设置，请先删除此商品对应的优惠券';
			if($post['end_time']<time()) $error[] = '选择的日期不正确，不能小于当天';
			
			if($post['goods_id'] && $post['price'] && $post['end_time']>time() && $countGoods<=0) {
				$voucher->attributes = $post;
				if($voucher->save() && $this->createImg($voucher->id)) {
					$this->redirect(url('shopcp/voucher/list'));
				}
			}
			
		}
		$this->pageTitle = '添加优惠券';
		$this->render('create', array(
			'voucher' => $voucher,
			'goods_list' => $goods_list,
			'post' => $post,
			'error' => $error
		));
	}
	
	private function createImg($vid)
	{
		if(!$vid) return false;
		$voucher = Voucher::model()->with('shop','goods')->findByPk($vid);
		if(null===$voucher) return false;
		
		$imgVoucher = param('staticBasePath') . 'voucher/voucher.png';
		$fontFile = param('staticBasePath') . 'voucher/MSYH.TTF';
		$fontNumFile = param('staticBasePath') . 'voucher/ariblk.TTF';
		
		$im = imagecreatefrompng($imgVoucher);
		$textcolor = imagecolorallocate($im, 117, 117, 117);
		$textcolor2  = imagecolorallocate($im, 250, 1, 10);
		$textcolor3  = imagecolorallocate($im, 255, 255, 255);
		
		// 写入商铺名称
		imagettftext($im, 16, 0, 400, 45, $textcolor, $fontFile, $voucher->shop->shop_name);
		
		// 写入商品名称
		imagettftext($im, 16, 0, 400, 88, $textcolor, $fontFile, $voucher->goods->name);

		// 写入原价
		imagettftext($im, 18, 0, 354, 175, $textcolor2, $fontFile, $voucher->goods->wmPrice);
		
		// 写入现价
		imagettftext($im, 50, 0, 480, 178, $textcolor2, $fontNumFile, $voucher->price+0);
		
		// 写入有效期
		imagettftext($im, 14, 0, 350, 208, $textcolor3, $fontFile, date("Y/m/d", $voucher->create_time) . ' - ' .date("Y/m/d", $voucher->end_time));
		$path = CdcBetaTools::makeUploadPath('voucher');
		$dscfile =  $path['absolute'] . $voucher->shop_id . '_' . $voucher->goods_id . '.png';
		$img = $path['relative'] . $voucher->shop_id . '_' . $voucher->goods_id . '.png';
		if(imagepng($im, $dscfile)) {
			$voucher->img = $img;
			if($voucher->save()) {
				return true;
			}
		}
		return false;
	}

	public function actionTest()
	{
		$this->createImg(3);
		$this->createImg(2);
		$this->createImg(5);
	}
	
	/**
	 * 删除优惠券
	 */
	public function actionDelete($id = 0)
	{
    	$id = (int)$id;
	    if ($id) {
			$c = new CDbCriteria();
		    $c->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
			$voucher = Voucher::model()->findByPk($id, $c);
			$img = $voucher->img;
			if ($voucher->delete()) {
				// 删除图片
				@unlink(param('staticBasePath') . $img);
				
				/* 查看是不是还有优惠券 */
				$c2 = new CDbCriteria();
		   	 	$c2->addColumnCondition(array('shop_id' => $_SESSION['shop']->id));
		   	 	$c2->addCondition('end_time > ' . time());
				$count = Voucher::model()->count($c2);
				if($count == 0) {
					$shop = Shop::model()->findByPk($_SESSION['shop']->id);
					$shop->is_voucher = STATE_DISABLED;
					$shop->save();
				}
			}
		}
		$this->redirect(url('shopcp/voucher/list'));
	}

}