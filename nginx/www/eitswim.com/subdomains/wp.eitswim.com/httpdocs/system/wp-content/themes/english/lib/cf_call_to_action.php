<?php

/*---------------------------------------------------------------------------
 * title image のボックス追加
 *---------------------------------------------------------------------------*/

/* ボックス追加 */
add_action('admin_menu', function(){
  // メタボックス追加
  add_meta_box( 'elc_call_to_action', 'Call to actionの表示', 'elc_call_to_action', 'page', 'advanced' );

});

/* 投稿画面に表示するフォーム */
if( function_exists( 'elc_call_to_action' ) === false ):
function elc_call_to_action() {
	global $post;

  $options = array('ON','OFF');
  $n = count($options);

  $radio_field = get_post_meta($post->ID,'radio_call_to_action',true);

  echo '<label for="radio_field">ON：Call to Action を表示します。</label><br>';
  for ($i=0; $i<$n; $i++) {
    $option = $options[$i];
    if ($option==$radio_field) {
      echo '<input type="radio" name="radio_call_to_action" value="'. esc_html($option) .'" checked > '. $option .' ';
    } else {
      echo '<input type="radio" name="radio_call_to_action" value="'. esc_html($option) .'" > '. $option .' ';
    }
  }

	/* 既に値がある場合、その値をフォームに出力 */
	$image = '';
	$og_image = get_post_meta( $post->ID, 'elc_call_to_action_image', true );

	if( !empty( $og_image ) ) {
		$image = get_post_meta( $post->ID, 'elc_call_to_action_image', true );
	}
?>
  <script>thkImageSelector('elc-call-to-action-image', 'Title Image (1920 × 1280)');</script>
  <div id="elc-call-to-action-image-form">
    <input id="elc-call-to-action-image" type="hidden" name="elc_call_to_action_image" value="<?php echo $image; ?>" />
    <input id="elc-call-to-action-image-set" type="button" class="button" value="<?php echo __( 'Set image', 'luxeritas' ); ?>" />
    <input id="elc-call-to-action-image-del" type="button" class="button" value="<?php echo __( 'Delete image', 'luxeritas' ); ?>" />
  </div>
  <?php
  if( !empty( $image ) ) {
    ?>
    <div id="elc-call-to-action-image-view"><img src="<?php echo $image; ?>"  style="width: 100%"></div>
    <?php
  }
  else {
    ?>
    <div id="elc-call-to-action-image-view"></div>
    <?php
  }

  $text_field = get_post_meta($post->ID,'elc_call_to_action_title',true);
  echo <<<EOS
  <label>タイトル</label><br>
  <input type="text" value="{$text_field}" name="elc_call_to_action_title" style="width:400px;">
EOS;

  $text_field = get_post_meta($post->ID,'elc_call_to_action_caption',true);
  echo <<<EOS
  <br>
  <label>説明</label><br>
  <input type="text" value="{$text_field}" name="elc_call_to_action_caption" style="width:400px;">
EOS;

  $text_field = get_post_meta($post->ID,'elc_call_to_action_link',true);
  echo <<<EOS
  <br>
  <label>リンク</label><br>
  <input type="text" value="{$text_field}" name="elc_call_to_action_link" style="width:400px;">
EOS;

}
endif;

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
  // カスタムフィールドの保存（新規・更新・削除）
  $post_data = $_POST['elc_call_to_action_title'];
  $field_value = get_post_meta($post_id, 'elc_call_to_action_title', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_call_to_action_title', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_call_to_action_title', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_call_to_action_title', $field_value);

  $post_data = $_POST['elc_call_to_action_caption'];
  $field_value = get_post_meta($post_id, 'elc_call_to_action_caption', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_call_to_action_caption', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_call_to_action_caption', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_call_to_action_caption', $field_value);

  $post_data = $_POST['elc_call_to_action_link'];
  $field_value = get_post_meta($post_id, 'elc_call_to_action_link', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_call_to_action_link', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_call_to_action_link', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_call_to_action_link', $field_value);

  $post_data = $_POST['radio_call_to_action'];
  $field_value = get_post_meta($post_id, 'radio_call_to_action', true);
  if ($field_value == "")
    update_post_meta($post_id, 'radio_call_to_action', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'radio_call_to_action', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'radio_call_to_action', $field_value);

	$title_data = isset( $_POST['elc_call_to_action_image'] ) && !empty( $_POST['elc_call_to_action_image'] ) ? $_POST['elc_call_to_action_image'] : null;
  $title_data = remove_home_url($title_data);
  $title_img = get_post_meta( $post_id, 'elc_call_to_action_image' );

	if( !empty( $title_data ) && empty( $title_img ) ) {
		/* 保存 */
		add_post_meta( $post_id, 'elc_call_to_action_image', $title_data, true ) ;
	}
	elseif( !empty( $title_data ) && $title_data !== $title_img ) {
        	/* 更新 */
        	update_post_meta( $post_id, 'elc_call_to_action_image', $title_data ) ;
	}
	else {
        	/* 削除 */
        	delete_post_meta( $post_id, 'elc_call_to_action_image' ) ;
	}
} );

/* Javascript */
add_action( 'admin_print_scripts', function() {
	wp_enqueue_media();
	wp_enqueue_script( 'thk-img-selector-script', get_template_directory_uri() . '/js/thk-img-selector.js', array( 'media-views' ), false );
	wp_localize_script( 'media-views', '_thkImageViewsL10n', array( 'setImage' => __( 'Set image', 'luxeritas' ) ) );

} );

/* CSS */
add_action( 'admin_print_styles', function() {
	wp_register_style( 'thk-img-selector-style', get_template_directory_uri() . '/css/thk-img-selector.css', false, false );
        wp_enqueue_style( 'thk-img-selector-style' );
} );
