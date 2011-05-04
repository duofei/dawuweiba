<?php
class LatestTendency extends WmCornerBox
{
	public $list;
	
    public function init()
    {
    	$this->htmlOptions = array('class'=>'index-sidebar-ad corner ma-t5px corner-gray');
        parent::init();
    }
	
    public function renderContent()
    {
    	$list = UserAction::getContentList();
	    $this->render('latestTendency', array(
	    	'list'=>$list
	   	));
    }
}