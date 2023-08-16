<?php
//For Validation
use Respect\Validation\Validator as V;

/**
 * This is the model class for table "{{inquiry}}".
 *
 * The followings are the available columns in table '{{inquiry}}':
 * @property integer inquiry_id
 * @property integer inquiry_rank
 * @property string  inquiry_code
 * @property string  inquiry_title
 * @property string  inquiry_script
 * @property integer inquiry_name_rank
 * @property integer inquiry_mail_rank
 * @property string  create_date
 * @property string  update_date
 * @property integer update_user_id
 * @property integer delete_flag
 * @property string  seo_title
 * @property string  seo_description
 * @property string  seo_keywords
 * @property string  inquiry_notes

 */
class Inquiry extends CActiveRecord
{

	public $answer = array();
	public $error = array();
	public $kind = array();
    public $categories = array();
    public $category;
	public $history_name;
	public $history_mail;
	public $history_from;
	public $history_to;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{inquiry}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inquiry_id,
						 answer,
						 error,
						 kind,
						 categories,
						 category,
						 history_name,
						 history_mail,
						 history_from,
						 history_to,
						 inquiry_rank,
						 inquiry_code,
						 inquiry_title,
						 seo_title,
						 seo_description,
						 seo_keywords,
						 inquiry_notes,
						 inquiry_name_rank,
						 inquiry_mail_rank,
						 inquiry_script',
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

	/**
	 * バリデーションチェック
	 * $model->error[question_rank][] にエラーメッセージを格納
	 * @param $model
	 *
	 * @return bool
	 */
	public function checkValidation($model) {
		$status = true;
		$answers = $model->answer;
		$questions = InquiryQuestion::model()->findAll(
			array("condition" => "question_inquiry_id = {$model->inquiry_id} AND delete_flag = 0",
						"order" => "question_rank"
			));
		//同意
		if(empty($answers[0])) {
			$status = false;
			$model->error[0] = '個人情報の取扱いについてをご確認ください';
		}
		foreach($questions as $index => $question) {
			if(!empty($question->question_require)) {
				//必須
				if(empty($answers[$question->question_rank])) {
					$status = false;
					$model->error[$question->question_rank] = '必須項目を入力して下さい';
				}
			}
			if(!empty($question->question_validation)) {
				if(isset($answers[$question->question_rank])) {
					$value = $answers[$question->question_rank];
					switch ($question->question_validation) {
						case 'カタカナ':
							/**
							S	「半角」スペースを「全角」に変換
							K	「半角カタカナ」を「全角カタカナ」に変換
							C	「全角ひらがな」を「全角カタカナ」に変換
							V	濁点付きの文字を一文字に変換。K、Hと共に使用
							 */
							$option = 'SKCV';
							$value2 = mb_convert_kana($value , $option, 'UTF-8');
							$model->answer[$question->question_rank] = $value2;

							if(empty($value2)) break;
							if ( ! preg_match( "/^[ァ-ヶー 　]+$/u", $value2 ) ) {
								//カタカナ以外
								$status = false;
								$model->error[$question->question_rank] = '全角カタカナで入力して下さい';
							}
							break;
						case '電話':
							/**
							a	「全角」英数字を「半角」に変換
							 */
							$option = 'a';
							$value2 = mb_convert_kana($value , $option, 'UTF-8');
							$model->answer[$question->question_rank] = $value2;
							if(empty($value2)) break;
							if ( ! v::string()->digit( '-' )->validate( $value2 ) ) {
								$status = false;
								$model->error[$question->question_rank] = '半角数字または記号で入力して下さい';
							}
							break;
						case 'メール':
							/**
							a	「全角」英数字を「半角」に変換
							 */
							$option = 'a';
							$value2 = mb_convert_kana($value , $option, 'UTF-8');
							$model->answer[$question->question_rank] = $value2;
							if(empty($value2)) break;
							if ( ! v::email()->validate( $value2 ) ) {
								//形式エラー
								$status = false;
								$model->error[$question->question_rank] = 'メールアドレスの形式が不正です';
							}
							break;
						case '確認':
							/**
							 * 前の質問の回答と同じかチェックする
							 */
							if(!empty($model->answer[$question->question_rank-1])) {
								$prev_answer = $model->answer[$question->question_rank-1];
								if($value!=$prev_answer) {
									//確認エラー
									$status = false;
									$prev_question = $questions[$index-1];
									$model->error[$question->question_rank] = 'ご入力いただいたメールアドレスが一致しません。';
								}
							}
							break;
						case '数字':
							/**
							a	「全角」英数字を「半角」に変換
							 */
							$option = 'a';
							$value2 = mb_convert_kana($value , $option, 'UTF-8');
							$model->answer[$question->question_rank] = $value2;
							if(empty($value2)) break;
							if ( ! v::string()->digit()->validate( $value2 ) ) {
								$status = false;
								$model->error[$question->question_rank] = '半角数字で入力して下さい';
							}
							break;
					}
				}
			}
		}
		return $status;
	}


