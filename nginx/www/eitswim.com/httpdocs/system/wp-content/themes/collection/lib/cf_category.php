<?php

/* ボックス追加 */
add_action('admin_menu', function(){
  //固定ページの subtitle を記入する
  add_meta_box( 'elc_item_category', 'カテゴリー', 'create_item_category', 'city', 'advanced' );
  add_meta_box( 'elc_item_category', 'カテゴリー', 'create_item_category', 'surf', 'advanced' );
});


function create_item_category() {
  $keyname = 'elc_item_category';
  global $post;
  // 保存されているカスタムフィールドの値を取得
  $get_value = get_post_meta( $post->ID, $keyname, true );

  $args = array(
    'type'                     => 'post',
    'child_of'                 => 0,
    'parent'                   => '0',
    'orderby'                  => 'name',
    'order'                    => 'ASC',
    'hide_empty'               => 0,
    'hierarchical'             => 1,
    'exclude'                  => '',
    'include'                  => '',
    'number'                   => '',
    'taxonomy'                 => 'category',
    'pad_counts'               => false
  );
  $categories = get_categories($args);
  $data = array();
  foreach($categories as $item) {
    $data[$item->cat_ID] = $item->name;
  }

// nonceの追加
  wp_nonce_field( 'action-' . $keyname, 'nonce-' . $keyname );

// HTMLの出力
  echo '<select name="' . $keyname . '">';
  echo '<option value="">-----</option>';
  foreach( $data as $id=>$name ) {
    $selected = '';
    if( $id == $get_value ) $selected = ' selected';
    echo '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
  }
  echo '</select>';
}

// カスタムフィールドの保存
add_action( 'save_post', function($post_id){
  $custom_fields = ['elc_item_category'];

  foreach( $custom_fields as $d ) {
    if ( isset( $_POST['nonce-' . $d] ) && $_POST['nonce-' . $d] ) {
      if( check_admin_referer( 'action-' . $d, 'nonce-' . $d ) ) {

        if( isset( $_POST[$d] ) && $_POST[$d] ) {
          update_post_meta( $post_id, $d, $_POST[$d] );
        } else {
          delete_post_meta( $post_id, $d, get_post_meta( $post_id, $d, true ) );
        }
      }
    }
  }
});
