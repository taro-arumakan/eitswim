<?php

/*---------------------------------------------------------------------------
 * title image のボックス追加
 *---------------------------------------------------------------------------*/

/* ボックス追加 */
add_action('admin_menu', function(){
  // メタボックス追加
  add_meta_box( 'elc_collection_link', 'リンクの設定', 'elc_collection_link', 'city', 'advanced' );
  add_meta_box( 'elc_collection_link', 'リンクの設定', 'elc_collection_link', 'surf', 'advanced' );

});

/* 投稿画面に表示するフォーム */
if( function_exists( 'elc_collection_link' ) === false ):
function elc_collection_link() {
	global $post;

  $text_field = get_post_meta($post->ID,'elc_collection_link',true);
  echo <<<EOS
  <label>リンク</label><br>
  <input type="text" value="{$text_field}" name="elc_collection_link" style="width:400px;">
EOS;

}
endif;

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
  // カスタムフィールドの保存（新規・更新・削除）
  $post_data = $_POST['elc_collection_link'];
  $field_value = get_post_meta($post_id, 'elc_call_to_action_title', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_collection_link', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_collection_link', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_collection_link', $field_value);


});
