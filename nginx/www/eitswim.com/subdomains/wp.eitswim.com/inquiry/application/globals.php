<?php
//For Validation
use Respect\Validation\Validator as V;

$LIB_DIR = realpath(dirname(__FILE__).'/extensions/').'/';
$LIB_DIR = dirname(__FILE__).'/extensions/';
require_once $LIB_DIR.'Dm/Geocoder.php';
require_once $LIB_DIR.'Dm/Geocoder/Address.php';
require_once $LIB_DIR.'Dm/Geocoder/Prefecture.php';
require_once $LIB_DIR.'Dm/Geocoder/Query.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV/Finder.php';
require_once $LIB_DIR.'Dm/Geocoder/GISCSV/Reader.php';

//$result = Dm_Geocoder::geocode('北海道河西郡中札内村東一条北六丁目');

/**
 * Below is the code containing some of the most commonly used shortcut functions.
 * Feel free to adjust it according to your taste.
 * http://www.yiiframework.com/wiki/31/
 */
/**
 * This is the shortcut to DIRECTORY_SEPARATOR
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);


/**
 *
 * @param string $string
 * @param string $str4empty
 * @return boolean (always true)
 */
function echoIfEmpty($string, $str4empty = '-') {
    if(empty($string)) {
        echo $str4empty;
    } else {
        echo $string;
    }
    return true;
}

/**
 * create meta tag
 * @param string $name
 * @param string $item
 * @param string $format
 * @return string
 */
function createMeta($name, $item, $format = "{item} ｜ {common}") {
    return str_replace(array('{common}', '{item}'), array(param($name), $item), $format);
}

/**
 * shorten multibyte string
 * @param string $str
 * @param integer $max
 * @param string $end
 * @return string
 */
function cutString($str, $max, $end = '…') {
    if($max >= mb_strlen($str, app()->charset))
        return $str;
    return mb_substr($str, 0, $max, app()->charset) . $end;
}

/**
 * This is the shortcut to Yii::app()
 */
function app() {
    return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 */
function cs() {
    // You could also call the client script instance via Yii::app()->clientScript
    // But this is faster
    return Yii::app()->getClientScript();
}

/**
 * This is the shortcut to Yii::app()->user.
 */
function user() {
    return Yii::app()->getUser();
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route, $params = array(), $ampersand = '&') {
    return Yii::app()->createUrl($route, $params, $ampersand);
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text) {
    return CHtml::encode($text);
    //return htmlspecialchars($text,ENT_QUOTES,app()->charset);
    //return htmlspecialchars($text);
}

/**
 * \r, \n => <br>
 * This is the shortcut to CHtml::encode
 */
function hbr($text) {
    return nl2br(CHtml::encode($text));
}


/**
 * This is the shortcut to CHtml::link()
 */
function l($text, $url = '#', $htmlOptions = array()) {
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t() with default category = 'stay'
 */
function t($message, $category = 'stay', $params = array(), $source = null, $language = null) {
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = null) {
    static $baseUrl;
    if($baseUrl === null)
        $baseUrl = Yii::app()->getRequest()->getBaseUrl();
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 */
function param($name) {
    return Yii::app()->params[$name];
}

/**
 * This is the shortcut to print_r()
 */
function pr($val) {
    return print_r($val);
}

/**
 * Date変換表示
 * @param $date
 * @return string
 */
function dt($date) {
    return substr(str_replace("-", ".", $date), 0, 10);
}

/**
 * This is the shortcut to Yii::app()->format->formatNtext
 * @param $text
 * @return mixed
 */
function n($text) {
    return Yii::app()->format->formatNtext($text);
}

/**
 * マルチバイト文字切り出し
 * @param $str
 * @param int $len
 * @return string
 */
function mbs($str, $len = 140) {
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');

    return mb_substr($str, 0, $len);
}

/**
 * マルチバイト文字切り出しタイトル用
 * @param $str
 * @param int $len1
 * @param int $len2
 * @param string $opt
 * @return string
 */
function mbt($str, $len1 = 13, $len2 = 24, $opt = '...') {
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');

    if(mb_strlen($str) > $len2) {
        $msg = mb_substr($str, 0, $len2) . $opt;
    } else if(mb_strlen($str) > $len1) {
        $msg = $str;
    } else {
        //$msg = $str.mb_substr("　　　　　　　　　　　　　", 0, $len1-mb_strlen($str));
        $msg = $str;
    }
    return $msg;
}

/**
 * 現在日時を取得
 * @return bool|string
 */
function now() {
    return date("Y-m-d H:i:s");
}


/**
 * クリア ALL SESSION 変数
 */
function unsetAllSession() {
    unset(Yii::app()->session['xxxxxx']);
}

/**
 * File Read
 * $bod = ReadHtml(Yii::app()->basepath . '/views/sig.html');
 * @param $fna
 * @return string
 */
function ReadHtml($fna) {
    $fh = fopen($fna, 'r');
    $theData = fread($fh, filesize($fna));
    fclose($fh);
    return $theData;
}

/**
 * File Write
 * $bod = WriteHtml(Yii::app()->basepath . '/views/sig.html', $data);
 * @param $fna
 * @param $data
 * @return int
 */
function WriteHtml($fna, $data) {
    $fh = fopen($fna, 'w');
    $return = fwrite($fh, $data);
    fclose($fh);
    return $return;
}

/**
 * Return Tab Craractor
 * @param int $count
 * @return string
 */
function TAB($count = 1) {
    $ret = "";
    for($i = 0; $i < $count; $i++) {
        $ret .= "\t";
    }
    return $ret;
}

/**
 * Return CR Craractor
 * @param int $count
 * @return string
 */
function CR($count = 1) {
    $ret = "";
    for($i = 0; $i < $count; $i++) {
        $ret .= "\r";
    }
    return $ret;
}

/**
 * Return CRLF Craractor
 * @param int $count
 * @return string
 */
function CRLF($count = 1) {
    $ret = "";
    for($i = 0; $i < $count; $i++) {
        $ret .= "\r\n";
    }
    return $ret;
}

/**
 * モバイル判定を行う
 * @return bool
 */
function isMobile() {
    //モバイル判定
    $detect = Yii::app()->mobileDetect;
    if(param('TABLET_IS_MOBILE')) {
        if($detect->isMobile()) {
            return True;
        } else {
            return False;
        }
    } else {
        if($detect->isMobile() && !$detect->isTablet()) {
            return True;
        } else {
            return False;
        }
    }
}

/**
 * モバイル判定を行い、prefixを取得する
 * @return string
 */
function getPrefix() {
    //モバイル判定
    if(isMobile()) {
        $prefix = param('MOBILE_PREFIX');
    } else {
        $prefix = '';
    }
    return $prefix;
}

/**
 * NULL(empty)なら''を返す
 * @param $source
 * @return string
 */
function IFN($source) {
    $ret = empty($source) ? '' : $source;
    return $ret;
}

/**
 * emptyなら'0'を返す
 * @param $source
 * @return string
 */
function IF0($source) {
    $ret = empty($source) ? '0' : $source;
    return $ret;
}

/**
 * emptyでないなら'1'を返す
 * @param $source
 * @return string
 */
function IF1($source) {
    $ret = empty($source) ? '0' : '1';
    return $ret;
}

/**
 * NULL(empty)なら''を返す
 * @param $source
 * @return string
 */
function IFNP($source) {

    $ret = empty($_POST[$source]) ? '' : $_POST[$source];
    return $ret;
}

/**
 * emptyなら'0'を返す
 * @param $source
 * @return string
 */
function IF0P($source) {
    $ret = empty($_POST[$source]) ? '0' : $_POST[$source];
    return $ret;
}

/**
 * emptyでないなら'1'を返す
 * @param $source
 * @return string
 */
function IF1P($source) {
    $ret = empty($_POST[$source]) ? '0' : '1';
    return $ret;
}

/**
 * 金額フォーマットに変換する
 * @param string $pre
 * @param $number
 * @param string $suf
 * @return string
 */
function cvtPrice($pre = '', $number, $suf = '') {
    if($number === '')
        return '';
    if($number === '-')
        return '-';
    $ret = number_format($number);
    $ret = $pre . $ret . $suf;
    return $ret;
}

/**
 * 個数内のランダムな値を返す（10を指定すると、0-9の値を返す）
 * @param $count    個数
 * @param int $min  最小値 = 0
 *
 * @return mixed
 */
function getRand($count, $min=0) {
    $max = $min + $count -1;

    //range()を使い$min から $max までの整数を値に持つ配列を$rand_arに取得
    $rand_ar = range($min , $max);

    //shuffle()を使い$rand_arの並びをランダムにする
    shuffle($rand_ar);

    return $rand_ar[0];
}

/**
 * ここから、Wifi Spot用
 */

/**
 * Admin Root
 * @return string
 */
function R() {
    //return Yii::app()->request->baseUrl . '/';
    return '/contact/';
}

/**
 * Common Root
 * @return string
 */
function RC() {
    return R() . 'common/';
}


/**
 * Admin Root
 * @return string
 */
function RA() {
    return R() . 'admin/';
}

/**
 * History Root
 * @return string
 */
function RAH() {
    return RA() . 'history/';
}

function RAS() {
    return R() . '../../admin/';
}

/**
 * Admin Master Root
 * @return string
 */
function RM() {
    return R() . 'admin/master/';
}

function RR() {
    return R() . 'admin/release/';
}

/**
 * Admin Order Root
 * @return string
 */
function RO() {
    return R() . 'admin/order/';
}

/**
 * chekedを返す
 * '':empty() => checked
 * '*':!empty() => checked
 * @param $value
 * @param $number
 * @return string
 */
function CKD($value, $number) {
    if(!isset($value))
        return '';
    if($number == '*') {
        if(empty($value)) {
            echo ' checked';
        } else {
            echo '';
        }
    } else {
        if($value == $number) {
            echo ' checked';
        } else {
            echo '';
        }
    }
}

/**
 * selectedを返す
 * @param $value
 * @param $number
 * @return string
 */
function STD($value, $number) {
    if(!isset($value))
        return '';
    if($value == $number) {
        echo ' selected';
    } else {
        echo '';
    }
}

/**
 * 日付変換 for Picker
 * YYYY-MM-DD => YYYY年MM月DD日
 * @param $date
 * @return bool|string
 */
function d2j($date) {
    if(empty($date))
        return $date;
    return date('Y年m月d日', strtotime($date));
}

/**
 * 日付変換 for Picker
 * YYYY-MM-DD => YYYY年M月D日
 * @param $date
 * @return bool|string
 */
function d2js($date) {
    if(empty($date))
        return $date;
    return date('Y年n月j日', strtotime($date));
}

/**
 * 日付変換 for Picker
 * YYYY-MM-DD => M月D日
 * @param $date
 * @return bool|string
 */
function d2jss($date) {
    if(empty($date))
        return $date;
    return date('n月j日', strtotime($date));
}

/**
 * 日付変換 for Picker
 * YYYY-MM-DD => YYYY/MM/DD
 * @param $date
 * @return mixed|string
 */
function d2p($date) {
    return dateToPicker($date);
}

function dateToPicker($date) {
    if(empty($date))
        return '';
    return str_replace('-', '/', $date);
}

/**
 * 日付変換 for Picker
 * YYYY/MM/DD => YYYY-MM-DD
 * @param $date
 * @return mixed|string
 */
function p2d($date) {
    return pickerToDate($date);
}

/**
 * @param $date
 * @return mixed|string
 */
function pickerToDate($date) {
    if(empty($date))
        return '';
    return str_replace('/', '-', $date);
}

/**
 * 時間変換 for Picker
 * HH:MM:SS => HH:MM PM
 * @param $time
 * @return null|string
 */
function t2p($time) {
    return timeToPicker($time);
}

/**
 * @param $time
 * @return null|string
 */
function timeToPicker($time) {
    if(empty($time))
        return null;
    $ret = $time;
    $times = explode(':', $time);
    if(count($times) === 3) {
        $hh = $times[0];
        if($hh > 12) {
            $hh -= 12;
            $opt = ' PM';
        } else {
            $opt = ' AM';
        }
        $hh = sprintf("%02d", $hh);
        $mm = sprintf("%02d", $times[1]);
        $ret = $hh . ':' . $mm . $opt;
    }
    return $ret;
}

/**
 * 時間変換
 * HH:MM:SS => HH:MM
 * @param $time
 * @return null|string
 */
function t2s($time) {
    return timeToStime($time);
}

/**
 * @param $time
 * @return null|string
 */
function timeToStime($time) {
    if(empty($time))
        return null;
    $ret = $time;
    $times = explode(':', $time);
    if(count($times) === 3) {
        $hh = $times[0];
        $hh = sprintf("%02d", $hh);
        $mm = sprintf("%02d", $times[1]);
        $ret = $hh . ':' . $mm;
    }
    return $ret;
}

/**
 * 時間変換 for Picker
 * HH:MM PM => HH:MM:00
 * @param $time
 * @return string
 */
function p2t($time) {
    return timeFromPicker($time);
}

/**
 * @param $time
 * @return null|string
 */
function timeFromPicker($time) {
    if(empty($time))
        return null;
    $ret = $time;
    $times = explode(':', $time);
    if(count($times) === 2) {
        $hh = $times[0];
        $opts = explode(' ', $times[1]);
        $mm = '';
        $opt = '';
        if(count($opts) === 2) {
            $mm = $opts[0];
            $opt = $opts[1];
        }
        if($opt === 'PM') {
            $hh += 12;
        }
        $hh = sprintf("%02d", $hh);
        $mm = sprintf("%02d", $mm);
        $ret = $hh . ':' . $mm . ':00';
    }
    return $ret;
}

/**
 * 時間変換
 * HH:MM => HH:MM:00
 * @param $time
 * @return null|string
 */
function s2t($time) {
    return timeFromStime($time);
}

/**
 * @param $time
 * @return null|string
 */
function timeFromStime($time) {
    if(empty($time))
        return null;
    $ret = $time;
    $times = explode(':', $time);
    if(count($times) === 2) {
        $hh = $times[0];
        $mm = $times[1];
        $hh = sprintf("%02d", $hh);
        $mm = sprintf("%02d", $mm);
        $ret = $hh . ':' . $mm . ':00';
        if($hh > 23 || $hh < 0)
            $ret = null;
        if($mm > 59 || $mm < 0)
            $ret = null;
    }
    return $ret;
}

/**
 * 日付を取得
 * yyyy-mm-dd
 * -1,-7,+1,+7
 * @param $date
 * @param $add
 * @return bool|string
 */
function calcDate($date, $add) {
    if(empty($date))
        return $date;
    $ret = date("Y-m-d", strtotime("{$date} {$add} day"));
    return $ret;
}

/**
 * @return bool
 */
function is_secure() {
    //$host = $_SERVER['HTTP_HOST'];
    //switch ( $host ) {
    //    default:
    //        return false;
    //}
    // 冗長化している場合（AWS）は"HTTP_X_FORWARDED_PROTO"で判断
    if(!empty($_SERVER["HTTP_X_FORWARDED_PROTO"])) {
        if($_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") {
            return true;
        }
    } else if( !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) {
        // 通常のパターン
        return true;
    }
    return false;
}

/**
 * @return string
 */
function get_current_url() {
    if(is_secure()) {
        //'https'
        $url = 'https://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    } else {
        // 'http'
        $url = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    }
    return $url;
}



/**
 * HTMLをCURLで取得し、表示する
 * @param $url
 */
function include_html($url) {
    if(is_secure()) {
        //'https'
        $url = 'https://'.$_SERVER['SERVER_NAME'].$url;
    } else {
        // 'http'
        $url = 'http://'.$_SERVER['SERVER_NAME'].$url;
    }

    $ch = curl_init($url);

    // HTTPヘッダを出力しない
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    // 返り値を文字列として受け取る
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // HTTPステータスコード400の以上の場合も何も処理しない
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    //Redirectしても取得する
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10);
    //CA証明書の検証をしない
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    //$USERNAME = "studioelc";
    //$PASSWORD = "work5963";
    //curl_setopt($ch, CURLOPT_USERPWD, $USERNAME . ":" . $PASSWORD);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //$_SERVER['HTTP_USER_AGENT']
    if(!empty($_SERVER['HTTP_USER_AGENT'])) {
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }

    // サイトへアクセス
    $result = curl_exec($ch);
    //$result = str_replace('</span><br />', '</span>', $result);

    echo $result;
}

/**
 * HTMLをCURLで取得
 * @param $url
 *
 * @return mixed
 */
function get_html($url) {
    $host = $_SERVER['HTTP_HOST'];
    switch ( $host ) {
        case 'umobile.local':	// ローカル開発環境
            $url = 'http://'.$_SERVER['SERVER_NAME'].$url;
            break;
        default:
            $url = 'http://localhost'.$url;
            break;
    }

    $ch = curl_init($url);

    // HTTPヘッダを出力しない
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    // 返り値を文字列として受け取る
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // HTTPステータスコード400の以上の場合も何も処理しない
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    //Redirectしても取得する
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10);
    //CA証明書の検証をしない
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    //$USERNAME = "studioelc";
    //$PASSWORD = "work5963";
    //curl_setopt($ch, CURLOPT_USERPWD, $USERNAME . ":" . $PASSWORD);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //$_SERVER['HTTP_USER_AGENT']
    if(!empty($_SERVER['HTTP_USER_AGENT'])) {
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    }

    // サイトへアクセス
    $result = curl_exec($ch);
    //$result = str_replace('</span><br />', '</span>', $result);

    return $result;
}

function getUri() {
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
    {
        //'https'
        $url = 'https://';
    } else {
        // 'http'
        $url = 'http://';
    }
    $url .= $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];

    return $url;
}

