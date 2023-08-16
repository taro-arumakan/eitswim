<?php

/*---------------------------------------------------------------------------
 * title image のボックス追加
 *---------------------------------------------------------------------------*/
const META_ID = 'elc_title_image';
const META_TITLE = 'Title Image';

/* ボックス追加 */
add_action('admin_menu', function(){
  $args = array( 'id' => META_ID, 'title' => META_TITLE);
  // メタボックス追加
  // context:'normal', 'advanced'
  // priority:'high', 'core', 'default' または 'low'
  add_meta_box( META_ID, META_TITLE, 'elc_image_callback', 'page', 'advanced','default', $args );
  add_meta_box( META_ID, META_TITLE, 'elc_image_callback', 'post', 'advanced','default', $args );

});

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
  $title_data = isset( $_POST[META_ID] ) && !empty( $_POST[META_ID] ) ? $_POST[META_ID] : null;
  $title_data = remove_home_url($title_data);
  $title_img = get_post_meta( $post_id, META_ID );

  if( !empty( $title_data ) && empty( $title_img ) ) {
    /* 保存 */
    add_post_meta( $post_id, META_ID, $title_data, true ) ;
  }
  elseif( !empty( $title_data ) && $title_data !== $title_img ) {
    /* 更新 */
    update_post_meta( $post_id, META_ID, $title_data ) ;
  }
  else {
    /* 削除 */
    delete_post_meta( $post_id, META_ID ) ;
  }
} );
