<?php

class StaticController extends Controller
{
	public function actions()
	{
		return array(
			'pages' => array(
			    'class'=>'CViewAction',
			),
		);
	}
	
	
}