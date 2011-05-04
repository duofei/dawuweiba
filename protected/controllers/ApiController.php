<?php

class ApiController extends Controller
{
	public function init()
	{
		parent::init();
		app()->errorHandler->errorAction = 'api/error';
	}
	
	public function actionIndex()
	{
	    $args = $_GET;
	    $method = strip_tags(trim($args['method']));
	    $apikey = strip_tags(trim($args['apikey']));
	    $format = ApiBase::checkFormat($args['format']);
	    
	    try {
	        ApiBase::checkApiKey($apikey);
	    } catch (CException $e) {
	        $data = array('errorCode'=>$e->getCode(), 'errorMessage'=>$e->getMessage());
	        ApiBase::renderData($data, $format);
	        exit(0);
	    }
	    
	    try {
            list($class, $method) = ApiBase::checkMethod($method);
	    } catch (CException $e) {
	        $data = array('errorCode'=>$e->getCode(), 'errorMessage'=>$e->getMessage());
	        ApiBase::renderData($data, $format);
	        exit(0);
	    }
	    
		try {
	    	$object = new $class;
	    	$data = $object->$method($args, $format);
	        ApiBase::renderData($data, $format);
	    }  catch (CException $e) {
	        $data = array('errorCode'=>$e->getCode(), 'errorMessage'=>$e->getMessage());
	        ApiBase::renderData($data, $format);
	        exit(0);
	    }
	}
	
	public function actionError()
	{
		$error = app()->errorHandler->error;
	    if (!$error) exit;
	    $errno = $error['code'];
	    if($errno=='400') {
	    	$error['errorCode'] = 400;
	    	$error['message'] = '参数错误';
	    }
		if($errno=='404') {
	    	$error['errorCode'] = 404;
	    	$error['message'] = '不存在的操作';
	    }
	    
	    $this->renderPartial('error', array('error'=>$error));
	}
}

