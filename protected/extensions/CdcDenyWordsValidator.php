<?php
class CdcDenyWordsValidator extends CValidator
{
    public $allowEmpty = false;
    
    protected function validateAttribute($object, $attribute)
    {
    	$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;
		if(!$this->validateValue($value))
		{
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} 中包含不允许的关键词.');
			$this->addError($object,$attribute,$message);
		}
    }
    
	public function validateValue($value)
	{
	    if (!is_string($value)) return false;
	    
	    static $words = null;
	    $words = (null === $words) ? require(app()->basePath . '/config/deny_words.php') : $words;
        foreach ($words as $v) {
            $pos = strpos($value, $v);
            if (false !== $pos) return false;
        }
        return true;
	}
}