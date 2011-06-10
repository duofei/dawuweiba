<?php

class ShopController extends Controller
{
	/**
	 * 商铺列表
	 */
    public function actionList()
    {
    	if(!$_SESSION['super_shop']) {
    		$this->redirect(url('shopcp/shop/profile'));
    		exit;
    	}
    	$criteria = new CDbCriteria();
	    $criteria->addColumnCondition(array('yewu_id' => user()->id));
	    if($_GET['shop_name']) {
	    	$criteria->addSearchCondition('shop_name', $_GET['shop_name']);
	    }
	    $criteria->order = 'create_time desc';
	    $pages = new CPagination(Shop::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
	    $shops = Shop::model()->findAll($criteria);

	    $this->pageTitle = '商铺列表';
        $this->render('list', array('shops'=>$shops, 'pages'=>$pages));
    }
    
    /**
     * 设置某一个商铺的SESSION
     */
    public function actionSetSession($id)
    {
    	$shopId = intval($id);
    	$shop = Shop::model()->findByPk($shopId);
    	//if($shop && $shop->yewu_id == user()->id && $shop->state != STATE_ENABLED) {
    	if($shop && $shop->yewu_id == user()->id) {
    		$session = app()->session;
        	$session['shop'] = $shop;
        	$this->redirect(url('shopcp/shop/profile'));
    	}
    	$this->redirect(url('shopcp/shop/list'));
    }
    
    /**
     *	创建一个新商铺
     */
    public function actionCreate()
    {
    	if(!$_SESSION['super_shop']) {
    		$this->redirect(url('shopcp/shop/profile'));
    		exit;
    	}
    	$shop = new Shop('adminPost');
    	$shopTag = ShopCategory::getShopCategoryArray();
    	
    	if(app()->request->isPostRequest && isset($_POST['Shop'])) {
			$shop->attributes = $_POST['Shop'];
			$shop->category_id = ShopCategory::CATEGORY_FOOD;
			$shop->yewu_id = user()->id;
			if($shop->save()) {
				if($_POST['tags']) {
	    			$tagArray = array();
	    			foreach($_POST['tags'] as $v) {
	    				$tagArray[] = $shopTag[$v];
	    			}
	    			Tag::addShopTag($shop->id, $tagArray);
	    		}
				$this->redirect(url('shopcp/shop/list'));
			}
		}
        $this->render('create', array('shop'=>$shop, 'shopTag'=>$shopTag));
    }
    
    /**
     * 从地图上查看商铺
     */
    public function actionDitu()
    {
    	if(app()->request->isPostRequest && isset($_POST['map_region'])) {
			$map_region = $_POST['map_region'];
			$maxx = $maxy = 0;
			$minx = $miny = 1000;
    		$data = explode(Shop::SEPARATOR_REGION_POINT, $map_region);
	        foreach ($data as $v) {
	        	$temp = explode(Shop::SEPARATOR_REGION_LATLON, $v);
	            $points[] = explode(Shop::SEPARATOR_REGION_LATLON, $v);
	            if($temp[0] > $maxx) $maxx = $temp[0];
	            if($temp[0] < $minx) $minx = $temp[0];
	            if($temp[1] > $maxy) $maxy = $temp[1];
	            if($temp[1] < $miny) $miny = $temp[1];
	        }
	        
	        $criteria = new CDbCriteria();
	        $criteria->addBetweenCondition('map_x', $minx, $maxx);
	        $criteria->addBetweenCondition('map_y', $miny, $maxy);
	        $shops = Shop::model()->findAll($criteria);
	        $newshops = array();
	        foreach ($shops as $shop) {
	        	if(CdcBetaTools::pointInPolygon($points, $shop->map_x, $shop->map_y)) {
	        		$newshops[] = $shop;
	        	}
	        }
		}
		$criteria = new CDbCriteria();
		$criteria->select = 'id,shop_name,map_x,map_y,address';
		$shoplist = Shop::model()->findAll($criteria);
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('city_id'=>$this->city['id']));
		$district = District::model()->findAll($criteria);
		
    	$this->render('ditu', array('shops'=>$newshops, 'map_region'=>$map_region, 'shoplist'=>$shoplist, 'district'=>$district));
    }
    
