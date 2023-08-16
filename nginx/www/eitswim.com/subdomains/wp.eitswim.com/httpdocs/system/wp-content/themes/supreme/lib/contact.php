<?php
/**
 * コンタクトフォーム関連の関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

/**
 * MW WP Form で送信日時を追加するカスタマイズ
 * 「mwform_custom_mail_tag_mw-wp-form-15」の数字をフォームのIDに変更すること
 * @param $value
 * @param $key
 * @param $insert_contact_data_id
 * @return string
 */
function send_date( $value, $key, $insert_contact_data_id ) {
  if ( $key === 'send_datetime' ) {
    return date_i18n( 'Y年m月d日' );
  }
  return $value;
}
function send_date_time( $value, $key, $insert_contact_data_id ) {
  if ( $key === 'send_datetime' ) {
    return date_i18n( 'Y年m月d日 H:i' );
  }
  return $value;
}
function send_date_time_his( $value, $key, $insert_contact_data_id ) {
  if ( $key === 'send_datetime' ) {
    return date_i18n( 'Y年m月d日 H:i:s' );
  }
  return $value;
}

/**
 * MW WP FormのIDを設定
 */
global $wpdb;
$post_meta = $wpdb->get_row(
  $wpdb->prepare(
    "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s", 'mw-wp-form'
  ), ARRAY_A
);
$mw_wp_id = $post_meta['post_id'];
$key = 'mwform_custom_mail_tag_mw-wp-form-'.$mw_wp_id;

add_filter( $key, 'send_date_time', 10, 3 );
