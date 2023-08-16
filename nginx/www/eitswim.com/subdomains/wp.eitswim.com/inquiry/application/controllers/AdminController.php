<?php
require_once('autoload.php');
use Respect\Validation\Validator as V;

//session_cache_limiter('private_no_expire');

class AdminController extends Controller {

    public $layout = 'column1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('assets', 'css', 'js', 'login'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'top', 'master', 'history', 'test', 'pdf', 'excel'),
                'users' => array('@'),
//                'ips' => param('ips'),
                ),
            array('deny', // deny all users
                'users' => array('*'),
//                'ips' => array('*')
            ),
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if($error = Yii::app()->errorHandler->error) {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', array('error' => $error));
        }
    }

    /**
     * Top page
     */
    public function actionLogin() {

        //モバイル判定
        $prefix = getPrefix();

        $this->render('login', array());
    }

    /**
     * Top page
     */
    public function actionMaster() {
        //Serviceが増えたら個々に追加していく
        if(isset($_SERVER['SCRIPT_NAME'])) {
            if($_SERVER['SCRIPT_NAME'] == '/contact/index.php') {
                $this->actionMasterInquiry();
            } else {
                $this->redirect('/');
            }
        }
    }

    public function actionIndex() {
        //Serviceが増えたら個々に追加していく
        if(isset($_SERVER['SCRIPT_NAME'])) {
            if($_SERVER['SCRIPT_NAME'] == '/contact/index.php') {
                $this->actionIndexTop();
            } else {
                $this->redirect('/');
            }
        }
    }

    public function actionHistory() {
        //Serviceが増えたら個々に追加していく
        if(isset($_SERVER['SCRIPT_NAME'])) {
            if($_SERVER['SCRIPT_NAME'] == '/contact/index.php') {
                $this->actionHistoryTop();
            } else {
                $this->redirect('/');
            }
        }
    }

    public function actionTop() {
        //Serviceが増えたら個々に追加していく
        if(isset($_SERVER['SCRIPT_NAME'])) {
            if($_SERVER['SCRIPT_NAME'] == '/contact/index.php') {
                $this->actionIndexTop();
            } else {
                $this->redirect('/');
            }
        }
    }

    public function actionIndexTop() {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        date_default_timezone_set('Asia/Tokyo');

        //Adminかどうか
        $isAdmin =Yii::app()->getModule('user')->isAdmin();

        //URLに付加する
        $ses = '?' . date("YmdHis");
        $sec = '&' . date("YmdHis");
        $data = array();
        $model = array();
        $data0 = array();
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $option = array();
        $action = 'top';
        //モバイル判定
        $function = 'index';
        $id = '';
        $cmd = '';
        if(isset($_GET['function'])) {
            $function = $_GET['function'];
        }
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if(isset($_GET['cmd'])) {
            $cmd = $_GET['cmd'];
        }
        //view template設定
        $template = $function;

        switch($function) {
            case 'index':
                if($function=='index') {
                    $template = 'index';
                }
                break;
            default:
                $this->redirect(RM());
                break;
        }

        $this->render('inquiry/'.$action . '/' . $template, array(
            'action' => $action,
            'template' => $template,
            'cmd' => $cmd,
            'id' => $id,
            'model' => $model,
            'data' => $data,
            'data0' => $data0,
            'data1' => $data1,
            'data2' => $data2,
            'data3' => $data3,
            'option' => $option,
        ));
    }

    /**
     * History
     */
    public function actionHistoryTop() {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        date_default_timezone_set('Asia/Tokyo');

        //Adminかどうか
        $isAdmin =Yii::app()->getModule('user')->isAdmin();

        //URLに付加する
        $ses = '?' . date("YmdHis");
        $sec = '&' . date("YmdHis");
        $data = array();
        $model = array();
        $data0 = array();
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $option = array();
        $action = 'history';
        //モバイル判定
        $function = 'index';
        //view template設定
        $template = $function;
        $id = '';
        $cmd = '';
        if(isset($_GET['function'])) {
            $function = $_GET['function'];
        }
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if(isset($_GET['cmd'])) {
            $cmd = $_GET['cmd'];
        }

        $category = '';
        switch($function) {
            case 'advertisement':
                if(empty($category)) $category = 'Advertisement';
            case 'support':
                if(empty($category)) $category = 'Support';
            case 'inquiry':
                if(empty($category)) $category = 'Inquiry';
            case 'others':
                if(empty($category)) $category = 'Other';
            case 'all':
                //履歴
                if(isset($_POST['mode'])) {
                    if($_POST['mode'] === 'search') {
                        $page = 0;
                        if(!empty($_GET['page'])) $page = $_GET['page'];
                        if(!empty($_POST['page'])) $page = $_POST['page'];
                        $model = new Inquiry;
                        $model->attributes = $_POST;
                        if(!empty($category)) $model->category = $category;
                        $option['page'] = $page;
                        $option['total_count'] = Inquiry::model()->countByModel($model);
                        $data = Inquiry::model()->searchByModel($model, $page);
                    } elseif($_POST['mode'] === 'validate') {
                    } elseif($_POST['mode'] === 'save') {
                    } elseif($_POST['mode'] === 'update') {
                    } elseif($_POST['mode'] === 'delete') {
	                    $template = $function;
                    	if(!empty($_POST['history_ids'])) {
                    		$ids = $_POST['history_ids'];
                    		foreach($ids as $key=>$item) {
                    			$history = InquiryHistory::model()->findByPk($key);
                    			if($item=='on') {
				                    if($history) {
					                    $history->delete();
				                    }
			                    }
		                    }
	                    }
	                    $this->redirect(RA().'history/'.$function);
                    }
                } else {
                    if(empty($id)) {
                        $page = 0;
                        if(!empty($_GET['page'])) $page = $_GET['page'];
                        if(!empty($_POST['page'])) $page = $_POST['page'];
                        $model = new Inquiry;
                        $model->attributes = $_POST;
                        if(!empty($category)) $model->category = $category;
                        $option['page'] = $page;
                        $option['total_count'] = Inquiry::model()->countByModel($model);
                        $data = Inquiry::model()->searchByModel($model, $page);
                    }
                }
                $option['kind'] = Inquiry::model()->findAll(array(
                    "condition" => "delete_flag = 0",
                    "order" => "inquiry_rank"
                ));
                if(empty($option['page'])) $option['page'] = 0;
                if(empty($option['total_count'])) $option['total_count'] = 0;

                //$model->attributes = $_POST;
/*
                if(!empty($_POST['csv'])) {
                    //CSV出力
                    $dataList = Inquiry::model()->getCsvList($model);
                    $file = "お問い合わせ_".date("Ymd").".csv";
                    downloadCSV($dataList, $file);
                    return;
                }
*/
                break;
            case 'detail':
                $template = $function;
                if(!empty($_POST['cmd'])) {
                    $cmd = $_POST['cmd'];
                }
                if(!empty($_POST['mode'])) {
                	$mode = $_POST['mode'];
                	if($mode =='update') {
                		$model = InquiryHistory::model()->findByPk($id);
                		if($model) {
                			$model->attributes = $_POST;
                			$model->save();
		                }
	                } else if($mode == 'delete') {
		                $model = InquiryHistory::model()->findByPk($id);
		                if($model) {
			                $model->attributes = $_POST;
			                $model->delete();
			                $this->redirect(RA().'history/all');
		                }
	                }
                }
                $model = InquiryHistory::model()->searchById($id);
                break;
            default:
                $this->redirect(RM());
                break;
        }

        $this->render('inquiry/'.$action . '/' . $template, array(
            'action' => $function,
            'template' => $template,
            'cmd' => $cmd,
            'id' => $id,
            'model' => $model,
            'data' => $data,
            'data0' => $data0,
            'data1' => $data1,
            'data2' => $data2,
            'data3' => $data3,
            'option' => $option,
        ));
    }

    /**
     * Inquiry Master page
     */
    public function actionMasterInquiry() {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        date_default_timezone_set('Asia/Tokyo');

        //Adminかどうか
        $isAdmin =Yii::app()->getModule('user')->isAdmin();

        //URLに付加する
        $ses = '?' . date("YmdHis");
        $sec = '&' . date("YmdHis");
        $data = array();
        $model = array();
        $data0 = array();
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $option = array();
        $action = 'master';
        //モバイル判定
        $function = 'index';
        $id = '';
        $cmd = '';
        if(isset($_GET['function'])) {
            $function = $_GET['function'];
        }
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if(isset($_GET['cmd'])) {
            $cmd = $_GET['cmd'];
        }
        //view template設定
        $template = $function;

        switch($function) {
            case 'banner':
                if(!empty($_POST['cmd'])) {
                    $cmd = $_POST['cmd'];
                }
                if($cmd=='edit' || $cmd=='add' || $cmd=='conf') {
                    $template = $function.'_add';
                    if($cmd=='conf') {
                        $template = $function.'_conf';
                    }
                    if(!empty($id)) {
                        $model = InquiryBanner::model()->findByPk($id);
                    }
                }
                $data = InquiryBanner::model()->findAll(array("condition" => "delete_flag = 0",
                                                        "order" => "banner_rank"
                                                  ));
                if(!empty($_POST['mode'])) {
                    $mode = $_POST['mode'];
                    if($mode=='validate') {
                        //入力チェック
                        $error = Inquiry::model()->saveValidation();
                        if(empty($error)) {
                            $template = $function.'_conf';
                            if(!empty($_POST['Inquiry'])) {
                                $option['Inquiry'] = $_POST['Inquiry'];
                            }
                            if(!empty($_POST['InquiryAnswer'])) {
                                $option['InquiryAnswer'] =  $_POST['InquiryAnswer'];
                            }
                            if(!empty($_POST['InquiryBcc'])) {
                                $option['InquiryBcc'] =  $_POST['InquiryBcc'];
                            }
                            if(!empty($_POST['InquiryMail'])) {
                                $option['InquiryMail'] =  $_POST['InquiryMail'];
                            }
                            if(!empty($_POST['InquiryMailSub'])) {
                                $option['InquiryMailSub'] =  $_POST['InquiryMailSub'];
                            }
                            if(!empty($_POST['InquiryQuestion'])) {
                                $option['InquiryQuestion'] =  $_POST['InquiryQuestion'];
                            }
                            if(!empty($_POST['InquiryTo'])) {
                                $option['InquiryTo'] =  $_POST['InquiryTo'];
                            }
                        } else {
                            $model = new Inquiry;
                            $model->attributes = $_POST['Inquiry'];
                            $option['mail'] = new InquiryMail;
                            $option['mail']->attributes = $_POST['InquiryMail'];
                            if(!empty($_POST['InquiryTo'])) {
                                $option['mail_to'] = array();
                                foreach($_POST['InquiryTo'] as $index => $to) {
                                    $option['mail_to'][$index] = new InquiryTo;
                                    $option['mail_to'][$index]->attributes = $to;
                                }
                            }
                            if(!empty($_POST['InquiryBcc'])) {
                                $option['mail_bcc'] = array();
                                foreach($_POST['InquiryBcc'] as $index => $bcc) {
                                    $option['mail_bcc'][$index] = new InquiryBcc;
                                    $option['mail_bcc'][$index]->attributes = $bcc;
                                }
                            }
                            $option['question'] = array();
                            foreach($_POST['InquiryQuestion'] as $index => $question) {
                                $option['question'][$index] = new InquiryQuestion;
                                $option['question'][$index]->attributes = $question;
                            }
                            $option['answer'] = array();
                            foreach($_POST['InquiryAnswer'] as $index => $item) {
                                foreach($item as $count => $answer) {
                                    $option['answer'][$index][$count] = new InquiryAnswer;
                                    $option['answer'][$index][$count]->attributes = $answer;
                                }
                            }

                            //エラーの場合
                            $template = $function.'_add';
                            $option['error'] = $error;
                        }

                    } else if($mode=='update') {
                        //Save
                        if(Inquiry::model()->saveData($model)) {
                            //OK
                        } else {
                            //NG
                        }
                        $this->redirect(RM().'form');
                    }
                }
                break;
            case 'form':
                if(!empty($_POST['cmd'])) {
                    $cmd = $_POST['cmd'];
                }
                if($cmd=='edit' || $cmd=='add' || $cmd=='conf') {
                    $template = $function.'_add';
                    if($cmd=='conf') {
                        $template = $function.'_conf';
                    }
                    if(!empty($id)) {
                        $model = Inquiry::model()->findByPk($id);
                        $option['mail'] = InquiryMail::model()->findByAttributes(array('mail_inquiry_id' => $id,'delete_flag'=>0));

                        $option['question'] = InquiryQuestion::model()->findAll(
                          array("condition" => "question_inquiry_id = {$id} AND delete_flag = 0",
                                "order" => "question_rank"
                          ));
                    }
                }
                $data = Inquiry::model()->findAll(array("condition" => "delete_flag = 0",
                                                         "order" => "inquiry_rank"
                                                   ));
                if(!empty($_POST['mode'])) {
                    $mode = $_POST['mode'];
                    if($mode=='validate') {
                        //入力チェック
                        $error = Inquiry::model()->saveValidation();
                        if(empty($error)) {
                            $template = $function.'_conf';
                            if(!empty($_POST['Inquiry'])) {
                                $option['Inquiry'] = $_POST['Inquiry'];
                            }
                            if(!empty($_POST['InquiryAnswer'])) {
                                $option['InquiryAnswer'] =  $_POST['InquiryAnswer'];
                            }
                            if(!empty($_POST['InquiryBcc'])) {
                                $option['InquiryBcc'] =  $_POST['InquiryBcc'];
                            }
                            if(!empty($_POST['InquiryMail'])) {
                                $option['InquiryMail'] =  $_POST['InquiryMail'];
                            }
                            if(!empty($_POST['InquiryMailSub'])) {
                                $option['InquiryMailSub'] =  $_POST['InquiryMailSub'];
                            }
                            if(!empty($_POST['InquiryQuestion'])) {
                                $option['InquiryQuestion'] =  $_POST['InquiryQuestion'];
                            }
                            if(!empty($_POST['InquiryTo'])) {
                                $option['InquiryTo'] =  $_POST['InquiryTo'];
                            }
                        } else {
                            $model = new Inquiry;
                            $model->attributes = $_POST['Inquiry'];
                            $option['mail'] = new InquiryMail;
                            $option['mail']->attributes = $_POST['InquiryMail'];
                            if(!empty($_POST['InquiryTo'])) {
                                $option['mail_to'] = array();
                                foreach($_POST['InquiryTo'] as $index => $to) {
                                    $option['mail_to'][$index] = new InquiryTo;
                                    $option['mail_to'][$index]->attributes = $to;
                                }
                            }
                            if(!empty($_POST['InquiryBcc'])) {
                                $option['mail_bcc'] = array();
                                foreach($_POST['InquiryBcc'] as $index => $bcc) {
                                    $option['mail_bcc'][$index] = new InquiryBcc;
                                    $option['mail_bcc'][$index]->attributes = $bcc;
                                }
                            }
                            $option['question'] = array();
                            foreach($_POST['InquiryQuestion'] as $index => $question) {
                                $option['question'][$index] = new InquiryQuestion;
                                $option['question'][$index]->attributes = $question;
                            }
                            $option['answer'] = array();
                            foreach($_POST['InquiryAnswer'] as $index => $item) {
                                foreach($item as $count => $answer) {
                                    $option['answer'][$index][$count] = new InquiryAnswer;
                                    $option['answer'][$index][$count]->attributes = $answer;
                                }
                            }

                            //エラーの場合
                            $template = $function.'_add';
                            $option['error'] = $error;
                        }

                    } else if($mode=='update') {
                        //Save
                        if(Inquiry::model()->saveData($model)) {
                            //OK
                        } else {
                            //NG
                        }
                        $this->redirect(RM().'form');
                    }
                }
                break;
            case 'detail':
                if(!empty($_POST['cmd'])) {
                    $cmd = $_POST['cmd'];
                }
                $model = InquiryHistory::model()->findByPk($id);
            break;
            case 'index':
                if($function=='index') {
                    $template = 'history';
                }
            case 'history':
                //履歴
                if(isset($_POST['mode'])) {
                    if($_POST['mode'] === 'search') {
                        $page = 0;
                        if(!empty($_GET['page'])) $page = $_GET['page'];
                        if(!empty($_POST['page'])) $page = $_POST['page'];
                        $model = new Inquiry;
                        $model->attributes = $_POST;
                        $option['page'] = $page;
                        $option['total_count'] = Inquiry::model()->countByModel($model);
                        $data = Inquiry::model()->searchByModel($model, $page);
                    } elseif($_POST['mode'] === 'validate') {
                    } elseif($_POST['mode'] === 'save') {
                    } elseif($_POST['mode'] === 'update') {
                    } elseif($_POST['mode'] === 'delete') {
                    }
                } else {
                    if(empty($id)) {
                        $page = 0;
                        if(!empty($_GET['page'])) $page = $_GET['page'];
                        if(!empty($_POST['page'])) $page = $_POST['page'];
                        $model = new Inquiry;
                        $model->attributes = $_POST;
                        $option['page'] = $page;
                        $option['total_count'] = Inquiry::model()->countByModel($model);
                        $data = Inquiry::model()->searchByModel($model, $page);
                    }
                }
                $option['kind'] = Inquiry::model()->findAll(array(
                                                                   "condition" => "delete_flag = 0",
                                                                   "order" => "inquiry_rank"
                                                                 ));
                if(empty($option['page'])) $option['page'] = 0;
                if(empty($option['total_count'])) $option['total_count'] = 0;

                $model->attributes = $_POST;

                if(!empty($_POST['csv'])) {
                    //CSV出力
                    $dataList = Inquiry::model()->getCsvList($model);
                    $file = "お問い合わせ_".date("Ymd").".csv";
                    downloadCSV($dataList, $file);
                    return;
                }

                break;
            case 'download':
                if(isset($_GET['file'])) {
                    error_download($_GET['file']);
                }
                return;
                break;
            default:
                $this->redirect(RM());
                break;
        }

        $this->render('inquiry/'.$action . '/' . $template, array(
          'action' => $action,
          'template' => $template,
          'cmd' => $cmd,
          'id' => $id,
          'model' => $model,
          'data' => $data,
          'data0' => $data0,
          'data1' => $data1,
          'data2' => $data2,
          'data3' => $data3,
          'option' => $option,
        ));
    }

    
}
