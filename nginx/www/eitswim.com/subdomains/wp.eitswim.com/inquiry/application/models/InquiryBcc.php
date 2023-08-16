<?php

/**
 * This is the model class for table "{{inquiry_bcc}}".
 *
 * The followings are the available columns in table '{{inquiry_bcc}}':
 * @property integer bcc_id
 * @property integer bcc_mail_id
 * @property string bcc_name
 * @property string bcc_address
 * @property string  create_date
 * @property string  update_date
 * @property integer update_user_id
 * @property integer delete_flag

 */
class InquiryBcc extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{inquiry_bcc}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bcc_id,
						 bcc_mail_id,
						 bcc_name,
						 bcc_address',
				'safe'),
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
			//'photos'=>array(self::HAS_MANY, 'Photo', 'album_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Album the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * beforeSave function
	 * @return type
	 */
	public function beforeSave()
	{
		if($this->isNewRecord) {
			$this->create_date=new CDbExpression('NOW()');
		} else {
			$this->update_date=new CDbExpression('NOW()');
		}
		$this->update_user_id = app()->user->id;

		return parent::beforeSave();
	}

	/*
	 * get Name
	 */
	public function getName($id) {
		if(empty($id)) return '';
		$model = $this->findByPk($id, '', array('delete_flag'=>'0'));
		if(!$model) return '';
		return $model->city_name;
	}


}
