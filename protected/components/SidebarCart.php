<?php
class SidebarCart extends WmCornerBox
{
    public $cart;
    
    public function init()
    {
        $this->title = '我的购物车';
        $this->htmlOptions = array('class'=>'corner-gray-title live-cart relative');
        parent::init();
    }
    
    public function renderContent()
    {
    	if(!$this->cart) $this->cart = Cart::getGoodsList();
	    $this->render('sidebarCart');
    }
}