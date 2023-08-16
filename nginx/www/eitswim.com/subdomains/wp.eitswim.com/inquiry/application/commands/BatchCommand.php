<?php
class BatchCommand extends CConsoleCommand
{
    /*
     * php yiic.php batch search
     */
    public function actionSearch() {

        $url = 'http://localhost/system/api/search/';

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
        curl_close($ch);
        $aResult = json_decode($result, TRUE );

        foreach($aResult as $item) {
            $this->get_image($item['link']);
        }

    }
    
    public function get_image($src) {
        $exe_path = Yii::app()->basepath . '/commands/get_image.sh';
        $u_src = $this->z_mb_urlencode($src);
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


}