/**
 * 住所から緯度・経度を配列で取得
 * @param $strAddress
 *
 * @return array
 */
function getLatLng( $strAddress )
{
    $latLng = array();

    //$strUrl = 'http://maps.google.com/maps/api/geocode/json'
    //          . '?address=' . urlencode( mb_convert_encoding( trim($strAddress), 'UTF-8' ) )
    //          . '&sensor=false';
    $strUrl = 'http://maps.google.com/maps/api/geocode/json'
              . '?address=' . trim($strAddress)
              . '&sensor=false';

    $ch = curl_init($strUrl);

    // HTTPヘッダを出力しない
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    // 返り値を文字列として受け取る
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // HTTPステータスコード400の以上の場合も何も処理しない
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    //Redirectしても取得する
    //curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    //curl_setopt( $ch, CURLOPT_MAXREDIRS, 10);
    //curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE);

    $response = curl_exec($ch);
    curl_close($ch);
    $aryGeo = json_decode($response, TRUE );
    if ( !isset( $aryGeo['results'][0] ) )
        return $latLng;
    $strLat = (string)$aryGeo['results'][0]['geometry']['location']['lat'];
    $strLng = (string)$aryGeo['results'][0]['geometry']['location']['lng'];

    $latLng['lat'] = $strLat;
    $latLng['lng'] = $strLng;
    return $latLng;

}

/**
 * 住所から緯度・経度をPOINTで取得
 * @param $strAddress
 *
 * @return string
 */
function strAddressToLatLng( $strAddress )
{
    $latLng = getLatLng( $strAddress );
    if(empty($latLng)) {
        return '';
    }
    $strLat = $latLng['lat'];
    $strLng = $latLng['lng'];
    $strLatLng = "POINT({$strLng} {$strLat})";
    return $strLatLng;

}

/**
 * @param $address
 *
 * @return array
 */
function splitAddress($address) {
    $matches = array();
    preg_match('/(東京都|北海道|(?:京都|大阪)府|.{6,9}県)((?:四日市|廿日市|野々市|臼杵|かすみがうら|つくばみらい|いちき串木野)市|(?:杵島郡大町|余市郡余市|高市郡高取)町|.{3,12}市.{3,12}区|.{3,9}区|.{3,15}市(?=.*市)|.{3,15}市|.{6,27}町(?=.*町)|.{6,27}町|.{9,24}村(?=.*村)|.{9,24}村)(.*)/', $address, $matches);
    return $matches;
}

/**
 * CSVファイルを配列に変換
 * @param $csv
 *
 * @return array
 */
