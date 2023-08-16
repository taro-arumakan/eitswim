<?php

// カスタム投稿タイプの追加
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
});
