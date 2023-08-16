<?php
/**
 * メディア関連の関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

// Thumbnail sizes を追加
add_image_size( 'studioelc-thumb-525', 525, 350, true );

/**
 * 画像URLからIDを取得
 * @param $url
 * @return int
 */
function get_attachment_id_by_url( $url ) {
  global $wpdb;
  //$sql = "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s";
  //preg_match( '/([^\/]+?)(-e\d+)?(-\d+x\d+)?(\.\w+)?$/', $url, $matches );
  //$post_name = $matches[1];
  //return ( int )$wpdb->get_var( $wpdb->prepare( $sql, $post_name ) );
  //post_name が 画像の名前と一致しているわけではなかったので、修正（マルチサイトの場合か？）
  $sql = "SELECT post_id as ID FROM {$wpdb->postmeta} WHERE %s LIKE concat('%', meta_value) AND meta_value <> ''";
  return ( int )$wpdb->get_var( $wpdb->prepare( $sql, $url ) );
}

/**
 * 最初の画像を取得
 * アイキャッチがある場合は、アイキャッチを優先
 * @param int $id
 * @param string $size
 * @return string
 */
if( function_exists( 'catch_that_image' ) === false ):
  function catch_that_image($id = 0, $size = 'full') {
    if(empty($id)) {
      global $post;
      $id = $post->ID;
    } else {
      $post = get_post($id);
    }

    $ret = '';
    $post_content = $post->post_content;
    if(has_post_thumbnail()) {
      //アイキャッチがある場合
      $image_id = get_post_thumbnail_id($id);
      $image_src = wp_get_attachment_image_src($image_id, $size);
      if(!empty($image_src[0])) {
        $ret = $image_src[0];
      }

    } else {
      ob_start();
      ob_end_clean();
      $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
      $first_img_src = $matches [1] [0];
      $attachment_id = get_attachment_id_by_url( $first_img_src );
      $image_src = wp_get_attachment_image_src($attachment_id, $size);
      if(!empty($image_src[0])) {
        $ret = $image_src[0];
      }
    }
    return $ret;
  }
endif;


/**
 * メディア挿入時に余分なタグを削除
 */
add_filter( 'image_send_to_editor', 'remove_image_attribute', 10 );
add_filter( 'post_thumbnail_html', 'remove_image_attribute', 10 );

function remove_image_attribute( $html ){
  $html = preg_replace( '/(width|height)="\d*"\s/', '', $html );
  $html = preg_replace( '/class=[\'"]([^\'"]+)[\'"]/i', '', $html );
  $html = preg_replace( '/title=[\'"]([^\'"]+)[\'"]/i', '', $html );
  //$html = preg_replace( '/<a href=".+">/', '', $html );
  //$html = preg_replace( '/<\/a>/', '', $html );
  $html = '<div class="post-image center">' . $html;
  $html .= '</div>';
  //$html = preg_replace( '/<div[\s\S]*?class="post-image"[\s\S]*?<a[\s\S]*?href="/', '<div class="post-image"><a data-lightbox-type="image" href="', $html );
  $html = preg_replace( '/<div[\s\S]*?class="post-image"[\s\S]*?<a[\s\S]*?href="/', '<div class="post-image center" data-lightbox="gallery"><a data-lightbox="gallery-item" href="', $html );
  $html = str_replace("  />", ">", $html);
  $html = str_replace(" />", ">", $html);
  return $html;
}
remove_filter('the_content', 'convert_chars');

// 画像編集の際に勝手にwidth/heightが入るので削除
function my_tinymce_remove_width_attribute( $options ) {
  if ( $options['tinymce'] ) {
    wp_enqueue_script( 'tinymce_remove_width_attribute', get_template_directory_uri() . '/js/wp-admin-remove_width_attribute.js', array( 'jquery' ), '1.0.0', true);
  }
}
add_action( 'wp_enqueue_editor', 'my_tinymce_remove_width_attribute', 10, 1 );

/**
 * 動画ボタンの追加
 */
function register_button($buttons) {
  $buttons[] = 'blockquote_link';
  return $buttons;
}
add_filter('mce_buttons', 'register_button');

function mce_plugin($plugin_array) {
  $plugin_array['custom_button_script'] = get_template_directory_uri() . '/plugin/editor_plugin.js';
  return $plugin_array;
}
add_filter('mce_external_plugins', 'mce_plugin');

/**
 * 管理画面のメディアサイズ選択を非表示にさせる
 */
function my_admin_style() {
  echo '<style>
div.attachment-display-settings {
  	display: none;
}
</style>'.PHP_EOL;
}
add_action('admin_print_styles', 'my_admin_style');

/**
 * メディアを挿入　添付ファイルの表示設定のデフォルト値変更
 */
function image_default_setting() {
  $image_link = get_option( 'image_default_link_type' );
  $image_size = get_option( 'image_default_size' );

  // リンク先をなしに指定
  if ( $image_link !== 'file' ) {
    update_option( 'image_default_link_type', 'file' );
  }

  // サイズをフルサイズに指定
  if ( $image_size !== 'full' ) {
    update_option( 'image_default_size', 'full' );
  }
}
add_action('admin_init', 'image_default_setting', 10);

/**
 * メディアを挿入　添付ファイルの表示設定のデフォルト値変更
 * 設定が保存されることに対応
 */
function default_attachment_display_settings2() {
  $ob  = "<script type='text/javascript'>";
  $ob .= "if ( typeof setUserSetting !== 'undefined' ) {";
  //$ob .= sprintf("setUserSetting( 'align', '%s' );", 'none');     // 配置
  $ob .= sprintf("setUserSetting( 'urlbutton', '%s' );", 'file');     // リンク先
  $ob .= sprintf("setUserSetting( 'imgsize', '%s' );", 'full' );      // サイズ
  $ob .= "}";
  $ob .= "</script>";
  echo $ob;
}
add_action( 'admin_print_footer_scripts', 'default_attachment_display_settings2' );