function csvToArray($csv) {
    if(!isset($_FILES[$csv])) {
        return array();
    } else {
        if($_FILES[$csv]['error'] !== UPLOAD_ERR_OK) {
            //エラーが発生している
            if($_FILES[$csv]['error'] == UPLOAD_ERR_NO_FILE) {
                //echo 'ファイルが選択されていません。';
                return array();
                //return false;
            } elseif($_FILES[$csv]['error'] == UPLOAD_ERR_FORM_SIZE) {
                //echo 'ファイルサイズがHTMLで指定した MAX_FILE_SIZE を超えています。';
                return array();
            } else {
                //echo 'その他のエラーが発生しています。';
                //echo $_FILES[$csv]['error'];
                //var_dump($_FILES[$csv]);
                return array();
            }
        } else {

            //ユーザーが指定したファイル名
            //$myfile_name = $_FILES[$csv]['name'];
            //ファイルのMIME型
            //$myfile_type = $_FILES[$csv]['type'];
            //ファイルサイズ
            //$myfile_size = $_FILES[$csv]['size'];
            //アップロードしたファイルが保存されている一時保存場所
            $file_tmp_path = $_FILES[$csv]['tmp_name'];

            $file = new SplFileObject($file_tmp_path);
            $file->setFlags(SplFileObject::READ_CSV);

            $array = array();
            foreach ($file as $line) {
                //
                if (empty($line)) continue;
                $array[] = $line;
            }
            //mb_language("Japanese");
            //mb_convert_variables("UTF-8", array("ASCII","JIS","UTF-8","EUC-JP","SJIS-win","SJIS"), $array);
            return $array;

        }
    }

}

/**
 * CSVデータを登録
 * @param $array
 * @param $start
 * @param $max
 * @param $step
 *
 * @return array
 */
function uploadSpot($array, $start, $max, $step = 1) {
    set_time_limit( 0 );
    $spot_table = param('spot_table');

    $oks = array();
    $errors = array();

    if(empty($array)) return null;

    @ob_flush();
    @flush();

    for ($i = 0; $i < $step; $i++) {
        $index = $i + $start;
        if($index >= $max) break;
        $data = $array[$index];
        $c = count($data);
        if($c<2) continue;

        $spot_area_id = '';
        if(!empty($data[$spot_table['spot_area_id']]))
            $spot_area_id = $data[$spot_table['spot_area_id']];
        $spot_name = '';
        if(!empty($data[$spot_table['spot_name']])) {
            $spot_name = $data[$spot_table['spot_name']];
            $spot_name = str_replace("'","’",$spot_name);
        }

        if($spot_area_id === 'エリアID') continue;
        if(empty($spot_name)) continue;

        //名前で削除 2017.02.08 Add
        Spot::model()->deleteAllByAttributes(array('spot_name' => $spot_name, 'spot_dospot' => 0));
        
        $spot = Spot::model()->findByAttributes(array('spot_name' => $spot_name));
        if(empty($spot)) {
            //New
            $spot = new Spot;
        }

        $spot->spot_area_id = $spot_area_id;
        $spot->spot_section_name = '';
        if(!empty($data[$spot_table['spot_section_name']]))
            $spot->spot_section_name = $data[$spot_table['spot_section_name']];
        $spot->spot_name = '';
        if(!empty($data[$spot_table['spot_name']]))
            $spot->spot_name = $spot_name;
        $spot->spot_category = '';
        if(!empty($data[$spot_table['spot_category']]))
            $spot->spot_category = $data[$spot_table['spot_category']];
        //カテゴリIDを取得
        $category = Category::model()->findByAttributes(array('category_name' => $spot->spot_category));
        if(empty($category)) {
            //カテゴリが存在しない場合は追加
            $category = new Category;
            $category->category_name = $spot->spot_category;
            $category->category_rank = 999;
            $category->category_icon = 'other.png';
            if($category->save()) {
                $category->category_rank = $category->category_id;
                $category->save();
            }
        } else {
            $spot->spot_category_id = $category->category_id;
        }
        $spot->spot_east = '';
        if(!empty($data[$spot_table['spot_east']]))
            $spot->spot_east = $data[$spot_table['spot_east']];
        $spot->spot_prefecture = '';
        if(!empty($data[$spot_table['spot_prefecture']]))
            $spot->spot_prefecture = $data[$spot_table['spot_prefecture']];
        $spot->spot_area = '';
        if(!empty($data[$spot_table['spot_area']]))
            $spot->spot_area = $data[$spot_table['spot_area']];
        $spot->spot_address = '';
        if(!empty($data[$spot_table['spot_address']]))
            $spot->spot_address = $data[$spot_table['spot_address']];
        $spot->spot_address = str_replace("'","’",$spot->spot_address);
        //住所を都道府県、市区町村に分解
        $address = splitAddress($spot->spot_address);
        if(!empty($address[1])) {
            $prefecture = Prefecture::model()->findByAttributes(array('prefecture_name' => $address[1]));
            if(!empty($prefecture)) {
                if(!empty($address[2])) {
                    $city = City::model()->findByAttributes(array('city_name' => $address[2]));
                    if(empty($city)) {
                        //市区町村が存在しない場合は追加
                        //市区町村IDを設定
                        $city = new City;
                        $city->city_prefecture_id = $prefecture->prefecture_id;
                        $city->city_name = $address[2];
                        $city->save();
                    }
                    if(!empty($city->city_id)) {
                        $spot->spot_city_id = $city->city_id;
                    }
                }
            }
        }
        $spot->spot_ap_count = '';
        if(!empty($data[$spot_table['spot_ap_count']]))
            $spot->spot_ap_count = $data[$spot_table['spot_ap_count']];
        $spot->spot_location = '';
        if(!empty($data[$spot_table['spot_location']]))
            $spot->spot_location = $data[$spot_table['spot_location']];
        $strLatLng = array();
        $strLatLng['lat'] = '';
        if(!empty($data[$spot_table['spot_lat']]))
            $strLatLng['lat'] = $data[$spot_table['spot_lat']];
        $strLatLng['lng'] = '';
        if(!empty($data[$spot_table['spot_lng']]))
            $strLatLng['lng'] = $data[$spot_table['spot_lng']];
        //$strLatLng = "POINT({$spot_geometry_lng} {$spot_geometry_lat})";

        $mod_address = mb_convert_kana($spot->spot_address, "nhk", "utf-8");
        if(!empty($strLatLng['lat'])) {
            if($strLatLng['lat']<20) {
                $strLatLng['lat'] = '';
                $strLatLng['lng'] = '';
            }
        }
        if(!empty($strLatLng['lng'])) {
            if($strLatLng['lng']<120) {
                $strLatLng['lat'] = '';
                $strLatLng['lng'] = '';
            }
        }

        if(empty($strLatLng['lat']) || empty($strLatLng['lng'])) {
            $spot_address = explode(' ', $mod_address);
            if(!empty($spot_address[0])) {
                $tmp_address = $spot_address[0];
                $len = mb_strlen($tmp_address, 'utf-8');
                while($len>0){
                    $tmp_address = mb_substr($tmp_address, 0, $len, "utf-8");
                    //緯度経度取得
                    //住所から緯度・経度を取得
                    $adds = Dm_Geocoder::geocode($tmp_address);
                    if(count($adds) > 0) {
                        $lat = $adds[0]->lat;
                        $lng = $adds[0]->lng;
                        $strLatLng = array();
                        $strLatLng['lat'] = $lat;
                        $strLatLng['lng'] = $lng;
                    } else {
                        $strLatLng = getLatLng($tmp_address);
                    }
                    if(empty($strLatLng['lat']) ||
                       empty($strLatLng['lng'])) {
                        //NGなら
                        $len -= 1;
                    } else {
                        if($strLatLng['lat']<20 ||
                           $strLatLng['lng']<0  ||
                           $strLatLng['lng']<120  ||
                           $strLatLng['lat']<0) {
                            //NGなら
                            $len -= 1;
                        } else {
                            break;
                        }
                    }
                }
            }
            if(empty($strLatLng)) {
                //取得エラー
                //店名から緯度・経度を取得
                $strLatLng = getLatLng($spot->spot_name);
            }
        }
        if(!empty($strLatLng['lat'])) {
            $spot->spot_lat = $strLatLng['lat'];
        }
        if(!empty($strLatLng['lng'])) {
            $spot->spot_lng = $strLatLng['lng'];
        }
        //$spot->spot_geometry = new CDbExpression("GeomFromText(:point)",array('point' => $strLatLng));

        $spot->spot_dospot = 0;

        $spot->delete_flag = 0;

        $error = array();
        if(!$spot->validate()) {
            $e = $spot->getErrors();
        }
        $err = Spot::model()->userValidate($spot);
        if(empty($err)) {
            //OK
            $spot = Spot::model()->saveData($spot);
            if($spot->hasErrors()) {
                //NG
                $error['index'] = $index;
                //$error['error'] = CHtml::errorSummary($spot);
                $error['error'] = $spot->getErrors();
                $errors[] = $error;
            } else {
                $oks[] = $index;
            }
        } else {
            foreach($err as $key=>$msg) {
                $spot->addError($key, $msg);
            }
            $error['index'] = $index;
            //$error['error'] = CHtml::errorSummary($spot);
            $error['error'] = $spot->getErrors();
            $errors[] = $error;
        }
    }
    $ret = array();
    $ret['OK'] = $oks;
    $ret['ERROR'] = $errors;
    return $ret;
}

/**
 * DoSPOT CSVデータを登録
 * @param $array
 * @param $start
 * @param $max
 * @param $step
 *
 * @return array
 * 
 * $address = $spot->spot_prefecture . $address1 . $address2 . ' ' . $address3
 */
