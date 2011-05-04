<?php

class CDIpBehavior extends CActiveRecordBehavior
{
	public $createAttribute = 'create_ip';
	
	public $updateAttribute = 'update_ip';
	
	public $setUpdateOnCreate = false;
	
	
	/**
	* Responds to {@link CModel::onBeforeSave} event.
	* Sets the values of the creation or modified attributes as configured
	* 
	* @param CModelEvent event parameter
	*/
	public function beforeSave($event) {
		if ($this->getOwner()->getIsNewRecord() && ($this->createAttribute !== null)) {
			$this->getOwner()->{$this->createAttribute} = CdcBetaTools::getClientIp();
		}
		if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateAttribute !== null)) {
			$this->getOwner()->{$this->updateAttribute} = CdcBetaTools::getClientIp();
		}
	}

}