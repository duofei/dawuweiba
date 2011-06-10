<?php
class CdcSwfUpload extends CWidget
{
    public $id;
    public $cssFile;
    public $options;
    protected $assets;
    
    public function init()
    {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'resource';
        $this->assets = Yii::app()->getAssetManager()->publish($file, true) . '/';
        if (empty($this->id)) $this->id = 'selectFilesButton' . mt_rand();
    }
    
    public function run()
    {
        $this->registerClientScript();
        $this->render('swfshow');
    }
    
    protected function registerClientScript()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $swfUploadJsFile = $this->assets . 'SWFUpload/swfupload.js';
        $cs->registerScriptFile($swfUploadJsFile, CClientScript::POS_END);
        $handleJsFile = $this->assets . 'handler.js';
        $cs->registerScriptFile($handleJsFile, CClientScript::POS_END);
        $cssFile = $this->cssFile ? $this->cssFile : $this->assets . 'swfupload.css';
        $cs->registerCssFile($cssFile, 'screen');
    }
}