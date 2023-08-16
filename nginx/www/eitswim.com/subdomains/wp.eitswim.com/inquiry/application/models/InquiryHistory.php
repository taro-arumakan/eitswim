<?php

/**
 * This is the model class for table "{{inquiry_history}}".
 *
 * The followings are the available columns in table '{{inquiry_history}}':
 * @property integer history_id
 * @property string  history_code
 * @property string  history_title
 * @property string  history_name
 * @property string  history_mail
 * @property string  history_answer
 * @property string  history_response
 * @property string  create_date
 * @property string  update_date
 * @property integer update_user_id
 * @property integer delete_flag

 */
class InquiryHistory extends CActiveRecord
{
    public $subject;
    public $category;
    public $message;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{inquiry_history}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('history_id,
						 history_code,
						 subject,
						 category,
						 message,
						 history_title,
						 history_name,
						 history_mail,
						 history_response,
						 history_answer',
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

    function searchById($id) {
        $query= "SELECT T1.*,
(
SELECT detail_answer FROM tbl_inquiry_history_detail WHERE detail_history_id = T1.history_id AND detail_name = 'Subject'
) AS subject,
(
SELECT detail_answer FROM tbl_inquiry_history_detail WHERE detail_history_id = T1.history_id AND detail_name = 'Category'
) AS category,
(
SELECT detail_answer FROM tbl_inquiry_history_detail WHERE detail_history_id = T1.history_id AND detail_name = 'Message'
) AS message
				FROM tbl_inquiry_history T1
				LEFT JOIN tbl_inquiry_history_detail D1
				ON D1.detail_history_id = T1.history_id
				WHERE history_id = {$id}
				GROUP BY history_id
				ORDER BY
				T1.create_date
		";
        $data = InquiryHistory::model()->findBySql($query);
        return $data;
    }

	/*
	 * get Name
	 */
	public function getResponse($id) {
		if(empty($id)) return '';
		$model = $this->findByPk($id, '', array('delete_flag'=>'0'));
		if(!$model) return '';
		return $model->history_response;
	}

	/*
	 * get Name
	 */
	public function getResponseById($id) {
		if(empty($id)) return '';
		$model = $this->findByPk($id, '', array('delete_flag'=>'0'));
		if(!$model) return '';
		return $this->getResponseName($model->history_response);
	}

    public function getResponseName($responseId) {
		if($responseId == 0) return '未対応';
	    if($responseId == 1) return '対応済';
	    if($responseId == 2) return '保留';
    }

    public function getResponseList() {
		$response = array();
		$response[0] = '未対応';
	    $response[1] = '対応済';
	    $response[2] = '保留';
    }
}