    /**
     * 商家资料修改
     * @param integer $shopid 商家ID，如果为空则取cookie中的，如果不为空，则取该ID的商家资料
     */
	public function actionProfile()
	{
	    $shop_info = Shop::model()->findByPk($_SESSION['shop']->id);
		if(app()->request->isPostRequest && isset($_POST['Shop'])) {
			$shop_info->transport_condition = '';
	    	$shop_info->transport_condition2 = '';
	    	$shop_info->transport_condition3 = '';
	    	
			$shop_info->attributes = $_POST['Shop'];
			if($shop_info->state==Shop::STATE_PSEUDO) {
				$shop_info->state = Shop::STATE_UNSETTLED;
			}
			if(!$shop_info->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop_info));
			} else {
				$session = app()->session;
				$session['shop'] = $shop_info;
			}
		}
	    $shop_info->reserve_hour = $shop_info->reserve_hour ? $shop_info->reserve_hour : 1;
		$shop_info->group_success_price += 0;
		
	    $this->pageTitle = '店铺设置';
		$data = array(
	    	'店铺设置' => array(
	    		'id' => 'profile',
	    		'content' => $this->renderPartial('profile', array('shop_info' => $shop_info), true)
	    	)
	    );
	    
		$this->render('/public/tab', array('tabs'=>$data));
	}

	/**
	 * 更改商家营业状态
	 * @param integer $state 营业状态，营业中/休息中/关闭
	 * @param integer $shopid 商家ID，如果为空则取cookie中的，如果不为空，则取该ID的商家资料
	 */
	public function actionState()
	{
		$shop_info = Shop::model()->findByPk($_SESSION['shop']->id);
	    if (app()->request->isPostRequest && isset($_POST['Shop'])) {
	    	$shop_info->attributes = $_POST['Shop'];
	    	
	    	if (!$shop_info->save()) {
	    		user()->setFlash('errorSummary', CHtml::errorSummary($shop_info));
	    	}else{
	    		$_SESSION['shop']->business_state = $shop_info->business_state;
	    	}
	    }
	    $data = array(
	    	'营业状态管理' => array(
	    		'id' => 'state',
	    		'content' => $this->renderPartial('state', array('shop_info' => $shop_info), true)
	    	)
	    );
	    
	    $this->pageTitle = '营业状态管理';
		$this->render('/public/tab', array('tabs'=>$data));
	}

	/**
	 * 修改logo
	 */
	public function actionLogo()
	{
		$shop_info = Shop::model()->findByPk($_SESSION['shop']->id);
		if ($shop_info->logo) {
			$oldfile = param('staticBasePath') . $shop_info->logo;
		}
		
		$file = CUploadedFile::getInstanceByName('Shop[logo]');
		if ($file->hasError || !$file) {
	        $error = '上传错误';
	    } else {
			$filePath = CdcBetaTools::makeUploadPath('shop');
	        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
	        $fileSavePath = $filePath['absolute'] . $filename;
	        if ($file->saveAs($fileSavePath)) {
	        	$fileUrl = $filePath['relative'] . $filename;
		        $shop_info->logo = $fileUrl;
				$shop_info->update();
				// 没有错误直接删除老的头像
				$error = CHtml::errorSummary($shop_info);
				if(!$error) {
					if(file_exists($oldfile)) {
						unlink($oldfile);
					}
					$script = '<script>top.location.href="' . url('shopcp/shop/profile') . '";</script>';
				}
	        } else {
	        	$error = '文件保存失败';
	        }
	    }
		$this->renderPartial('logo',array('error'=>$error, 'script'=>$script));
	}
	
	/**
	 * 商家认证
	 */
	public function actionApprove()
	{
		$shop_info = Shop::model()->findByPk($_SESSION['shop']->id);
		if(app()->request->isPostRequest && isset($_POST)) {
			$file = CUploadedFile::getInstanceByName('commercial_instrument');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('shop');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
		        	$fileUrl = $filePath['relative'] . $filename;
			        $shop_info->commercial_instrument = $fileUrl;
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
		    
			$file1 = CUploadedFile::getInstanceByName('sanitary_license');
			if ($file1->hasError || !$file1) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('shop');
		        $filename1 = CdcBetaTools::makeUploadFileName($file1->extensionName);
		        $fileSavePath1 = $filePath['absolute'] . $filename1;
		        
		        if ($file1->saveAs($fileSavePath1)) {
		        	$fileUrl1 = $filePath['relative'] . $filename1;
			        $shop_info->sanitary_license = $fileUrl1;
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
		    
			if(!$shop_info->update()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($shop_info));
				$this->redirect(url('shopcp/shop/approve'), array('shop_info' => $shop_info));
			}
		}
	    $data = array(
	    	'商家认证' => array(
	    		'id' => 'approve',
	    		'content' => $this->renderPartial('approve', array('shop_info' => $shop_info), true)
	    	)
	    );
	    
	    $this->pageTitle = '商家认证';
		$this->render('/public/tab', array('tabs'=>$data));
	}
	
	/**
	 * 网店合同
	 */
	public function actionContract()
	{
		$this->pageTitle = '网店合同';
		$this->render('contract');
	}

	/**
	 * 日志
	 */
	public function actionLog()
	{
		$this->pageTitle = '更新日志';
		$this->render('log');
	}
	
	/**
	 * 修改密码
	 */
	public function actionEditpassword()
	{
		if (app()->request->isPostRequest && isset($_POST)) {
			if($_POST['newpassword'] == $_POST['repassword']) {
				$user = User::model()->findByPk(user()->id);
				if($user->password == md5($_POST['oldpassword'])) {
					$user->password = md5($_POST['newpassword']);
					$user->clear_password = $_POST['newpassword'];
					if($user->save()) {
						$errorSummary = '密码修改成功！';
					} else {
						$errorSummary = CHtml::errorSummary($user);
					}
				} else {
					$errorSummary = '旧密码不正确！';
				}
			} else {
				$errorSummary = '两次密码不一致！';
			}
		}
		$this->render('editpassword', array(
			'errorSummary' => $errorSummary
		));
	}
	
	public function filters()
	{
	    return array(
	    	'postOnly + logo',
	    );
	}
}