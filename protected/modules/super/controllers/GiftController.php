<?php
class GiftController extends Controller
{
	public function actionList($state = '', $integral='')
	{
    	$condition = new CDbCriteria();
    	$sort = new CSort('Gift');
	   	$sort->attributes = array(
	    	'integral'=> array(
	    		'label' => '兑换积分',
	    		'asc' => 'integral asc',
	    		'desc' => 'integral desc',
	    	),
	    	'state'=> array(
	    		'label' => '状态',
	    		'asc' => 't.state asc',
	    		'desc' => 't.state desc',
	    	), 'favorite_nums', 'rate_avg',
	    );
	   	$sort->applyOrder($condition);
	   	
		$gift = Gift::model()->findAll($condition);
	    $this->render('list', array('gift'=>$gift, 'sort'=>$sort));
	}

	/**
	 * 添加 编辑
	 */
	public function actionCreate($id = 0)
	{
		$id = (int)$id;
		$gift = $id ? Gift::model()->findByPk($id) : new Gift();
		if ($gift->small_pic) {
			$oldsmallfile = param('staticBasePath') . $gift->small_pic;
		}
		if (app()->request->isPostRequest && isset($_POST['Gift'])) {
			$gift->attributes = $_POST['Gift'];
			$gift->small_pic = $_POST['Gift']['picOriginal'];
			
			$file = CUploadedFile::getInstanceByName('Gift[small_pic]');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('gift');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
					$image = Yii::app()->image->load($fileSavePath);
					$image->resize(150, 150);
					if ($image->save($filePath['absolute'] . 'small' . $filename)){
						$fileUrl = $filePath['relative'] . 'small' . $filename;
						$gift->small_pic = $fileUrl;
					}
					
		        	if(file_exists($oldsmallfile)) {
						unlink($oldsmallfile);
					}
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
			if ($gift->save()) {
			    AdminLog::saveManageLog('添加礼品(' . $gift->name . ')信息');
				$this->redirect(url('super/gift/list'));
			}else {
				user()->setFlash('errorSummary',CHtml::errorSummary($gift));
			}
		}
		$this->render('create', array('model'=>$gift));
	}
	
	/**
	 * 删除
	 */
	public function actionDelete($id)
	{
	    $id = (int)$id;
		if ($id) {
			$gift = Gift::model()->findByPk($id);
			if (!$gift->delete()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($gift));
			}
		}
		$this->redirect(url('super/gift/list'));
	}
	
	/**
	 * 状态调整
	 */
	public function actionState($id, $state)
	{
	    $id = (int)$id;
	    $state = (int)$state;
		if ($id) {
			$gift = Gift::model()->findByPk($id);
			$gift->state = $state;
			if (!$gift->save()) {
				user()->setFlash('errorSummary',CHtml::errorSummary($gift));
			}
		}
		$this->redirect(url('super/gift/list'));
	}
	
	/**
	 * 图片上传
	 */
	public function actionUpload()
	{
		if (app()->request->isPostRequest && isset($_POST)) {
			$file = CUploadedFile::getInstanceByName('pic');
			if ($file->hasError || !$file) {
		        $error = '上传错误';
		    } else {
				$filePath = CdcBetaTools::makeUploadPath('gift');
		        $filename = CdcBetaTools::makeUploadFileName($file->extensionName);
		        $fileSavePath = $filePath['absolute'] . $filename;
		        
		        if ($file->saveAs($fileSavePath)) {
					$image = Yii::app()->image->load($fileSavePath);
					$fileUrl = $filePath['relative'] . $filename;
					$data = array(
						'status'=>'0',
						'imgUrl'=>sbu($fileUrl)
					);
					echo json_encode($data);
		        } else {
		        	$error = '文件保存失败';
		        }
		    }
		    if ($error) {
		    	$data = array(
					'status'=>'1',
					'imgUrl'=>'图片上传出错'
				);
				echo json_encode($data);
		    }
		}
	}

	public function actionExchangeLog()
	{
	    $criteria = new CDbCriteria();
	    $criteria->limit = 20;
	    $criteria->order = 't.state asc, t.id desc';
	    
	    $pages = new CPagination(GiftExchangeLog::model()->count($criteria));
	    $pages->setPageSize(20);
	    $pages->applyLimit($criteria);
	    
	    $data = GiftExchangeLog::model()->with('gift', 'user')->findAll($criteria);
	    
        $this->render('exchangelog_list', array(
            'data' => $data,
            'pages' => $pages,
        ));
	}
	
	public function actionEditLog($giftid)
	{
	    $giftid = (int)$giftid;
	    $model = GiftExchangeLog::model()->findByPk($giftid);
	    if (request()->isPostRequest && isset($_POST['GiftExchangeLog'])) {
	        $model->attributes = $_POST['GiftExchangeLog'];
	        if ($model->save()) {
	            $this->redirect(url('super/gift/exchangelog'));
	        }
	    }
	    $this->render('editlog', array('model' => $model));
	}
	
}