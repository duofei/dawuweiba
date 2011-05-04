<?php

class InviterhidecodeController extends Controller
{
	
	/**
	 * 分页
	 */
	private function _getPages($criteria)
	{
		$pages = new CPagination(UserInviterHideCode::model()->count($criteria));
		$pages->pageSize = 20;
		$pages->applyLimit($criteria);
		return $pages;
	}
	
	public function actionList()
	{
		$criteria = new CDbCriteria();
		$pages = $this->_getPages($criteria);
		$criteria->order = 't.id desc';
		$list = UserInviterHideCode::model()->findAll($criteria);
		$this->render('list', array(
			'list' => $list,
			'pages' => $pages
		));
	}
	
	public function actionCreate($id=0)
	{
		$id = (int)$id;
        if (0 === $id) {
            $model = new UserInviterHideCode();
			$model->integral = param('defaultInviterBcIntegral');
			$model->state = STATE_ENABLED;
        } else {
            $model = UserInviterHideCode::model()->findByPk($id);
        }
        
        if (request()->isPostRequest && isset($_POST['UserInviterHideCode'])) {
            $model->attributes = $_POST['UserInviterHideCode'];
            if ($model->save())
                $this->redirect(url('admin/inviterhidecode/list'));
        }
		
		$this->render('create', array(
			'model' => $model
		));
	}
	
	public function actionDelete($id=0)
	{
		$id = intval($id);
		
		if(0 !== $id) {
			$model = UserInviterHideCode::model()->findByPk($id);
			if($model) {
				$model->delete();
			}
		}
		
		$this->redirect(url('admin/inviterhidecode/list'));
	}
}