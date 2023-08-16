<?php

/*---------------------------------------------------------------------------
 * title image のボックス追加
 *---------------------------------------------------------------------------*/

/* ボックス追加 */
add_action('admin_menu', function(){
  // メタボックス追加
  add_meta_box( 'elc_top_news', 'TOPでの表示', 'elc_top_news', 'post', 'advanced' );

});

/* 投稿画面に表示するフォーム */
if( function_exists( 'elc_top_news' ) === false ):
  function elc_top_news() {
    global $post;
    $elc_top_news_check = "";

    if( get_post_meta($post->ID,'elc_top_news',true) == "is-on" ) {
      $elc_top_news_check = "checked";
    }//チェックされていたらチェックボックスの$book_label_checkの場所にcheckedを挿入
    echo 'TOPに表示： <input type="checkbox" name="elc_top_news" value="is-on" '.$elc_top_news_check.' ><br>';

  }
endif;

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
  // カスタムフィールドの保存（新規・更新・削除）

  $post_data = $_POST['elc_top_news'];
  $field_value = get_post_meta($post_id, 'elc_top_news', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_top_news', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_top_news', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_top_news', $field_value);

} );
