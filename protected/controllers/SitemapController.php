<?php

class SitemapController extends Controller
{
    public function init()
    {
        header('Content-type: text/xml; charset=' . app()->charset);
    }
    public function filters()
    {
        return array(
            array(
                'COutputCache',
                'duration' => 3600*24,
            ),
        );
    }
    
    /**
     * 商家sitemap
     */
    public function actionShops()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, shop_name';
	    $criteria->order = 't.id DESC';
	    $criteria->limit = 2000;
	    $criteria->addColumnCondition(array('t.state' => STATE_ENABLED));
	    $data = Shop::model()->findAll($criteria);
	    $this->renderPartial('sitemap', array('data'=>$data));
    }
    
    /**
     * 位置sitemap
     */
    public function actionLocations()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, name';
	    $criteria->order = 't.id DESC';
	    $criteria->limit = 2000;
	    $criteria->addColumnCondition(array('t.state' => STATE_ENABLED));
	    $criteria->addCondition('food_nums > 0 OR cake_nums > 0');
	    $data = Location::model()->findAll($criteria);
	    $this->renderPartial('sitemap', array('data'=>$data));
    }
    
    public function actionTuan()
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id';
	    $criteria->order = 't.id DESC';
	    $criteria->limit = 500;
	    $data = Tuannav::model()->findAll($criteria);
	    $this->renderPartial('sitemap', array('data'=>$data));
    }
}