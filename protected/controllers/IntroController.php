<?php

class IntroController extends Controller
{
	public function actionBaichidian()
	{
		$this->layout = 'blank';
		$this->render('baichidian');
	}
}