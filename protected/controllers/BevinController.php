<?php
//set_time_limit(3600);
class BevinController extends Controller
{
	/**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'goods'=>array(
                'class'=>'CWebServiceAction',
                'classMap'=>array(
                    'User'=>'User'
                )
            ),
        );
    }

    /**
     * @return User[]  // <= return类型为User和object，输出结果会有差别
     * @soap
     */
    public function getUser()
    {
        $user = User::model()->findAll();
        return $user;
    }
    
	public function actionMakePinyin()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('pinyin=""');
		$criteria->limit = '1000';
		
		$criteria->select = "id, name, pinyin, city_id, map_x, map_y";
		$locations = Location::model()->findAll($criteria);
		$i = 0;
		foreach($locations as $l) {
			$l->save();
			$i++;
		}
		echo $i;
	}
	
	public function actionT()
	{
		exit;
		$session = app()->session;
		$session->setSessionID('d9eaf61fc5cd165d67ac878b0403d9a3');
		print_r($session->useCustomStorage);
		echo $session->readSession('d9eaf61fc5cd165d67ac878b0403d9a3');
		echo $session->get('email');
		exit;
		$a = CdcBetaTools::curl("http://maps.google.com/maps/api/geocode/json?address=%E6%B5%8E%E5%8D%97%E6%95%B0%E7%A0%81%E6%B8%AF&sensor=false&region=cn");
		echo $a;
	}
	
	public function actionTest()
	{
		$array = MiaoshaResult::getSuccessUserTelphone();
		print_r($array);
	}
}