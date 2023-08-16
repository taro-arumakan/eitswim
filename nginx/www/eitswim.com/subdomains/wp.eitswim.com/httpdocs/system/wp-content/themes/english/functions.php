<?php

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