	/**
	 * メールタイトルの文字列を置き換える
	 * @param $conf
	 * @param $model
	 * @param $questions
	 *
	 * @return mixed
	 */
	function mail_replace($conf, $model, $questions) {
		//mail_reply_subject
		$title = $conf->mail_reply_subject;
		foreach($questions as $question) {
			$title = str_replace('['.$question->question_name.']',$model->answer[$question->question_rank],$title);
		}
		$conf->mail_reply_subject = $title;

		//mail_admin_subject
		$title = $conf->mail_admin_subject;
		foreach($questions as $question) {
			$title = str_replace('['.$question->question_name.']',$model->answer[$question->question_rank],$title);
		}
		$conf->mail_admin_subject = $title;

		return $conf;
	}

	/**
	 * メール送信処理・履歴保存
	 * @param $model
	 *
	 * @return bool
	 */
	public function sendMail($model) {

		$status = true;

		if(empty($model->answer[$model->inquiry_name_rank])) return false;
		if(empty($model->answer[$model->inquiry_mail_rank])) return false;

		/* 履歴保存 */
		if( ! $this->saveHistory($model)) {
			return false;
		}

		require_once( dirname(__FILE__).'/../vendors/phpmailer/phpmailer/PHPMailerAutoload.php');

		//言語設定、内部エンコーディングを指定する
		mb_language("japanese");
		mb_internal_encoding("UTF-8");

		// タイムゾーン設定
		date_default_timezone_set('Asia/Tokyo');

		$conf = InquiryMail::model()->findByAttributes(
			array('mail_inquiry_id' => $model->inquiry_id, 'delete_flag' => 0));

		$questions = InquiryQuestion::model()->findAll(
			array("condition" => "question_inquiry_id = {$model->inquiry_id} AND delete_flag = 0",
						"order" => "question_rank"
			));

		//mail_reply_subject
		//mail_admin_subject
		$conf = $this->mail_replace($conf, $model, $questions);

		$q_and_a = '';
		$replace_name = 'お名前';
		foreach($questions as $question) {
			if($question->question_type=='確認') continue;
			if($question->question_rank==$model->inquiry_name_rank) {
				$replace_name = $question->question_name;
			}
			$a = empty($model->answer[$question->question_rank])?'':$model->answer[$question->question_rank];
			if(is_array($a)) {
				$strA = implode('、', $a);
				$q_and_a .= $question->question_name.':'."\n".$strA."\n\n";
			} else {
				if(!empty($a)) {
					$q_and_a .= $question->question_name.':'."\n".$a."\n\n";
				}
			}
		}

		/* 自動返信メール */
		$bcc = InquiryBcc::model()->findAllByAttributes(
			array('bcc_mail_id' => $conf->mail_id, 'delete_flag' => 0));
		
		$userMail = new PHPMailer();

		$mail_from_name = $conf->mail_from_name;
		$mail_from_address = $conf->mail_from_address;
		$mail_header = $conf->mail_header;
		$mail_footer = $conf->mail_footer;

		$subs = InquiryMailSub::model()->findAllByAttributes(
			array('sub_inquiry_id' => $model->inquiry_id, 'delete_flag' => 0));
		foreach($subs as $sub) {
			if(!empty($sub['sub_question_no']) && !empty($sub['sub_answer_no'])) {
				$question_no = $sub['sub_question_no'];
				$answer_no = $sub['sub_answer_no'];
				$answer = InquiryAnswer::model()->getNameByModel($model, $question_no, $answer_no);
				if($model->answer[$question_no]==$answer) {
					$mail_from_name = $sub['sub_from_name'];
					$mail_from_address = $sub['sub_from_address'];
					$mail_header = $sub['sub_header'];
					$mail_footer = $sub['sub_footer'];
				}
			}
		}

		$userMail->CharSet = "UTF-8";    // 文字セット(デフォルトは'ISO-8859-1')
		$userMail->Encoding = "base64";  // エンコーディング(デフォルトは'8bit')
		$userMail->From     = $mail_from_address;  // Fromのメールアドレス
		$userMail->FromName = $mail_from_name;

		$message = str_replace('['.$replace_name.']',$model->answer[$model->inquiry_name_rank],$mail_header);

		//ヘッダーの[質問]を[回答]で置換え
		foreach($questions as $question) {
			$message = str_replace('['.$question->question_name.']',$model->answer[$question->question_rank],$message);
		}

		$message .= $q_and_a;
		//ヘッダーの[質問]を[回答]で置換え
		foreach($questions as $question) {
			$mail_footer = str_replace('['.$question->question_name.']',$model->answer[$question->question_rank],$mail_footer);
		}
		$message .= $mail_footer;

		$to_address = $model->answer[$model->inquiry_mail_rank];
		$userMail->addAddress($to_address);
		foreach($bcc as $item) {
			if(!empty($item->bcc_name)) {
				$userMail->addBCC($item->bcc_address, $item->bcc_name);
			} else {
				$userMail->addBCC($item->bcc_address);
			}
		}
		$userMail->Subject = $conf->mail_reply_subject;
		$userMail->Body = $message;
		//$userMail->ReturnPath($conf->config_reply);

		if (!$userMail->send()){
			$status = false;
		}

		/* 管理者メール送信 */
		$admin_to = InquiryTo::model()->findAllByAttributes(
			array('to_mail_id' => $conf->mail_id, 'delete_flag' => 0));

		$adminMail = new PHPMailer();
		$adminMail->CharSet = "UTF-8";    // 文字セット(デフォルトは'ISO-8859-1')
		$adminMail->Encoding = "base64";  // エンコーディング(デフォルトは'8bit')
		//2017.03.22 Mod 管理者宛メールのFromをユーザーメールアドレスに変更
		//$adminMail->From     = $mail_from_address;  // Fromのメールアドレス
		$user_address = $model->answer[$model->inquiry_mail_rank];
		//$adminMail->From     = $user_address;  // Fromのメールアドレス
		//$adminMail->FromName = $mail_from_name;
		$adminMail->From     = $mail_from_address;  // Fromのメールアドレス
		$adminMail->FromName = $mail_from_name;
		$adminMail->addReplyTo($user_address);

		$message = $q_and_a;
		$message .= "\n日時：" . date('Y/m/d H:i:s');

		$default_send = true;
		//回答による送信先が指定されているかをチェックする
		foreach($admin_to as $item) {
			if(!empty($item->to_question_no) && !empty($item->to_answer_no)) {
				$answer = InquiryAnswer::model()->getNameByModel($model, $item->to_question_no, $item->to_answer_no);
				if($model->answer[$item->to_question_no]==$answer) {
					$default_send = false;
					if(!empty($item->to_name)) {
						$adminMail->addAddress($item->to_address, $item->to_name);
					} else {
						$adminMail->addAddress($item->to_address);
					}
				}
			}
		}
		if($default_send) {
			//デフォルトの送信先
			foreach($admin_to as $item) {
				if(empty($item->to_question_no) && empty($item->to_answer_no)) {
					if(!empty($item->to_name)) {
						$adminMail->addAddress($item->to_address, $item->to_name);
					} else {
						$adminMail->addAddress($item->to_address);
					}
				}
			}
		}
		foreach($bcc as $item) {
			if(!empty($item->bcc_name)) {
				$adminMail->addBCC($item->bcc_address, $item->bcc_name);
			} else {
				$adminMail->addBCC($item->bcc_address);
			}
		}
		$adminMail->Subject = $conf->mail_admin_subject;
		$adminMail->Body = $message;
		//$adminMail->ReturnPath($conf->config_reply);

		if (!$adminMail->send()){
			$status = false;
		}

		return $status;
	}