function uploadDoSpot($array, $start, $max, $step = 1) {
    set_time_limit( 0 );
    $spot_table = param('do_spot_table');

    $oks = array();
    $errors = array();

    if(empty($array)) return null;

    @ob_flush();
    @flush();

    for ($i = 0; $i < $step; $i++) {
        $index = $i + $start;
        if($index >= $max) break;
        $data = $array[$index];
        $c = count($data);
        if($c<2) continue;

        $spot_name = '';
        if(!empty($data[$spot_table['spot_name']])) {
            $spot_name = $data[$spot_table['spot_name']];
            $spot_name = str_replace("'","’",$spot_name);
        }

        if(empty($spot_name)) continue;
        if($spot_name === '店舗名') continue;

        //名前で削除 2017.02.08 Add
        Spot::model()->deleteAllByAttributes(array('spot_name' => $spot_name, 'spot_dospot' => 1));
        
        //店名＋住所 で検索
        $prefecture = '';
        $address1 = '';
        $address2 = '';
        $address3 = '';
        if(!empty($data[$spot_table['spot_prefecture']]))
            $prefecture = $data[$spot_table['spot_prefecture']];
        if(!empty($data[$spot_table['spot_address1']]))
            $address1 = $data[$spot_table['spot_address1']];
        if(!empty($data[$spot_table['spot_address2']]))
            $address2 = $data[$spot_table['spot_address2']];
        if(!empty($data[$spot_table['spot_address3']]))
            $address3 = $data[$spot_table['spot_address3']];
        $address = $prefecture . $address1 . $address2 . ' ' . $address3;
        $spot = Spot::model()->findByAttributes(array('spot_name' => $spot_name,
                                                      'spot_address' => $address));
        if(empty($spot)) {
            //New
            $spot = new Spot;
        }

        $spot->spot_area_id = '';
        $spot->spot_section_name = '';
        $spot->spot_name = '';
        if(!empty($data[$spot_table['spot_name']]))
            $spot->spot_name = $spot_name;
        $spot->spot_name = strreplace("'", "’", $spot->spot_name);
        $spot->spot_category = 'Do SPOT';
        //カテゴリIDを取得
        $category = Category::model()->findByAttributes(array('category_name' => $spot->spot_category));
        if(empty($category)) {
            //カテゴリが存在しない場合は追加
            $category = new Category;
            $category->category_name = $spot->spot_category;
            $category->category_rank = 999;
            $category->category_icon = 'other.png';
            if($category->save()) {
                $category->category_rank = $category->category_id;
                $category->save();
            }
        } else {
            $spot->spot_category_id = $category->category_id;
        }
        $spot->spot_east = '';
        $spot->spot_prefecture = '';
        if(!empty($data[$spot_table['spot_prefecture']]))
            $spot->spot_prefecture = $data[$spot_table['spot_prefecture']];
        $spot->spot_area = '';
        $spot->spot_address = $spot->spot_prefecture . $address1 . $address2 . ' ' . $address3;

        $spot->spot_address = strreplace("'", "’", $spot->spot_address);
        //住所を都道府県、市区町村に分解
        $address = splitAddress($spot->spot_address);
        if(!empty($address[1])) {
            $prefecture = Prefecture::model()->findByAttributes(array('prefecture_name' => $address[1]));
            if(!empty($prefecture)) {
                if(!empty($address[2])) {
                    $city = City::model()->findByAttributes(array('city_name' => $address[2]));
                    if(empty($city)) {
                        //市区町村が存在しない場合は追加
                        //市区町村IDを設定
                        $city = new City;
                        $city->city_prefecture_id = $prefecture->prefecture_id;
                        $city->city_name = $address[2];
                        $city->save();
                    }
                    if(!empty($city->city_id)) {
                        $spot->spot_city_id = $city->city_id;
                    }
                }
            }
        }
        $spot->spot_ap_count = '';
        $spot->spot_location = '';

        $strLatLng = array();
        $strLatLng['lat'] = '';
        if(!empty($data[$spot_table['spot_lat']]))
            $strLatLng['lat'] = $data[$spot_table['spot_lat']];
        $strLatLng['lng'] = '';
        if(!empty($data[$spot_table['spot_lng']]))
            $strLatLng['lng'] = $data[$spot_table['spot_lng']];
        //$strLatLng = "POINT({$spot_geometry_lng} {$spot_geometry_lat})";

        $mod_address = mb_convert_kana($spot->spot_address, "nhk", "utf-8");
        if(!empty($strLatLng['lat'])) {
            if($strLatLng['lat']<20) {
                $strLatLng['lat'] = '';
                $strLatLng['lng'] = '';
            }
        }
        if(!empty($strLatLng['lng'])) {
            if($strLatLng['lng']<120) {
                $strLatLng['lat'] = '';
                $strLatLng['lng'] = '';
            }
        }

        if(empty($strLatLng['lat']) || empty($strLatLng['lng'])) {
            $spot_address = explode(' ', $mod_address);
            if(!empty($spot_address[0])) {
                $tmp_address = $spot_address[0];
                $len = mb_strlen($tmp_address, 'utf-8');
                while($len>0){
                    $tmp_address = mb_substr($tmp_address, 0, $len, "utf-8");
                    //緯度経度取得
                    //住所から緯度・経度を取得
                    $adds = Dm_Geocoder::geocode($tmp_address);
                    if(count($adds) > 0) {
                        $lat = $adds[0]->lat;
                        $lng = $adds[0]->lng;
                        $strLatLng = array();
                        $strLatLng['lat'] = $lat;
                        $strLatLng['lng'] = $lng;
                    } else {
                        $strLatLng = getLatLng($tmp_address);
                    }
                    if(empty($strLatLng['lat']) ||
                       empty($strLatLng['lng'])) {
                        //NGなら
                        $len -= 1;
                    } else {
                        if($strLatLng['lat']<20 ||
                           $strLatLng['lng']<0  ||
                           $strLatLng['lng']<120  ||
                           $strLatLng['lat']<0) {
                            //NGなら
                            $len -= 1;
                        } else {
                            break;
                        }
                    }
                }
            }
            if(empty($strLatLng)) {
                //取得エラー
                //店名から緯度・経度を取得
                $strLatLng = getLatLng($spot->spot_name);
            }
        }
        if(!empty($strLatLng['lat'])) {
            $spot->spot_lat = $strLatLng['lat'];
        }
        if(!empty($strLatLng['lng'])) {
            $spot->spot_lng = $strLatLng['lng'];
        }
        //$spot->spot_geometry = new CDbExpression("GeomFromText(:point)",array('point' => $strLatLng));

        $spot->spot_dospot = 1;

        $spot->delete_flag = 0;

        $error = array();
        if(!$spot->validate()) {
            $e = $spot->getErrors();
        }
        $err = Spot::model()->userValidate($spot);
        if(empty($err)) {
            //OK
            $spot = Spot::model()->saveData($spot);
            if($spot->hasErrors()) {
                //NG
                $error['index'] = $index;
                //$error['error'] = CHtml::errorSummary($spot);
                $error['error'] = $spot->getErrors();
                $errors[] = $error;
            } else {
                $oks[] = $index;
            }
        } else {
            foreach($err as $key=>$msg) {
                $spot->addError($key, $msg);
            }
            $error['index'] = $index;
            //$error['error'] = CHtml::errorSummary($spot);
            $error['error'] = $spot->getErrors();
            $errors[] = $error;
        }
    }
    $ret = array();
    $ret['OK'] = $oks;
    $ret['ERROR'] = $errors;
    return $ret;
}

/**
 * DoSPOT CSVデータを登録
 * @param $array
 * @param $start
 * @param $max
 * @param $step
 *
 * @return array
 *
 * $address = $spot->spot_prefecture . $address1 . $address2 . ' ' . $address3
 */
function uploadUsenSpot($array, $start, $max, $step = 1) {
    set_time_limit( 0 );
    $spot_table = param('usen_spot_table');

    $oks = array();
    $errors = array();

    if(empty($array)) return null;

    @ob_flush();
    @flush();

    for ($i = 0; $i < $step; $i++) {
        $index = $i + $start;
        if($index >= $max) break;
        $data = $array[$index];
        $c = count($data);
        if($c<2) continue;

        $spot_area_id = '';
        if(!empty($data[$spot_table['spot_area_id']]))
            $spot_area_id = $data[$spot_table['spot_area_id']];
        $spot_name = '';
        if(!empty($data[$spot_table['spot_name']])) {
            $spot_name = $data[$spot_table['spot_name']];
            $spot_name = str_replace("'", "’", $spot_name);
        }

        if($spot_area_id === '顧客CD') continue;
        if(empty($spot_name)) continue;

        //顧客CDで削除
        Spot::model()->deleteAllByAttributes(array('spot_area_id' => $spot_area_id));

        //顧客CD + 名称 + dospot = 2 で検索
        $spot = Spot::model()->findByAttributes(array('spot_area_id' => $spot_area_id,'spot_name' => $spot_name, 'spot_dospot'=>2 ));
        if(empty($spot)) {
            //New
            $spot = new Spot;
        }

        $spot->spot_area_id = $spot_area_id;
        $spot->spot_name = '';
        if(!empty($data[$spot_table['spot_name']]))
            $spot->spot_name = $spot_name;
        $spot->spot_category = '';
        if(!empty($data[$spot_table['spot_category']]))
            $spot->spot_category = $data[$spot_table['spot_category']];
        //カテゴリIDを取得
        $category = Category::model()->findByAttributes(array('category_name' => $spot->spot_category));
        if(empty($category)) {
            //カテゴリが存在しない場合は追加
            $category = new Category;
            $category->category_name = $spot->spot_category;
            $category->category_rank = 999;
            $category->category_icon = 'other.png';
            if($category->save()) {
                $category->category_rank = $category->category_id;
                $category->save();
            }
        } else {
            $spot->spot_category_id = $category->category_id;
        }
        $spot->spot_prefecture = '';
        if(!empty($data[$spot_table['spot_prefecture']]))
            $spot->spot_prefecture = $data[$spot_table['spot_prefecture']];
        $spot->spot_address = '';
        if(!empty($data[$spot_table['spot_address']]))
            $spot->spot_address = $data[$spot_table['spot_address']];
        if(!empty($spot->spot_prefecture) && !empty($spot->spot_address)) {
            $spot->spot_address = $spot->spot_prefecture.$spot->spot_address;
        } else {
            continue;
        }
        $spot->spot_address = str_replace("'", "’", $spot->spot_address);

        //住所を都道府県、市区町村に分解
        $address = splitAddress($spot->spot_address);
        if(!empty($address[1])) {
            $prefecture = Prefecture::model()->findByAttributes(array('prefecture_name' => $address[1]));
            if(!empty($prefecture)) {
                if(!empty($address[2])) {
                    $city = City::model()->findByAttributes(array('city_name' => $address[2]));
                    if(empty($city)) {
                        //市区町村が存在しない場合は追加
                        //市区町村IDを設定
                        $city = new City;
                        $city->city_prefecture_id = $prefecture->prefecture_id;
                        $city->city_name = $address[2];
                        $city->save();
                    }
                    if(!empty($city->city_id)) {
                        $spot->spot_city_id = $city->city_id;
                    }
                }
            }
        }
        $spot->spot_ap_count = '';
        if(!empty($data[$spot_table['spot_ap_count']]))
            $spot->spot_ap_count = $data[$spot_table['spot_ap_count']];
        $strLatLng = array();
        $strLatLng['lat'] = '';
        if(!empty($data[$spot_table['spot_lat']]))
            $strLatLng['lat'] = $data[$spot_table['spot_lat']];
        $strLatLng['lng'] = '';
        if(!empty($data[$spot_table['spot_lng']]))
            $strLatLng['lng'] = $data[$spot_table['spot_lng']];
        //$strLatLng = "POINT({$spot_geometry_lng} {$spot_geometry_lat})";

        $mod_address = mb_convert_kana($spot->spot_address, "nhk", "utf-8");
        if(!empty($strLatLng['lat'])) {
            if($strLatLng['lat']<20) {
                $strLatLng['lat'] = '';
                $strLatLng['lng'] = '';
            }
        }
        if(!empty($strLatLng['lng'])) {
            if($strLatLng['lng']<120) {
                $strLatLng['lat'] = '';
                $strLatLng['lng'] = '';
            }
        }
        if(empty($strLatLng['lat']) || empty($strLatLng['lng'])) {
            $spot_address = explode(' ', $mod_address);
            if(!empty($spot_address[0])) {
                $tmp_address = $spot_address[0];
                $len = mb_strlen($tmp_address, 'utf-8');
                while($len>0){
                    $tmp_address = mb_substr($tmp_address, 0, $len, "utf-8");
                    //緯度経度取得
                    //住所から緯度・経度を取得
                    $adds = Dm_Geocoder::geocode($tmp_address);
                    if(count($adds) > 0) {
                        $lat = $adds[0]->lat;
                        $lng = $adds[0]->lng;
                        $strLatLng = array();
                        $strLatLng['lat'] = $lat;
                        $strLatLng['lng'] = $lng;
                    } else {
                        $strLatLng = getLatLng($tmp_address);
                    }
                    if(empty($strLatLng['lat']) ||
                       empty($strLatLng['lng'])) {
                        //NGなら
                        $len -= 1;
                    } else {
                        if($strLatLng['lat']<20 ||
                           $strLatLng['lng']<0  ||
                           $strLatLng['lng']<120  ||
                           $strLatLng['lat']<0) {
                            //NGなら
                            $len -= 1;
                        } else {
                            break;
                        }
                    }
                }
            }
            if(empty($strLatLng)) {
                //取得エラー
                //店名から緯度・経度を取得
                $strLatLng = getLatLng($spot->spot_name);
            }
        }
        if(!empty($strLatLng['lat'])) {
            $spot->spot_lat = $strLatLng['lat'];
        }
        if(!empty($strLatLng['lng'])) {
            $spot->spot_lng = $strLatLng['lng'];
        }
        //$spot->spot_geometry = new CDbExpression("GeomFromText(:point)",array('point' => $strLatLng));

        $spot->spot_dospot = 2;

        $spot->delete_flag = 0;

        $error = array();
        if(!$spot->validate()) {
            $e = $spot->getErrors();
        }
        $err = Spot::model()->userValidate($spot);
        if(empty($err)) {
            //OK
            $spot = Spot::model()->saveData($spot);
            if($spot->hasErrors()) {
                //NG
                $error['index'] = $index;
                //$error['error'] = CHtml::errorSummary($spot);
                $error['error'] = $spot->getErrors();
                $errors[] = $error;
            } else {
                $oks[] = $index;
            }
        } else {
            foreach($err as $key=>$msg) {
                $spot->addError($key, $msg);
            }
            $error['index'] = $index;
            //$error['error'] = CHtml::errorSummary($spot);
            $error['error'] = $spot->getErrors();
            $errors[] = $error;
        }
    }
    $ret = array();
    $ret['OK'] = $oks;
    $ret['ERROR'] = $errors;
    return $ret;
}

