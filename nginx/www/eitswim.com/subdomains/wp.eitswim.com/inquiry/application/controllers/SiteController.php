<?php
require_once('autoload.php');
use Respect\Validation\Validator as V;

session_cache_limiter('private_no_expire');

class SiteController extends Controller {
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
                'actions' => array('index',
                    ),
                'users' => array('*'),
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
            $this->redirect('/');
            /*
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', array('error' => $error));
            */
        }
    }

    /**
     * Top page
     */
    public function actionIndex() {
        //Serviceが増えたら個々に追加していく
        if(isset($_SERVER['SCRIPT_NAME'])) {
            if($_SERVER['SCRIPT_NAME'] == '/contact/index.php') {
                $this->actionInquiry();
            } else {
                $this->redirect('/');
            }
        }
    }

    /**
     * Inquiry Top page
     */
    private function actionInquiry() {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        date_default_timezone_set('Asia/Tokyo');

        //モバイル判定
        $prefix = getPrefix();

        /*
        if(param('ssl')) {
            //SSLアクセスでない場合は、強制移動
            $isSecure = Yii::app()->getRequest()->isSecureConnection;
            if(!$isSecure)
                $this->redirect(Yii::app()->homeUrl . $_SERVER["REQUEST_URI"]);
        }
        */

        $isSecure = Yii::app()->getRequest()->isSecureConnection;

        $kind = '';
        $id = 0;
        $area = '';
        $option = array();
        $error = array();
        $data = array();
        $function = 'index';
        $code = 'eitswim';//変える
        $model = Inquiry::model()->findByAttributes(array('inquiry_code' => $code, 'delete_flag' => 0));
        if($model) {
            $option['question'] = InquiryQuestion::model()->findAll(
                array("condition" => "question_inquiry_id = {$model->inquiry_id} AND delete_flag = 0",
                    "order" => "question_rank"
                ));
        } else {
            $this->redirect('/contact');
        }
        if(isset($_GET['kind'])) {
            $kind = $_GET['kind'];
            //conf
            //thanks
            if(!empty($kind)) {
                $function = $kind;
            }
        }
        $code = '';
	    if(isset($_GET['function'])) {
		    $code = $_GET['function'];
	    }

        if(isset($_POST['answer'])) {
            $model->answer = $_POST['answer'];
        }
        if(!empty($_POST['mode'])) {
            if($_POST['mode']=='validation') {
                if ( ! Inquiry::model()->checkValidation( $model ) ) {
                    //エラーの場合
                    $function = 'index';
                } else {
                    //メール送信処理・履歴保存
                    if(!empty($_POST['answer']))
                        $model->answer = $_POST['answer'];
                    if ( Inquiry::model()->sendMail( $model ) ) {
                        //$this->redirect('/contact/'.$model->inquiry_code.'/thanks');
                        //$this->redirect('/contact/thanks');
                        $option['status'] = 'success';
                        $this->redirect('/contact/complete');
                    }
                }
            }
        } else {
            // inqury/xxx/confが直接呼ばれた場合は、contact/xxx/へリダイレクトさせる
            if($kind=='conf') {
                //$this->redirect('/contact/'.$model->inquiry_code.'/');
                $this->redirect('/contact');
            } else if($kind =='complete'){
                $function = 'index';
                $option['status'] = 'success';
            }
        }

        $this->render('contact/'.$function, array(
          'model' => $model,
          'data' => $data,
          'option' => $option,
          'error' => $error,
          'isSecure' => $isSecure,
        ));

    }

}
