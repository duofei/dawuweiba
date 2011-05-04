<?php

/**
 * This is the model class for table "{{SendMail}}".
 *
 * The followings are the available columns in table '{{SendMail}}':
 * @property string $id
 * @property string $subject
 * @property string $body
 * @property string $mailto
 * @property integer $state
 * @property string $create_time
 * @property string $update_time
 * @property integer $priority
 */
class SendMail extends CActiveRecord
{
    const ERRNO_SEND = 255;
    const ADDRESS_SEPARATE = ','; 
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return SendMail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{SendMail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('subject, body, mailto', 'required'),
			array('state, priority', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>255),
			array('create_time, update_time', 'length', 'max'=>10),
			array('body, mailto', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, subject, body, mailto, state, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'subject' => 'Subject',
			'body' => 'Body',
			'mailto' => 'Mailto',
			'state' => 'State',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);

		$criteria->compare('mailfrom',$this->mailfrom,true);

		$criteria->compare('fromname',$this->fromname,true);

		$criteria->compare('subject',$this->subject,true);

		$criteria->compare('body',$this->body,true);

		$criteria->compare('mailto',$this->mailto,true);

		$criteria->compare('state',$this->state);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider('SendMail', array(
			'criteria'=>$criteria,
		));
	}

    public static function mailSend($subject, $body, $mailto, $attachments = null)
    {
        if (empty($subject) || empty($body) || empty($mailto)) return false;
        
        $mail = app()->mailer->getMail();
        $mail->Subject = $subject;
        $mail->AltBody = $subject;
        $mail->Body = $body;
        $mail->WordWrap = 80;
        $mail->MsgHTML($body);
        $mail->ClearAddresses();
        $mail->ClearAttachments();
        $address = explode(self::ADDRESS_SEPARATE, $mailto);
        if (count($address) == 1)
            $mail->AddAddress($mailto);
        else
            foreach ($address as $v) $mail->AddAddress($v);

        if (is_string($attachments)) $mail->AddAttachment($attachments);
        if (is_array($attachments)) {
            foreach ($attachments as $v) $mail->AddAttachment($v);
        }
        
        $mail->IsHTML(true);
        return $mail->send();
    }
    
    public function send()
    {
        self::sendMail($this->subject, $this->body, $this->mailto);
    }
    
    public static function addMailQueue($subject, $body, $mailto, $priority = 0)
    {
        $mail = new self();
        $mail->subject = $subject;
        $mail->body = $body;
        $mail->mailto = $mailto;
        return $mail->save();
    }
    
    protected function beforeSave()
    {
        parent::beforeSave();
        
        if ($this->isNewRecord) {
            $this->state = STATE_DISABLED;
        }
        return true;
    }
    
    public function behaviors()
    {
        return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	        )
	    );
    }
    
}