<?php
add_action( 'init', function(){
  $labels = array(
    // 投稿タイプの名前
    'name'          => 'CITY',
    // すべての投稿タイプを表すテキスト
    'all_items'     => 'CITY一覧',
    // 新規追加を表すテキスト
    'add_new_item'  => '新規追加',
    // 編集を表すテキスト
    'edit_item'     => '編集',
  );
  $args = array(
    // 投稿タイプの翻訳を配列で指定
    'labels'        => $labels,
    // trueの場合は投稿タイプを公開。falseの場合は投稿タイプを公開しない（管理画面からも使用不可）初期値はfalse
    'public'        => true,
    // trueの場合は階層の指定が可能。初期値はfalse
    'hierarchical'  => false,
    // trueの場合はアーカイブを有効にする。初期値はfalse
    'has_archive'   => false,
    // 投稿タイプで追加・編集できる要素を指定。初期値はtitleとeditor
    'supports'      => array('title','editor','thumbnail'),
    // 管理画面で投稿タイプが表示されるメニューの位置。5は「投稿の下」に表示。 デフォルトは「コメントの下」
    //'menu_position' => 5,
    // 設定するカスタムタクソノミーを配列で指定。カスタムタクソノミーはregister_taxonomy()関数で登録。初期値はタクソノミー無し
    // register_taxonomy_for_object_type() を直接呼び出す代わりに使用可能。
    //'taxonomies'    => array('book_category','book_tag'),
  );
  register_post_type('city',$args);

  $labels = array(
    // 投稿タイプの名前
    'name'          => 'SURF',
    // すべての投稿タイプを表すテキスト
    'all_items'     => 'SURF一覧',
    // 新規追加を表すテキスト
    'add_new_item'  => '新規追加',
    // 編集を表すテキスト
    'edit_item'     => '編集',
  );
  $args = array(
    // 投稿タイプの翻訳を配列で指定
    'labels'        => $labels,
    // trueの場合は投稿タイプを公開。falseの場合は投稿タイプを公開しない（管理画面からも使用不可）初期値はfalse
    'public'        => true,
    // trueの場合は階層の指定が可能。初期値はfalse
    'hierarchical'  => false,
    // trueの場合はアーカイブを有効にする。初期値はfalse
    'has_archive'   => false,
    // 投稿タイプで追加・編集できる要素を指定。初期値はtitleとeditor
    'supports'      => array('title','editor','thumbnail'),
    // 管理画面で投稿タイプが表示されるメニューの位置。5は「投稿の下」に表示。 デフォルトは「コメントの下」
    //'menu_position' => 5,
    // 設定するカスタムタクソノミーを配列で指定。カスタムタクソノミーはregister_taxonomy()関数で登録。初期値はタクソノミー無し
    // register_taxonomy_for_object_type() を直接呼び出す代わりに使用可能。
    //'taxonomies'    => array('book_category','book_tag'),
  );
  register_post_type('surf',$args);

});

//以下、カスタムリンクを投稿IDに変更する
add_filter('post_type_link', function($link, $post){
  if($post->post_type === 'city') {
    // 投稿IDに書き換えたパーマリンク文字列を返す
    return home_url('/city/' . $post->ID);
  } else if($post->post_type === 'surf'){
    // 投稿IDに書き換えたパーマリンク文字列を返す
    return home_url('/surf/'.$post->ID);
  } else {
    return $link;
  }
}, 1, 2);
add_filter('rewrite_rules_array', function($rules){
  // 書き換えたパーマリンクに対応したクエリルールを追加
  $new_rule = array(
    'city/([0-9]+)/?$' => 'index.php?post_type=city&p=$matches[1]',
    'surf/([0-9]+)/?$' => 'index.php?post_type=surf&p=$matches[1]'
  );
  // ルール配列に結合
  return $new_rule + $rules;
});
