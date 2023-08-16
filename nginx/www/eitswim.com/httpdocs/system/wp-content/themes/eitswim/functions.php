<?php
echo "<script>console.log('eitswim functions.php');</script>";
//Image Resize Plugin
locate_template('lib/aq_resizer.php', true);

//外観カスタム
locate_template('lib/custom.php', true);

//カスタム投稿追加
//locate_template('lib/custom_post.php', true);

//カスタムフィールド追加
locate_template('lib/custom_field.php', true);

//表示カスタム
locate_template('lib/display.php', true);

//WIDGETカスタム
locate_template('lib/widgets.php', true);

//カスタム検索
//locate_template('lib/custom_search.php', true);

add_filter( 'jetpack_enable_opengraph', '__return_false', 99 );

/**
 * ファイルの更新時刻をバージョンとする
 * @param $url
 * @return string
 * $ver = file_ver(SDEL.'/images/slide02.jpg');
 */
function file_ver($url) {
    $ver = file_time($url, dirname( __FILE__ ));
    echo empty($ver)?'':'?v='.$ver;
}

/*
 * タイトルを変更する
 */
function custom_document_title_parts($title) {
  $ret = $title;
  if(!empty($ret['title']) && $ret['title'] == 'ページが見つかりませんでした') {
    $ret['title'] = '404 Not Found';
  }

  return $ret;
}

/*
 * MW WP Formの自作バリテーション
 */