	/**
	 * @param $model
	 *
	 * @return bool
	 */
	public function saveHistory($model) {
		$email = $model->answer[$model->inquiry_mail_rank];
		$name = $model->answer[$model->inquiry_name_rank];

		$questions = InquiryQuestion::model()->findAll(
			array("condition" => "question_inquiry_id = {$model->inquiry_id} AND delete_flag = 0",
						"order" => "question_rank"
			));
		$q_and_a = '';
		foreach($questions as $question) {
			$a = empty($model->answer[$question->question_rank])?'':$model->answer[$question->question_rank];
			if(is_array($a)) {
				$strA = implode('、', $a);
				$q_and_a .= $question->question_name.':'."\n".$strA."\n\n";
			} else {
				$q_and_a .= $question->question_name.':'."\n".$a."\n";
			}
		}

		$history = new InquiryHistory;
		$history->history_code = $model->inquiry_code;
		$history->history_title = $model->inquiry_title;
		$history->history_name = $name;
		$history->history_mail = $email;
		$history->history_answer = $q_and_a;
		if($history->save()) {
			foreach($questions as $question) {
				$detail = new InquiryHistoryDetail;
				$detail->detail_history_id = $history->history_id;
				$detail->detail_rank = $question->question_rank;
				$detail->detail_name = $question->question_name;
				$detail->detail_answer = $model->answer[$question->question_rank];
				$detail->save();
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $model
	 *
	 * @return string
	 */
	function searchSql($model) {
		$where = 'WHERE T1.delete_flag = 0 ';
		$sep = ' AND ';
		//history_name
		if(!empty($model->history_name)) {
			$where .= $sep;
			$where .= " history_name LIKE '%" . $model->history_name . "%'";
		}
		//history_mail
		if(!empty($model->history_mail)) {
			$where .= $sep;
			$where .= " history_mail LIKE '%" . $model->history_mail . "%'";
		}
		//history_from
		if(!empty($model->history_from)) {
			$where .= $sep;
			$where .= " T1.create_date >='" . str_replace('/','-',$model->history_from) . " 00:00:00'";
		}
		//history_to
		if(!empty($model->history_to)) {
			$where .= $sep;
			$where .= " T1.create_date <='" . str_replace('/','-',$model->history_to) . " 23:59:99'";
		}
		//kind
		if(!empty($model->kind)) {
			$where .= $sep;
			$sub = '';
			if(is_array($model->kind)) {
				$kind = implode("','",$model->kind);
				$where .= "history_code IN ( '{$kind}' )";
			}
		}
        //categories
        if(!empty($model->categories)) {
            $sub = $sep . "(detail_name = 'Category'";
            if(is_array($model->categories)) {
                $categories = implode("','",$model->categories);
                $sub .= " AND detail_answer IN ( '{$categories}' ) )";
            } else {
                $sub = '';
            }
            $where .= $sub;
        }
        //category
        if(!empty($model->category)) {
            $sub = $sep . "(detail_name = 'Category'";
            $sub .= " AND detail_answer = '{$model->category}' )";
            $where .= $sub;
        }

		return $where;
	}


	/**
	 * @param $model
	 *
	 * @return int
	 */
	function countByModel($model) {
		$where = $this->searchSql($model);
		$query= "SELECT T1.*
				FROM tbl_inquiry_history T1
				LEFT JOIN tbl_inquiry_history_detail D1
				ON D1.detail_history_id = T1.history_id
				{$where}
				GROUP BY history_id
				ORDER BY
				T1.create_date
		";

		//$data = $this->countBySql($query);
		$data = Yii::app()->db->createCommand($query)->queryAll();
		if(count($data)>0) return count($data);
		return 0;
	}

	/**
	 * @param $model
	 * @param int $page
	 * @param bool $all
	 *
	 * @return mixed
	 */
	function searchByModel($model, $page = 0, $all = false) {
		$max_count_one = param('max_count_one');
		$offset = $page * $max_count_one;
		if($all) {
			$limit = '';
		} else {
			$limit = "LIMIT {$offset},{$max_count_one}";
		}
		$where = $this->searchSql($model);
		$query= "SELECT T1.*,
(
SELECT detail_answer FROM tbl_inquiry_history_detail WHERE detail_history_id = T1.history_id AND detail_name = 'Subject'
) AS subject,
(
SELECT detail_answer FROM tbl_inquiry_history_detail WHERE detail_history_id = T1.history_id AND detail_name = 'Category'
) AS category
				FROM tbl_inquiry_history T1
				LEFT JOIN tbl_inquiry_history_detail D1
				ON D1.detail_history_id = T1.history_id
				{$where}
				GROUP BY history_id
				ORDER BY
				T1.create_date
				{$limit}
		";
		$data = InquiryHistory::model()->findAllBySql($query);
		return $data;
	}

	/**
	 * @param $id
	 *
	 * @return array|static
	 */
	function getDetail($id) {
		if(empty($id)) return array();
		$query= "SELECT *
				FROM tbl_inquiry_history
				WHERE
				history_id = {$id}
				and delete_flag = 0
		";
		$data = $this->findBySql($query);
		return $data;
	}

	/**
	 * @param $model
	 *
	 * @return array
	 */
	function getCsvList($model) {
		$result = array();
		$header = array('お問い合わせID','種別','お名前','メールアドレス','日時');
		$data = $this->searchByModel($model, 0, true);
		if(empty($data)) return $result;
		$history = $data[0];
		$details = InquiryHistoryDetail::model()->findAll(array(
																											"condition" => "delete_flag = 0 AND detail_history_id = {$history->history_id}",
																											"order" => "detail_rank"
																										));
		foreach($details as $detail) {
			$header[] = $detail->detail_name;
		}
		$result[] = $header;
		foreach($data as $item) {
			$line = array();
			$line[] = $item->history_id;
			$line[] = $item->history_title;
			$line[] = $item->history_name;
			$line[] = $item->history_mail;
			$line[] = $item->create_date;
			//$line[] = preg_replace("/\r\n|\r|\n/", "\r\n", $item->history_answer);
			$details = InquiryHistoryDetail::model()->findAll(array(
																													"condition" => "delete_flag = 0 AND detail_history_id = {$item->history_id}",
																													"order" => "detail_rank"
																												));
			foreach($details as $detail) {
				$line[] = preg_replace("/\r\n|\r|\n/", "\r\n", $detail->detail_answer);
			}
			$result[] = $line;
		}
		return $result;
	}

	function saveData($model) {
		if(empty($_POST['Inquiry'])) {
			return false;
		}
		if(empty($_POST['InquiryMail'])) {
			return false;
		}
		if(empty($_POST['InquiryTo'])) {
			return false;
		}
		if(empty($_POST['InquiryQuestion'])) {
			return false;
		}

		$id = '';
		if(empty($_POST['Inquiry']['inquiry_id'])) {
			$id = $_POST['Inquiry']['inquiry_id'];
		}
		if(!empty($model->inquiry_id)) {
			$id = $model->inquiry_id;
		}

		if(empty($id)) {
			$inquiry = new Inquiry;
			$inquiryMail = new InquiryMail;
		} else {
			$inquiry = Inquiry::model()->findByPk($id);
			$inquiryMail = InquiryMail::model()->findByAttributes(
				array('mail_inquiry_id' => $id, 'delete_flag' => 0));
			//TO削除
			InquiryTo::model()->deleteAllByAttributes(array('to_mail_id' => $inquiryMail->mail_id));
			//BCC削除
			InquiryBcc::model()->deleteAllByAttributes(array('bcc_mail_id' => $inquiryMail->mail_id));
			$inquiryQuestion = InquiryQuestion::model()->findAllByAttributes(
				array('question_inquiry_id' => $id, 'delete_flag' => 0));
			foreach($inquiryQuestion as $question) {
				//ANSWER削除
				InquiryAnswer::model()->deleteAllByAttributes(array('answer_question_id' => $question->question_id));
			}
			//QUESTION削除
			InquiryQuestion::model()->deleteAllByAttributes(array('question_inquiry_id' => $id));
		}
		$inquiry->attributes = $_POST['Inquiry'];
		if(empty($inquiry->inquiry_id)) {
			$inquiry->inquiry_id = null;
		}
		if($inquiry->save()) {
			$inquiryMail->attributes = $_POST['InquiryMail'];
			$inquiryMail->mail_inquiry_id = $inquiry->inquiry_id;
			if(empty($inquiryMail->mail_id)) {
				$inquiryMail->mail_id = null;
			}
			if($inquiryMail->save()) {
				//TO登録
				$tos = $_POST['InquiryTo'];
				foreach($tos as $item) {
					$to = new InquiryTo;
					$to->attributes = $item;
					if(!empty($to->to_address)) {
						$to->to_mail_id = $inquiryMail->mail_id;
						$to->save();
					}
				}
				if(!empty($_POST['InquiryBcc'])) {
					//BCC登録
					$bccs = $_POST['InquiryBcc'];
					foreach($bccs as $item) {
						$bcc = new InquiryBcc;
						$bcc->attributes = $item;
						if(!empty($bcc->bcc_address)) {
							$bcc->bcc_mail_id = $inquiryMail->mail_id;
							$bcc->save();
						}
					}
				}
			} else {
				return false;
			}
			//SUB登録
			if(!empty($_POST['InquiryMailSub'])) {
				$subs = $_POST['InquiryMailSub'];
				foreach($subs as $index => $item) {
					if(!empty($item['sub_id'])) {
						$id = $item['sub_id'];
						$sub = InquiryMailSub::model()->findByPk($id);
						if(!empty($sub)) {
							$sub->attributes = $item;
							$sub->save();
						}
					}
				}
			}

			//質問登録
			$questions = $_POST['InquiryQuestion'];
			foreach($questions as $index => $item) {
				$question = new InquiryQuestion;
				$question->attributes = $item;
				$question->question_inquiry_id = $inquiry->inquiry_id;
				$question->question_rank = $item['question_rank'];
				if(!empty($question->question_name)) {
					if($question->save()) {
						if(!empty($item['is_name'])) {
							$inquiry->inquiry_name_rank = $index;
							$inquiry->save();
						}
						if(!empty($item['is_mail'])) {
							$inquiry->inquiry_mail_rank = $index;
							$inquiry->save();
						}
						if($question->question_type=='Radio' || $question->question_type=='Checkbox') {
							if(!empty($_POST['InquiryAnswer'][$index])) {
								//回答登録
								$answers = $_POST['InquiryAnswer'][$index];
								foreach($answers as $item_answer) {
									$answer = new InquiryAnswer;
									$answer->attributes = $item_answer;
									if(!empty($answer->answer_name)) {
										$answer->answer_value = $answer->answer_name;
										$answer->answer_question_id = $question->question_id;
										$answer->save();
									}
								}
							}
						}
					}
				}
			}
		} else {
			return false;
		}
		
		return true;
		
	}

	/**
	 * 入力データチェック
	 * @return array
	 */
	function saveValidation() {
		$error = array();
		if(!empty($_POST['Inquiry'])) {
			//必須項目を入力して下さい
			//inquiry_rank
			if(empty($_POST['Inquiry']['inquiry_rank'])) {
				$error['Inquiry']['inquiry_rank'] = '必須項目を入力して下さい';
			}
			//inquiry_code
			if(empty($_POST['Inquiry']['inquiry_code'])) {
				$error['Inquiry']['inquiry_code'] = '必須項目を入力して下さい';
			}
			//inquiry_title
			if(empty($_POST['Inquiry']['inquiry_title'])) {
				$error['Inquiry']['inquiry_title'] = '必須項目を入力して下さい';
			}
		}
		if(!empty($_POST['InquiryMail'])) {
			//必須項目を入力して下さい
			//mail_admin_subject
			if(empty($_POST['InquiryMail']['mail_admin_subject'])) {
				$error['InquiryMail']['mail_admin_subject'] = '必須項目を入力して下さい';
			}
			//mail_reply_subject
			if(empty($_POST['InquiryMail']['mail_reply_subject'])) {
				$error['InquiryMail']['mail_reply_subject'] = '必須項目を入力して下さい';
			}
			//mail_from_address
			if(empty($_POST['InquiryMail']['mail_from_address'])) {
				$error['InquiryMail']['mail_from_address'] = '必須項目を入力して下さい';
			} else {
				if ( ! v::email()->validate( $_POST['InquiryMail']['mail_from_address'] ) ) {
					//形式エラー
					//mail_from_address
					$error['InquiryMail']['mail_from_address'] = 'メールアドレスの形式が不正です';
				}
			}
		}
		if(!empty($_POST['InquiryTo'])) {
			$is_first = true;
			foreach($_POST['InquiryTo'] as $index => $to) {
				//必須項目を入力して下さい
				//to_address
				if(empty($to['to_address'])) {
					if($is_first) {
						$error['InquiryTo']['to_address'][$index] = '必須項目を入力して下さい';
						$is_first = false;
					}
				} else {
					$is_first = false;
					if ( ! v::email()->validate($to['to_address']) ) {
						//形式エラー
						//to_address
						$error['InquiryTo']['to_address'][$index] = 'メールアドレスの形式が不正です';
					}
				}
			}
		}
		if(!empty($_POST['InquiryBcc'])) {
			foreach($_POST['InquiryBcc'] as $index => $bcc) {
				if(!empty($bcc['bcc_address'])) {
					if ( ! v::email()->validate($bcc['bcc_address']) ) {
						//形式エラー
						//bcc_address
						$error['InquiryBcc']['bcc_address'][$index] = 'メールアドレスの形式が不正です';
					}
				}
			}
		}
		$question_count = 0;
		if(!empty($_POST['InquiryQuestion'])) {
			foreach($_POST['InquiryQuestion'] as $index => $question) {
				if(!empty($question['question_name'])) {
					$question_count += 1;
					if($question['question_type']=='Radio' || $question['question_type']=='Checkbox') {
						if(empty($_POST['InquiryAnswer'][$index])) {
							//Error 回答無し
							$answer_count = 0;
						} else {
							$answer_count = 0;
							foreach($_POST['InquiryAnswer'][$index] as $count => $answer) {
								if(!empty($answer['answer_name'])) {
									$answer_count += 1;
								}
							}
						}
						if($answer_count==0) {
							//必須項目を入力して下さい
							//answer
							$error['InquiryAnswer'][$index][1]['answer_name'] = '必須項目を入力して下さい';
						}
					}
				}
			}
		}
		//必須項目を入力して下さい
		//question
		if($question_count == 0) {
			$error['InquiryQuestion'][1]['question_name'] = '必須項目を入力して下さい';
		}
		
		return $error;
	}

	/**
	 * お問い合わせ side menuを作成
	 */
	function getSideMenu() {
		$html = '';
		$models = Inquiry::model()->findAll(array("condition" => "delete_flag = 0",
																							"order" => "inquiry_rank"));
		if(empty($models)) {
			return $html;
		}
		$html .= '<div class="sidebarmenu">'."\n";
		$html .= '  <h4>お問い合わせ</h4>'."\n";
		$html .= '  <ul>'."\n";
		foreach($models as $model) {
			$html .= '    <li><a href="/inquiry/'.$model->inquiry_code.'">'.$model->inquiry_title.'</a></li>'."\n";
		}
		$html .= '  </ul>'."\n";
		$html .= '</div>'."\n";
		return $html;
	}
	
}
