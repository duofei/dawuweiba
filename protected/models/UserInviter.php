<?php

/**
 * This is the model class for table "{{UserInviter}}".
 *
 * The followings are the available columns in table '{{UserInviter}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $invitee_id
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $integral
 * @property integer $state
 */
class UserInviter extends CActiveRecord
{
	public static $states = array(
		STATE_DISABLED => '未产生购买',
		STATE_ENABLED => '邀请成功',
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UserInviter the static model class
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
		return '{{UserInviter}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, invitee_id', 'required'),
			array('user_id, invitee_id, create_time, update_time, integral, state', 'numerical', 'integerOnly'=>true),
			array('create_ip, update_ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, invitee_id, create_time, create_ip, update_time, update_ip, integral, state', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'invitee' => array(self::BELONGS_TO, 'User', 'invitee_id')
		);
	}

	public function behaviors()
	{
	    return array(
	        'CTimestampBehavior' => array(
	            'class' => 'zii.behaviors.CTimestampBehavior',
	        ),
	        'CDIpBehavior' => array(
	            'class' => 'application.behaviors.CDIpBehavior',
	        )
	    );
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'user_id' => 'User',
			'invitee_id' => 'Invitee',
			'create_time' => 'Create Time',
			'create_ip' => 'Create Ip',
			'update_time' => 'Update Time',
			'update_ip' => 'Update Ip',
			'integral' => 'Integral',
			'state' => 'State',
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

		$criteria->compare('user_id',$this->user_id,true);

		$criteria->compare('invitee_id',$this->invitee_id,true);

		$criteria->compare('create_time',$this->create_time,true);

		$criteria->compare('create_ip',$this->create_ip,true);

		$criteria->compare('update_time',$this->update_time,true);

		$criteria->compare('update_ip',$this->update_ip,true);

		$criteria->compare('integral',$this->integral,true);

		$criteria->compare('state',$this->state);

		return new CActiveDataProvider('UserInviter', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 通过hcode返回邀请成功后增加的白吃点数
	 */
	public static function getInviterIntegral($hcode = null)
	{
		$integral = param('defaultInviterBcIntegral');
		if($hcode) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('hcode'=>$hcode));
			$hidecode = UserInviterHideCode::model()->find($criteria);
			if($hidecode) {
				$integral = $hidecode->integral;
				$hidecode->use_nums++;
				$hidecode->save();
			}
		}
		return $integral;
	}
	
	/**
	 * 邀请成功后的相关处理
	 */
	public static function inviteSuccess($invitee_id)
	{
		$invitee_id = intval($invitee_id);
		if($invitee_id) {
			$criteria = new CDbCriteria();
			$criteria->addColumnCondition(array('invitee_id'=>$invitee_id, 'state'=>STATE_DISABLED));
			$self = self::model()->find($criteria);
			if($self) {
				// 增加白吃点操作
				$integral = $self->integral;
				// 邀请者加分
				$bclog1 = new UserBcintegralLog();
				$bclog1->user_id = $self->user_id;
				$bclog1->integral = $integral;
				$bclog1->source = UserBcintegralLog::SOURCE_INVITER;
				
				// 被邀请者加分
				$bclog2 = new UserBcintegralLog();
				$bclog2->user_id = $self->invitee_id;
				$bclog2->integral = $integral;
				$bclog2->source = UserBcintegralLog::SOURCE_INVITEE;
				
				if($bclog1->save() && $bclog2->save()) {
					$self->state = STATE_ENABLED;
					$self->save();
				}
			}
		}
	}
	
	public function getStateText()
	{
		return self::$states[$this->state];
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getCreateDateTimeText()
	{
		return date(param('formatDateTime'), $this->create_time);
	}
	
	/**
	 * 格式化创建时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortCreateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->create_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i:s
	 */
	public function getUpdateDateTimeText()
	{
		return date(param('formatDateTime'), $this->update_time);
	}
	
	/**
	 * 格式化更新时间
	 * 输出时间格式：Y-m-d H:i
	 */
	public function getShortUpdateDateTimeText()
	{
		return date(param('formatShortDateTime'), $this->update_time);
	}

}