function mwform_validation_recaptcha( $validation_rules ) {

    if(! class_exists('MW_Validation_Recaptcha')) {

        
        class MW_Validation_Recaptcha extends MW_WP_Form_Abstract_Validation_Rule {
            /*
             * 独自のバリデーションルール名を設定します。
             * 他のバリテーションを被らない名前が良いです。
             */
            protected $name = 'mwformrecaptcha';

             public function rule( $item_name, array $options = array() ) {

               //データが送信されていない（最初にフォームページが表示された）時は以下の処理をしない設定
                if( strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST' ) return '';
                
                /*
                 * 取得したトークンを取得
                 */
                $token = $this->Data->get($item_name);
                $token = !empty($token) ? $token : '';
                
                /*
                 * 管理画面のreCAPTCHAのバリテーションが設定されているかのチェックして変数にいれる
                 */
                $is_reCAPTCHA = isset($options['is_reCAPTCHA']) ? $options['is_reCAPTCHA'] : false;
                
                $secret_key = '6Lc_zagnAAAAADtZJRj-PdSagoCUOOVh7gdLr5kh';      //ここには取得したシークレットキーを記載します
                $threshold_score = 0.5;//閾値の設定
                
                //管理画面にてreCAPTCHAバリテーションのチェックがあった時の処理
                if($is_reCAPTCHA !== false) {
                    
                    if($item_name === 'recaptchaToken' && !isset($_POST['submitBack'])) {
                        
                        if(!isset($secret_key) || $secret_key === '') {
                            $defaults = array(
                                'message' => __('No reCAPTCHA Secret Key','efc-theme')
                            );
                            $options = array_merge($defaults,$options);
                            return $options['message'];
                        }
                        //Google reCAPTCHA APIに投げて判定してもらう設定
                        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $token;
                        $response = wp_remote_get($url);
                            if (!is_wp_error($response) && $response["response"]["code"] === 200) {
                                
                                $reCAPTCHA = json_decode($response["body"]);

                                if($reCAPTCHA->success) {
                                    if( $reCAPTCHA->score < $threshold_score) {
                                        
                                        $defaults = array(
                                            'message' => 'reCAPTCHAがスパムロボットと判断しましたので送信できませんでした!!'
                                        );
                                        $options = array_merge($defaults,$options);
                                        return $options['message'];
                                    }
                                } else {

                                    $defaults = array(
                                        'message' =>'reCAPTCHAがスパムロボットと判断しましたので送信できませんでした'
                                    );
                                    $options = array_merge($defaults, $options);
                                    return $options['message'];                                    
                                    
                                }
                                // \$reCAPTCHA->success
                                
                            } else {

                                $defaults = array(
                                    'message' => __('Faild reCAPtCHA Access','efc-theme')
                                );
                                $options = array_merge($defaults,$options);
                                return $options['message'];

                            }

                    }
                }
             }

            /*
             * フォーム編集画面の「バリデーションルール」に設定を追加する
             */
            public function admin($key, $value) {
                $is_reCAPTCHA = false;
                if (is_array($value[$this->getName()]) && isset($value[$this->getName()]['is_reCAPTCHA'])) {
                    $is_reCAPTCHA = $value[$this->getName()]['is_reCAPTCHA'];
                }
        ?>      
                <table>
                    <tr>
                        <td>reCAPTCHA V3</td>
                        <td><input type="checkbox" value="1" name="<?php echo MWF_Config::NAME; ?>[validation][<?php echo $key; ?>][<?php echo esc_attr($this->getName()); ?>][is_reCAPTCHA]" <?php if ($is_reCAPTCHA) : ?>checked<?php endif; ?> /></td>
                    </tr>
                </table>
        <?php
            }
        }
    }

    //上記ルールのインスタンスを作って返す
    if(!isset($instance)) {
        $instance = new MW_Validation_Recaptcha();
        $validation_rules[$instance->getName()] = $instance;
        return $validation_rules;
    }
}

add_filter('mwform_validation_rules','mwform_validation_recaptcha',20,1);

$addTokenUsePreventDefault = '
if(document.querySelector(".mw_wp_form_input form")) {
    const myForm = document.querySelector(".mw_wp_form_input form");
    let preventEvent = true;
    const getToken =  (e) => {
        const target = e.target;
        if(preventEvent) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute("'. $siteKey .'", {action: "homepage"})
                .then(function(token) {
                    preventEvent = false;
                    if(document.querySelector("[name=recaptchaToken]")) {
                        const recaptchaToken = document.querySelector("[name=recaptchaToken]");
                        recaptchaToken.value = token;
                    }
                    if(myForm.querySelector("[name=submitConfirm]")) {
                        const confirmButtonValue = myForm.querySelector("[name=submitConfirm]").value;
                        const myComfirmButton = document.createElement("input");
                        myComfirmButton.type = "hidden";
                        myComfirmButton.value = confirmButtonValue;
                        myComfirmButton.name = "submitConfirm";
                        myForm.appendChild(myComfirmButton);
                    }

                    myForm.submit();
                })
                .catch(function(e) {
                    alert("reCAPTCHA token取得時にエラーが発生したためフォームデータを送信できません");
                    return false;
                });
            });

        }

    }
    myForm.addEventListener("submit",getToken);

} else if(document.querySelector(".mw_wp_form_confirm form")){

    let count=0;
    const timer = 60 * 1000 * 2;
    getToken = () => {
        grecaptcha.ready(function(){
            grecaptcha.execute("'. $siteKey .'",{action:"homepage"})
            .then(function(token){
                const recaptchaToken=document.querySelector("[name=recaptchaToken]");
                recaptchaToken.value=token;
                if(count<4){
                    setTimeout(getToken,timer)
                }
                    count++
            })
            .catch(function(e){
                alert("reCAPTCHA token取得時にエラーが発生したためフォームデータを送信できません");
                return false
            });
        });
    }
    document.addEventListener("DOMContentLoaded",getToken);

}';

$siteKey = '6Lc_zagnAAAAANW4VWiI4HpJXLsCgIIEQdEE3N_T';      //サイトキー
$loadReCaptcha = 'https://www.google.com/recaptcha/api.js?render=' . $siteKey;

    wp_enqueue_script('reCAPTCHv3', $loadReCaptcha, array(), 'v3', true);
    wp_add_inline_script('reCAPTCHv3', $addTokenUsePreventDefault);