/**
 * CSVデータを登録
 * @param $array
 * @param $start
 * @param $max
 * @param $step
 *
 * @return array
 */
function uploadShop($array, $start, $max, $step = 1) {
    set_time_limit( 0 );
    $shop_table = param('shop_table');

    $oks = array();
    $errors = array();

    if(empty($array)) return null;

    @ob_flush();
    @flush();

    for ($i = 0; $i < $step; $i++) {
        $index = $i + $start;
        if($index >= $max) break;
        $data = $array[$index];
        $c = count($data);
        if($c<2) continue;

        $shop_name = '';
        if(!empty($data[$shop_table['shop_name']])) {
            $str = mb_convert_encoding($data[$shop_table['shop_name']],"utf-8","sjis-win");
            $shop_name = $str;
        }

        if(empty($shop_name) || $shop_name=='店舗名') {
            continue;
        }

        $shop = Shop::model()->findByAttributes(array('shop_name' => $shop_name));
        if(empty($shop)) {
            //New
            $shop = new Shop;
        }

        $shop->shop_name = '';
        if(!empty($data[$shop_table['shop_name']]))
            $shop->shop_name = $shop_name;
        $shop->shop_name = preg_replace("/\r\n|\r|\n/", " ", $shop->shop_name);

        if(!empty($data[$shop_table['shop_kana']])) {
            $str = mb_convert_encoding($data[$shop_table['shop_kana']],"utf-8","sjis-win");
            $shop->shop_kana = $str;
        }
        if(!empty($data[$shop_table['shop_sim']])) {
            $str = mb_convert_encoding($data[$shop_table['shop_sim']],"utf-8","sjis-win");
            $shop->shop_sim = $str;
        }

        if(!empty($data[$shop_table['shop_phone']])) {
            $str = mb_convert_encoding($data[$shop_table['shop_phone']],"utf-8","sjis-win");
            $shop->shop_phone = $str;
        }
        if(!empty($data[$shop_table['shop_hours']])) {
            $str = mb_convert_encoding($data[$shop_table['shop_hours']],"utf-8","sjis-win");
            $shop->shop_hours = $str;
        }

        //カテゴリー(駐車場有り)
        $category_ids = array();
        if(!empty($data[$shop_table['shop_category']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_category']],"utf-8","sjis-win");
            if($item=='●') {
                $item = '駐車場有り';
                //カテゴリIDを取得
                $category = ShopCategoryMaster::model()->findByAttributes(array('category_name' => $item));
                if(empty($category)) {
                    //カテゴリが存在しない場合は追加
                    $category = new ShopCategoryMaster;
                    $category->category_name = $item;
                    $category->category_rank = 999;
                    $category->category_icon = 'other.png';
                    if($category->save()) {
                        $category->category_rank = $category->category_id;
                        $category->save();
                    }
                } else {
                    $category_ids[] = $category->category_id;
                }
            }
        }
        $services = '';
        $service_ids = array();
        //サービス1(U)
        if(!empty($data[$shop_table['shop_service1']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_service1']],"utf-8","sjis-win");
            if($item=='●') {
                $item = 'U';
                //サービスIDを取得
                $service = ShopServiceMaster::model()->findByAttributes(array('service_name' => $item));
                if(empty($service)) {
                    //サービスが存在しない場合は追加
                    $service = new ShopServiceMaster;
                    $service->service_name = $item;
                    $service->service_rank = 999;
                    $service->service_icon = 'other.png';
                    if($service->save()) {
                        $service->service_rank = $service->service_id;
                        $service->save();
                    }
                } else {
                    $service_ids[] = $service->service_id;
                }
            }
        }
        //サービス2(M)
        if(!empty($data[$shop_table['shop_service2']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_service2']],"utf-8","sjis-win");
            if($item=='●') {
                $item = 'M';
                //サービスIDを取得
                $service = ShopServiceMaster::model()->findByAttributes(array('service_name' => $item));
                if(empty($service)) {
                    //サービスが存在しない場合は追加
                    $service = new ShopServiceMaster;
                    $service->service_name = $item;
                    $service->service_rank = 999;
                    $service->service_icon = 'other.png';
                    if($service->save()) {
                        $service->service_rank = $service->service_id;
                        $service->save();
                    }
                } else {
                    $service_ids[] = $service->service_id;
                }
            }
        }
        //サービス3(S)
        if(!empty($data[$shop_table['shop_service3']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_service3']],"utf-8","sjis-win");
            if($item=='●') {
                $item = 'S';
                //サービスIDを取得
                $service = ShopServiceMaster::model()->findByAttributes(array('service_name' => $item));
                if(empty($service)) {
                    //サービスが存在しない場合は追加
                    $service = new ShopServiceMaster;
                    $service->service_name = $item;
                    $service->service_rank = 999;
                    $service->service_icon = 'other.png';
                    if($service->save()) {
                        $service->service_rank = $service->service_id;
                        $service->save();
                    }
                } else {
                    $service_ids[] = $service->service_id;
                }
            }
        }
        //サービス4(P)
        if(!empty($data[$shop_table['shop_service4']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_service4']],"utf-8","sjis-win");
            if($item=='●') {
                $item = 'P';
                //サービスIDを取得
                $service = ShopServiceMaster::model()->findByAttributes(array('service_name' => $item));
                if(empty($service)) {
                    //サービスが存在しない場合は追加
                    $service = new ShopServiceMaster;
                    $service->service_name = $item;
                    $service->service_rank = 999;
                    $service->service_icon = 'other.png';
                    if($service->save()) {
                        $service->service_rank = $service->service_id;
                        $service->save();
                    }
                } else {
                    $service_ids[] = $service->service_id;
                }
            }
        }
        //サービス5(PS)
        if(!empty($data[$shop_table['shop_service5']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_service5']],"utf-8","sjis-win");
            if($item=='●') {
                $item = 'PS';
                //サービスIDを取得
                $service = ShopServiceMaster::model()->findByAttributes(array('service_name' => $item));
                if(empty($service)) {
                    //サービスが存在しない場合は追加
                    $service = new ShopServiceMaster;
                    $service->service_name = $item;
                    $service->service_rank = 999;
                    $service->service_icon = 'other.png';
                    if($service->save()) {
                        $service->service_rank = $service->service_id;
                        $service->save();
                    }
                } else {
                    $service_ids[] = $service->service_id;
                }
            }
        }
        //サービス6(S)
        if(!empty($data[$shop_table['shop_service6']])) {
            $item = mb_convert_encoding($data[$shop_table['shop_service6']],"utf-8","sjis-win");
            if($item=='●') {
                $item = 'SS';
                //サービスIDを取得
                $service = ShopServiceMaster::model()->findByAttributes(array('service_name' => $item));
                if(empty($service)) {
                    //サービスが存在しない場合は追加
                    $service = new ShopServiceMaster;
                    $service->service_name = $item;
                    $service->service_rank = 999;
                    $service->service_icon = 'other.png';
                    if($service->save()) {
                        $service->service_rank = $service->service_id;
                        $service->save();
                    }
                } else {
                    $service_ids[] = $service->service_id;
                }
            }
        }

        $shop->shop_address = '';
        if(!empty($data[$shop_table['shop_address']])) {
            $str = mb_convert_encoding($data[$shop_table['shop_address']],"utf-8","sjis-win");
            $shop->shop_address = $str;
        }
        $shop->shop_address = preg_replace("/\r\n|\r|\n/", " ", $shop->shop_address);

        //住所を都道府県、市区町村に分解
        $address = splitAddress($shop->shop_address);
        if(!empty($address[1])) {
            $prefecture = Prefecture::model()->findByAttributes(array('prefecture_name' => $address[1]));
            if(!empty($prefecture)) {
                if(!empty($address[2])) {
                    $city = ShopCity::model()->findByAttributes(array('city_name' => $address[2]));
                    if(empty($city)) {
                        //市区町村が存在しない場合は追加
                        //市区町村IDを設定
                        $city = new ShopCity;
                        $city->city_prefecture_id = $prefecture->prefecture_id;
                        $city->city_name = $address[2];
                        $city->save();
                    }
                    if(!empty($city->city_id)) {
                        $shop->shop_city_id = $city->city_id;
                    }
                }
            }
        }
        $strLatLng = array();
        $strLatLng['lat'] = '';
        if(!empty($data[$shop_table['shop_lat']]))
            $strLatLng['lat'] = $data[$shop_table['shop_lat']];
        $strLatLng['lng'] = '';
        if(!empty($data[$shop_table['shop_lng']]))
            $strLatLng['lng'] = $data[$shop_table['shop_lng']];
        if(empty($strLatLng['lat']) || empty($strLatLng['lng'])) {
            //住所から緯度・経度を取得
            $address1 = explode(' ', $shop->shop_address);
            if(!empty($address1[0])) {
                $address = $address1[0];
                $address2 = explode('　', $address);
                if(!empty($address2[0])) {
                    $address = $address2[0];
                    $address2 = explode('　', $address);
                    $address = $address2[0];
                }
            }
            if(empty($address)) {
                $address = $shop->shop_address;
            }
            if(is_array($address)) {
                $address = $address[0];
            }
            $strLatLng = getLatLng($address);
            if(empty($strLatLng)) {
                //取得エラー
                //店名から緯度・経度を取得
                $strLatLng = getLatLng($shop->shop_name);
            }
        }
        if(!empty($strLatLng['lat'])) {
            $shop->shop_lat = $strLatLng['lat'];
        }
        if(!empty($strLatLng['lng'])) {
            $shop->shop_lng = $strLatLng['lng'];
        }

        $shop->delete_flag = 0;

        $error = array();
        if(!$shop->validate()) {
            $e = $shop->getErrors();
        }
        $err = Shop::model()->userValidate($shop);
        if(empty($err)) {
            //OK
            $shop = Shop::model()->saveData($shop);
            if($shop->hasErrors()) {
                //NG
                $error['index'] = $index;
                //$error['error'] = CHtml::errorSummary($spot);
                $error['error'] = $shop->getErrors();
                $errors[] = $error;
            } else {
                $oks[] = $index;

                //カテゴリを保存
                ShopCategory::model()->saveData($shop->shop_id, $category_ids);

                //サービスを保存
                ShopService::model()->saveData($shop->shop_id, $service_ids);
            }
        } else {
            foreach($err as $key=>$msg) {
                $shop->addError($key, $msg);
            }
            $error['index'] = $index;
            //$error['error'] = CHtml::errorSummary($spot);
            $error['error'] = $shop->getErrors();
            $errors[] = $error;
        }
    }
    $ret = array();
    $ret['OK'] = $oks;
    $ret['ERROR'] = $errors;
    return $ret;
}

/**
 * 検索用 Option HTML 作成 （市区町村）
 * @param int $prefecture_id
 * @param int $selected
 * @return string
 */
function makeOptionHtml($prefecture_id = 0, $selected = 0) {
    $option = City::model()->getList($prefecture_id);

    $html = "<option value=\"\">市区町村</option>
";
    if(isset($option)) {
        foreach($option as $item) {
            $sel = '';
            if($selected > 0 && $item['city_id'] == $selected) {
                $sel = ' selected';
            }
            $html .= "<option {$sel} value=\"{$item['city_id']}\">{$item['city_name']}</option>
";
        }
    }
    return $html;
}

/**
 * 検索用 Option HTML 作成 （市区町村）
 * @param int $prefecture_id
 * @param int $selected
 * @return string
 */
function makeOptionShopHtml($prefecture_id = 0, $selected = 0) {
    $option = ShopCity::model()->getList($prefecture_id);

    $html = "<option value=\"\">市区町村</option>
";
    if(isset($option)) {
        foreach($option as $item) {
            $sel = '';
            if($selected > 0 && $item['city_id'] == $selected) {
                $sel = ' selected';
            }
            $html .= "<option {$sel} value=\"{$item['city_id']}\">{$item['city_name']}</option>
";
        }
    }
    return $html;
}

/**
 * @param string $area  表示するエリア
 * @param string $pref  Activeに設定する都道府県
 * @param int $limit 表示制限数
 *
 * @return string
 */
function makeAreaHtml($area = '', $pref = '', $limit = 0) {

    $html = "<div class=\"container-fluid\">";
    $html .= "<div class=\"shoplistarea\">";

    $html .= "<h4>{$area}エリア</h4>";
    $html .= "<ul class=\"nav nav-tabs clearfix\" role=\"tablist\">";
    
    $prefectures = Shop::model()->getPrefecturesByArea($area);
    if(is_array($prefectures)) {
        $index = 1;
        foreach ( $prefectures as $prefecture ) {
            $active = '';
            if(empty($pref)) {
                if($index==1) $active = ' class="active"';
            } else {
                if($pref==$prefecture) {
                    $active = ' class="active"';
                }
            }
            $html .= "<li role=\"presentation\"{$active}><a href=\"#area{$index}\" name=\"{$prefecture}\" aria-controls=\"area{$index}\" role=\"tab\" data-toggle=\"tab\">{$prefecture}</a></li>";
            $index += 1;
        }
        $html .= "</ul>";

        $index = 1;
        $html .= '<div class="tab-content">';
        foreach ( $prefectures as $prefecture ) {
            $active = '';
            if($index==1) $active = 'active';
            $html .= "	<div role=\"tabpanel\" class=\"tab-pane {$active}\" id=\"area{$index}\">";

            if(empty($limit)) {
                $models = Shop::model()->findAllByAttributes(array('shop_prefecture' => $prefecture));
            } else {
                $models = Shop::model()->findAllByAttributes(array('shop_prefecture' => $pref),
                                                             array('limit' => $limit));
            }
            foreach ( $models as $model ) {
                $href = R().Shop::model()->getPrefecturePath($model->shop_prefecture).'/'.$model->shop_id;
                $html .= '    <div class="shopinformation">';
								$html .= '      <div class="shopname">';
								$html .= "        <a href=\"{$href}\">{$model->shop_name}</a>";
								$html .= '      </div>';
                //カテゴリー
                $categories = Shop::model()->getCategories($model);
                if(!empty($categories)) {
                    $html .= '      <div class="categoryicon">';
                    foreach($categories as $category) {
                        $html .= "        <img src=\"/common/images/shop/{$category->category_icon}\" alt=\"{$category->category_name}\">";
                    }
                    $html .= '      </div>';
                }
                //サービス
                $service = Shop::model()->getServiceRanks($model);
                //$service = array();
                $html .= '      <div class="planicon">';
                if(in_array("1",$service)) {
                    $html .= "        <img src=\"/common/images/shop/icon_u_on.png\" alt=\"\">";
                } else {
                    $html .= "        <img src=\"/common/images/shop/icon_u_off.png\" alt=\"\">";
                }
                if(in_array("2",$service)) {
                    $html .= "        <img src=\"/common/images/shop/icon_m_on.png\" alt=\"\">";
                } else {
                    $html .= "        <img src=\"/common/images/shop/icon_m_off.png\" alt=\"\">";
                }
                if(in_array("3",$service)) {
                    $html .= "        <img src=\"/common/images/shop/icon_ss_on.png\" alt=\"\">";
                } else {
                    $html .= "        <img src=\"/common/images/shop/icon_ss_off.png\" alt=\"\">";
                }
                if(in_array("4",$service)) {
                    $html .= "        <img src=\"/common/images/shop/icon_s_on.png\" alt=\"\">";
                } else {
                    $html .= "        <img src=\"/common/images/shop/icon_s_off.png\" alt=\"\">";
                }
                if(in_array("5",$service)) {
                    $html .= "        <img src=\"/common/images/shop/icon_p_on.png\" alt=\"\">";
                } else {
                    $html .= "        <img src=\"/common/images/shop/icon_p_off.png\" alt=\"\">";
                }
                if(in_array("6",$service)) {
                    $html .= "        <img src=\"/common/images/shop/icon_pp_on.png\" alt=\"\">";
                } else {
                    $html .= "        <img src=\"/common/images/shop/icon_pp_off.png\" alt=\"\">";
                }
								$html .= '      </div>';
                $html .= '      <hr>';
                $html .= '      <div class="address">';
                $html .= "        <img src=\"/common/images/shop/icon_pin.png\" alt=\"\"> {$model->shop_address}";
                $html .= '	    </div>';
                $html .= '      <div class="tel">';
                $html .= "        <img src=\"/common/images/shop/icon_tel.png\" alt=\"\"> {$model->shop_phone}";
                $html .= '	    </div>';
                $html .= '      <div class="time">';
                $html .= "        <img src=\"/common/images/shop/icon_clock.png\" alt=\"\"> {$model->shop_hours}";
                if(!empty($model->shop_sim)) {
                    $html .= ' 最終受付時間:'.$model->shop_sim;
                }
                $html .= '	    </div>';
                $html .= '    </div>';
            }
            $html .= '  </div>';
            $index += 1;
        }
        $html .= '</div>';

    } else {
        return '';
    }
    $html .= "  </div>";
    $html .= "</div>";

    return $html;
}

/**
 * @param $errors
 *
 * @return string
 */
function makeErrorLog($errors) {
    $file = 'error_'. date('ymd_His') .'.log';

    foreach($errors as $line) {
        //n行目でエラー：エラー内容
        $index = $line['index'] + 1;
        $error = $line['error'];
        foreach($error as $item) {
            foreach($item as $msg) {
                $message = "{$index} 行目でエラー：{$msg}\n";
                writeLog($message, $file);
            }
        }
    }
    return $file;
}

/**
 * Log Fileに出力
 * @param $message
 * @param $file
 */
function writeLog($message, $file) {
    $fna = Yii::app()->basepath . param('error_file_path') . $file;
    $fh = fopen($fna, 'a+');
    fwrite($fh, $message);
    fclose($fh);
    return;
}

/**
 * ファイルダウンロード
 * $file
 * @param $file
 */
function error_download($file) {
    $image_file = Yii::app()->basepath . param('error_file_path') . $file;
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $file);
    header('Content-Length:' . filesize($image_file));
    header('Pragma: no-cache');
    header('Cache-Control: no-cache');
    readfile($image_file);
}


/**
 * File保存処理
 * $file：$_FILEの名称
 * @param $file
 * @return string
 */
function saveFile($file) {
    if(!isset($_FILES[$file])) {
        return '';
    } else {
        if($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
            //エラーが発生している
            if($_FILES[$file]['error'] == UPLOAD_ERR_FORM_SIZE) {
                //echo 'ファイルサイズがHTMLで指定した MAX_FILE_SIZE を超えています。';
                return '';
                //return false;
            } elseif($_FILES[$file]['error'] == UPLOAD_ERR_NO_FILE) {
                //echo 'ファイルが選択されていません。';
                return '';
            } else {
                //echo 'その他のエラーが発生しています。';
                return '';
            }
        } else {
            //ユーザーが指定したファイル名
            $myfile_name = $_FILES[$file]['name'];
            //ファイルのMIME型
            $myfile_type = $_FILES[$file]['type'];
            //ファイルサイズ
            $myfile_size = $_FILES[$file]['size'];
            //アップロードしたファイルが保存されている一時保存場所
            $myfile_tmp_path = $_FILES[$file]['tmp_name'];

            //HTML表示用
            $safehtml_myfile_name = htmlspecialchars($myfile_name);
            $safehtml_myfile_type = htmlspecialchars($myfile_type);

            //拡張子の取得
            $tmp_ary = explode('.', $myfile_name);
            if(count($tmp_ary) > 1) {
                $extension = $tmp_ary[count($tmp_ary) - 1];

                //拡張子が半角英数字以外なら拡張子がないものとする。
                if(!preg_match("/^[0-9a-zA-Z]+$/", $extension))
                    $extension = '';
            } else {
                //拡張子がない場合はそのまま。Macなど。
                $extension = '';
            }

            //HTML表示用
            $safehtml_extension = htmlspecialchars($extension);

            //新しいファイル名を作成する
            $new_file_name = date("YmdHis") . '_' . mt_rand() . '.' . $safehtml_extension;

            //ファイルの保存場所
            //$myfile_new_path = Yii::app()->basepath . '/../common/' . param('icon_image_path') . $myfile_name;
            $myfile_new_path = Yii::app()->basepath . '/../html' . param('icon_image_path') . $myfile_name;
            if(!move_uploaded_file($myfile_tmp_path, $myfile_new_path)) {
                //die('エラー ファイルを保存できませんでした。');
                return '';
            }

            //echo 'ファイル名 : '.$safehtml_myfile_name.'<br />';
            //echo 'MIME型 : '.$safehtml_myfile_type.'<br />';
            //echo 'ファイルサイズ : '.number_format($myfile_size).' bytes<br />';
            //echo '新しいファイル名 : '.$new_file_name.'<br />';
            $result = array();
            $result['name'] = $myfile_name;
            $result['path'] = $myfile_new_path;
            return $result;
        }
    }
}

function saveFiles($file) {
    $status = array();
    if(!isset($_FILES[$file])) {
        return $status;
    } else {
        if(is_array($_FILES[$file]['error'])) {
            foreach($_FILES[$file]['error'] as $index => $item) {
                if($item !== UPLOAD_ERR_OK) {
                    //エラーが発生している
                    if($item == UPLOAD_ERR_FORM_SIZE) {
                        //echo 'ファイルサイズがHTMLで指定した MAX_FILE_SIZE を超えています。';
                        return $status;
                        //return false;
                    } elseif($item == UPLOAD_ERR_NO_FILE) {
                        //echo 'ファイルが選択されていません。';
                        return $status;
                    } else {
                        //echo 'その他のエラーが発生しています。';
                        return $status;
                    }
                } else {
                    //ユーザーが指定したファイル名
                    $myfile_name = $_FILES[$file]['name'][$index];
                    //ファイルのMIME型
                    $myfile_type = $_FILES[$file]['type'][$index];
                    //ファイルサイズ
                    $myfile_size = $_FILES[$file]['size'][$index];
                    //アップロードしたファイルが保存されている一時保存場所
                    $myfile_tmp_path = $_FILES[$file]['tmp_name'][$index];

                    //HTML表示用
                    $safehtml_myfile_name = htmlspecialchars($myfile_name);
                    $safehtml_myfile_type = htmlspecialchars($myfile_type);

                    //拡張子の取得
                    $tmp_ary = explode('.', $myfile_name);
                    if(count($tmp_ary) > 1) {
                        $extension = $tmp_ary[count($tmp_ary) - 1];

                        //拡張子が半角英数字以外なら拡張子がないものとする。
                        if(!preg_match("/^[0-9a-zA-Z]+$/", $extension))
                            $extension = '';
                    } else {
                        //拡張子がない場合はそのまま。Macなど。
                        $extension = '';
                    }

                    //HTML表示用
                    $safehtml_extension = htmlspecialchars($extension);

                    //新しいファイル名を作成する
                    $new_file_name = date("YmdHis") . '_' . mt_rand() . '.' . $safehtml_extension;

                    //ファイルの保存場所
                    //$myfile_new_path = Yii::app()->basepath . '/../common/' . param('icon_image_path') . $myfile_name;
                    $myfile_new_path = Yii::app()->basepath . '/../html' . param('banner_image_path') . $myfile_name;
                    if(!move_uploaded_file($myfile_tmp_path, $myfile_new_path)) {
                        //die('エラー ファイルを保存できませんでした。');
                        return $status;
                    }

                    //echo 'ファイル名 : '.$safehtml_myfile_name.'<br />';
                    //echo 'MIME型 : '.$safehtml_myfile_type.'<br />';
                    //echo 'ファイルサイズ : '.number_format($myfile_size).' bytes<br />';
                    //echo '新しいファイル名 : '.$new_file_name.'<br />';
                    $result = array();
                    $result['name'] = $myfile_name;
                    $result['path'] = $myfile_new_path;
                    $status[] = $result;
                }
            }
        }
    }
    return $status;
}

function saveCPFile($file) {
    if(!isset($_FILES[$file])) {
        return '';
    } else {
        if($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
            //エラーが発生している
            if($_FILES[$file]['error'] == UPLOAD_ERR_FORM_SIZE) {
                //echo 'ファイルサイズがHTMLで指定した MAX_FILE_SIZE を超えています。';
                return '';
                //return false;
            } elseif($_FILES[$file]['error'] == UPLOAD_ERR_NO_FILE) {
                //echo 'ファイルが選択されていません。';
                return '';
            } else {
                //echo 'その他のエラーが発生しています。';
                return '';
            }
        } else {
            //ユーザーが指定したファイル名
            $myfile_name = $_FILES[$file]['name'];
            //ファイルのMIME型
            $myfile_type = $_FILES[$file]['type'];
            //ファイルサイズ
            $myfile_size = $_FILES[$file]['size'];
            //アップロードしたファイルが保存されている一時保存場所
            $myfile_tmp_path = $_FILES[$file]['tmp_name'];

            //HTML表示用
            $safehtml_myfile_name = htmlspecialchars($myfile_name);
            $safehtml_myfile_type = htmlspecialchars($myfile_type);

            //拡張子の取得
            $tmp_ary = explode('.', $myfile_name);
            if(count($tmp_ary) > 1) {
                $extension = $tmp_ary[count($tmp_ary) - 1];

                //拡張子が半角英数字以外なら拡張子がないものとする。
                if(!preg_match("/^[0-9a-zA-Z]+$/", $extension))
                    $extension = '';
            } else {
                //拡張子がない場合はそのまま。Macなど。
                $extension = '';
            }

            //HTML表示用
            $safehtml_extension = htmlspecialchars($extension);

            //新しいファイル名を作成する
            $new_file_name = date("YmdHis") . '_' . mt_rand() . '.' . $safehtml_extension;

            //ファイルの保存場所
            //$myfile_new_path = Yii::app()->basepath . '/../common/' . param('icon_image_path') . $myfile_name;
            $myfile_new_path = Yii::app()->basepath . '/../html' . param('banner_image_path') . $myfile_name;
            if(!move_uploaded_file($myfile_tmp_path, $myfile_new_path)) {
                //die('エラー ファイルを保存できませんでした。');
                return '';
            }

            //echo 'ファイル名 : '.$safehtml_myfile_name.'<br />';
            //echo 'MIME型 : '.$safehtml_myfile_type.'<br />';
            //echo 'ファイルサイズ : '.number_format($myfile_size).' bytes<br />';
            //echo '新しいファイル名 : '.$new_file_name.'<br />';
            $result = array();
            $result['name'] = $myfile_name;
            $result['path'] = $myfile_new_path;
            return $result;
        }
    }
}

/**
 * CSVファイルをダウンロード
 * データはヘッダーを含めた配列
 * @param $dataList
 * @param $fileName
 */
function downloadCsv($dataList, $fileName) {

    try {

        //CSV形式で情報をファイルに出力のための準備
        $csvFileName = '/tmp/' . time() . rand() . '.csv';
        $res = fopen($csvFileName, 'w');
        if ($res === FALSE) {
            throw new Exception('ファイルの書き込みに失敗しました。');
        }

        // ループしながら出力
        foreach($dataList as $dataInfo) {

            // 文字コード変換。エクセルで開けるようにする
            mb_convert_variables('SJIS-WIN', 'UTF-8', $dataInfo);

            // ファイルに書き出しをする
            //fputcsv($res, $dataInfo);
            for($i=0;$i<count($dataInfo);$i++) {
                if(!empty($dataInfo[$i])) {
                    if(substr($dataInfo[$i],0,1)!='"' && substr($dataInfo[$i],-1,1)!='"') {
                        //"で囲われていない文字列に対応する処理
                        $dataInfo[$i] = '"'.$dataInfo[$i].'"';
                    }
                    //"のエスケープ処理
                    $substr = substr($dataInfo[$i],1,count($dataInfo[$i])-2);
                    $substr = str_replace('"', '""', $substr);
                    $dataInfo[$i] = '"'.$substr.'"';

                    if(substr($dataInfo[$i],0,2)=='"0') {
                        //0から始まる文字列に対応する処理
                        $dataInfo[$i] = '='.$dataInfo[$i];
                    }
                } else {
                    $dataInfo[$i] = '"'.$dataInfo[$i].'"';
                }
            }
            $line = implode(',' , $dataInfo);
            fwrite($res, $line . "\r\n");
        }

        // ハンドル閉じる
        fclose($res);

        // ダウンロード開始
        header('Content-Type: application/octet-stream');

        // ここで渡されるファイルがダウンロード時のファイル名になる
        header('Content-Disposition: attachment; filename='.$fileName);
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($csvFileName));
        readfile($csvFileName);

    } catch(Exception $e) {

        // 例外処理をここに書きます
        echo $e->getMessage();

    }
}

/**
 * @param $string
 *
 * @return mixed|string|void
 */
function searchWordPress($string) {
    $temp = array();

    $prefix_s = param('wp_prefix');
    foreach($prefix_s as $name => $prefix) {
        $query= "SELECT
                p.*,m.meta_value AS description
            FROM {$prefix}posts p
            LEFT JOIN {$prefix}postmeta m
            ON p.ID = m.post_id AND m.meta_key='_aioseop_description'
            WHERE (post_content LIKE '%{$string}%' OR post_title LIKE '%{$string}%' OR m.meta_value LIKE '%{$string}%')
            AND p.post_name <> 'contents'
            AND p.post_status = 'publish'
            AND p.post_type <> 'nav_menu_item'
            GROUP BY p.ID
            ORDER BY
            (CASE WHEN p.post_title LIKE '%{$string}%' THEN 1 ELSE (CASE WHEN m.meta_value LIKE '%{$string}%' THEN 2 ELSE 3 END) END),
              p.post_date DESC";
        $data = exec_query($query, $name);
        $ret = array();
        foreach($data as $post) {
            //linkを取得
            if($post['post_type']=='post') {
                $link = get_permalink_post($name, $post);
                $post['link'] = get_wp_prefix($name).$link;
            } else if($post['post_type']=='page') {
                $link = get_permalink_page($name, $post);
                $post['link'] = get_wp_prefix($name).$link;
            } else if($post['post_type']=='news' || $post['post_type']=='tech' || $post['post_type']=='maintenance') {
                $link = get_permalink_page($name, $post);
                $post['link'] = get_wp_prefix( $name ) . '/information/'.$post['post_type'] . $link;
            } else {
                $link         = get_permalink_page( $name, $post );
                $post['link'] = get_wp_prefix( $name ) . '/'.$post['post_type'] . $link;
            }
            //descriptionを取得
            $post['description'] = $post['description'];
            $ret[] = $post;
        }
        $temp = array_merge($temp, $ret);
    }
    return json_encode($temp);
}

function get_wp_prefix($name) {
    $prefix = '';
    switch ($name) {
        case 'main':
            $prefix = '';
        break;
        case 'faq':
            $prefix = '/faq';
            break;
        case 'lp':
            $prefix = '/lp';
            break;
        case 'magazine':
            $prefix = '/magazine';
            break;
    }
    return $prefix;
}

function get_permalink_page($name, $post) {
    $link = '';
    $prefix_s = param('wp_prefix');
    $prefix = $prefix_s[$name];
    $id = $post['ID'];
    $query = "SELECT * FROM {$prefix}posts WHERE ID = {$id}";
    $data = exec_query($query, $name);
    $links = array();
    if(!empty($data[0]['post_name'])) {
        $links[] = $data[0]['post_name'];
    }
    while(!empty($data[0]['post_parent'])){
        $id = $data[0]['post_parent'];
        $query = "SELECT * FROM {$prefix}posts WHERE ID = {$id}";
        $data = exec_query($query, $name);
        $links[] = $data[0]['post_name'];
    }
    if(!empty($links)) {
        for($i=count($links);$i>0;$i--) {
            $link .= '/'.$links[$i-1];
        }
    }
    return $link;
}

function get_category_slug($name, $post) {
    $prefix_s = param('wp_prefix');
    $prefix = $prefix_s[$name];
    $id = $post['ID'];
    $query = "SELECT s.slug FROM ({$prefix}term_relationships r INNER JOIN {$prefix}term_taxonomy t ON r.term_taxonomy_id = t.term_taxonomy_id AND r.object_id = {$id} AND t.taxonomy = 'category') INNER JOIN {$prefix}terms s ON t.term_taxonomy_id = s.term_id ORDER BY s.term_id ASC LIMIT 1";
    $data = exec_query($query, $name);
    if(!empty($data[0])) {
        return $data[0]['slug'];
    }
    return '';
}

function get_category_name($name, $post) {
    $prefix_s = param('wp_prefix');
    $prefix = $prefix_s[$name];
    $id = $post['ID'];
    $query = "SELECT s.name FROM ({$prefix}term_relationships r INNER JOIN {$prefix}term_taxonomy t ON r.term_taxonomy_id = t.term_taxonomy_id AND r.object_id = {$id} AND t.taxonomy = 'category') INNER JOIN {$prefix}terms s ON t.term_taxonomy_id = s.term_id ORDER BY s.term_id ASC LIMIT 1";
    $data = exec_query($query, $name);
    if(!empty($data[0])) {
        return $data[0]['name'];
    }
    return '';
}

function get_permalink_post($name, $post) {
    $prefix_s = param('wp_prefix');
    $prefix = $prefix_s[$name];
    $query = "SELECT option_value FROM {$prefix}options WHERE option_name = 'permalink_structure'";
    $data = exec_query($query, $name);
    $solved = '';
    if(!empty($data[0])) {
        $option_value =  $data[0]['option_value'];
        $year = date("Y", strtotime($post['post_date']));
        $month = date("m", strtotime($post['post_date']));
        $day = date("d", strtotime($post['post_date']));
        $solved = str_replace('%year%', $year, $option_value);
        $solved = str_replace('%monthnum%', $month, $solved);
        $solved = str_replace('%day%', $day, $solved);
        $solved = str_replace('%category%', get_category_slug($name, $post), $solved);
        $solved = str_replace('%postname%', $post['post_name'], $solved);
        $solved = str_replace('%post_id%', $post['ID'], $solved);
    }
    return $solved;
}

function get_description($name, $post) {
    $prefix_s = param('wp_prefix');
    $prefix = $prefix_s[$name];
    $id = $post['ID'];
    $query = "select meta_value from {$prefix}postmeta
              where post_id={$id}
              and meta_key='_aioseop_description'";
    $data = exec_query($query, $name);
    if(!empty($data[0])) {
        return $data[0]['meta_value'];
    }
    return '';
}

/**
 * @param $query
 * @param $name
 *
 * @return string
 */
function exec_query($query, $name) {
    switch ($name) {
        case 'main':
            $data = Yii::app()->wp_main->createCommand($query)->queryAll();
            break;
        case 'faq':
            $data = Yii::app()->wp_faq->createCommand($query)->queryAll();
            break;
        case 'lp':
            $data = Yii::app()->wp_lp->createCommand($query)->queryAll();
            break;
        case 'magazine':
            $data = Yii::app()->wp_magazine->createCommand($query)->queryAll();
            break;
        default:
            return '';
            break;
    }
    return $data;
}

/**
 * @return mixed
 */
function getNewPost($limit = 4) {
    //$uri = param('rest_api_new');
    //$json = get_html($uri);
    //$pers = json_decode($json,true);
    //var_dump($pers);

    $name = 'magazine';
    $prefix_s = param('wp_prefix');
    $prefix = $prefix_s[$name];
    $query = "SELECT
              M1.meta_value
              ,P.*
              FROM {$prefix}posts P
              LEFT JOIN {$prefix}postmeta M
              ON P.ID = M.post_id AND
              M.meta_key = '_thumbnail_id'
              LEFT JOIN {$prefix}postmeta M1
              ON M1.post_id = M.meta_value AND
              M1.meta_key = '_wp_attachment_metadata'
              WHERE P.post_status = 'publish' AND P.post_type IN ('post')
              ORDER BY P.post_date DESC
              LIMIT {$limit}
              ";
    $data = exec_query($query, $name);

    if(empty($data)) return '';

    $result = array();
    foreach($data as $post) {
        if(!empty($post['meta_value'])) {
            $meta_value = $post['meta_value'];
            $array = unserialize($meta_value);
            //$files = explode('/', $array['file'] );
            //$files[count($files)-1] = $array['sizes']['thumbnail']['file'];
            //$thumbnail = implode('/', $files);
            //$post['thumbnail'] = param('wp_magazine_uploads_path').$thumbnail;
            $post['thumbnail'] = param('wp_magazine_uploads_path').$array['file'];
            $post['category'] = get_category_name($name,$post);
            $post['slug'] = get_category_slug($name,$post);
            //linkを取得
            if($post['post_type']=='post') {
                $link = get_permalink_post($name, $post);
                $post['link'] = get_wp_prefix($name).$link;
            } else if($post['post_type']=='page') {
                $link = get_permalink_page($name, $post);
                $post['link'] = get_wp_prefix($name).$link;
            } else if($post['post_type']=='atoz') {
                $link = get_permalink_page($name, $post);
                $post['link'] = get_wp_prefix($name).'/atoz'.$link;
            }
        }
        $result[] = $post;
    }
    return json_encode($result);
}

/**
 * @return mixed|string|void
 */
function getCategories() {
    $result = array();
    $uri = param('rest_api_categories');
    $json = get_html($uri);
    $categories = json_decode($json,true);
    foreach($categories as $cat) {
        $result[$cat['id']] = $cat['name'];
    }
    return json_encode($result);
}

/**
 * @param $src  /lp/max
 */
function get_image($src) {
    //IMG作成コマンド実行
    $exe_path = Yii::app()->basepath . '/commands/get_image.sh';
    $u_src = z_mb_urlencode($src);
    $u_src = str_replace('%','',$u_src);
    $img = str_replace('/', '', $u_src).'.jpg';
    $command = "{$exe_path} {$src} {$img}";
    $output = array();
    $ret = null;
    exec($command, $output, $ret);
    if(!empty($output)) {
        echo $output[count($output)-1];
    } else {
        echo '';
    }
}

function z_mb_urlencode( $str ) {
    return preg_replace_callback(
      '/[^\x21-\x7e]+/',
      function( $matches ) {
          return urlencode( $matches[0] );
      },
      $str );
}

/**
 * @param $command
 *
 * @return array|string
 */
function exec_command($command) {
    //IMG作成コマンド実行
    $output = array();
    $ret = null;
    exec($command, $output, $ret);
    if(!empty($output)) {
        return $output;
    } else {
        return array();
    }
}

