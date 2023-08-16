<?php
/**
 * Created by PhpStorm.
 * User: Studioelc
 * Date: 2017/08/07
 */

/**
 * 管理画面への表示処理
 */

if( function_exists( 'elc_image_callback' ) === false ):
  /* 投稿画面に表示するフォーム （メディアファイル用）*/
  function elc_image_callback($post, $metabox) {
    global $post;
    $meta_id = $metabox['args']['id'];
    $meta_title = $metabox['args']['title'];
    /* 既に値がある場合、その値をフォームに出力 */
    $image = '';
    $og_image = get_post_meta( $post->ID, $meta_id, true );

    if( !empty( $og_image ) ) {
      $image = get_post_meta( $post->ID, $meta_id, true );
    }
    $set = __( 'Set image', 'luxeritas' );
    $delete = __( 'Delete image', 'luxeritas' );

    echo <<<EOS
    <script>thkImageSelector('{$meta_id}', '{$meta_title}')</script>
    <div id="{$meta_id}-form" style="margin-bottom:15px">
      <input id="{$meta_id}" type="hidden" name="{$meta_id}" value="{$image}" />
      <input id="{$meta_id}-set" type="button" class="button" value="{$set}" />
      <input id="{$meta_id}-del" type="button" class="button" value="{$delete}" />
    </div>
EOS;
    if( !empty( $image ) ) {
      ?>
    <div id="<?php echo $meta_id;?>-view"><img src="<?php echo $image; ?>"  style="width: 100%"></div>
      <?php
    }
    else {
      ?>
    <div id="<?php echo $meta_id;?>-view"></div>
      <?php
    }
  }

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

endif;

add_action('admin_menu', function(){
  // メタボックス追加

  //固定ページの subtitle を記入する
  add_meta_box( 'elc_seo_title', 'SEO Title 設定', 'elc_custom_field_seo_title', 'post', 'normal' ,'high');
  add_meta_box( 'elc_seo_title', 'SEO Title 設定', 'elc_custom_field_seo_title', 'page', 'normal' ,'high');

  //投稿ページ、固定ページの meta description を自分で記入する
  $description_enable = get_theme_mod( 'description_enable', true );
  if($description_enable) {
    add_meta_box( 'elc_description', 'meta description 設定', 'elc_custom_field_description', 'post', 'normal','high' );
    add_meta_box( 'elc_description', 'meta description 設定', 'elc_custom_field_description', 'page', 'normal','high' );
  }

  //投稿ページ、固定ページの keywords を自分で記入する('input')
  $meta_keywords = get_theme_mod( 'meta_keywords', 'input' );
  if($meta_keywords==='input') {
    add_meta_box( 'elc_keywords', 'meta keywords 設定', 'elc_custom_field_keywords', 'post', 'normal','high' );
    add_meta_box( 'elc_keywords', 'meta keywords 設定', 'elc_custom_field_keywords', 'page', 'normal','high' );
  }

});

/**
 * SEO Title 設定
 */
function elc_custom_field_seo_title() {
  global $post;

  $text_field = get_post_meta($post->ID,'elc_seo_title',true);

  echo <<<EOS
  <input type="text" value="{$text_field}" name="elc_seo_title" style="width:400px;">
EOS;

}


/**
 * meta description 設定
 */
function elc_custom_field_description() {
  global $post;

  $text_field = get_post_meta($post->ID,'elc_description',true);

  echo <<<EOS
  <textarea rows="1" cols="40" name="elc_description" id="excerpt">{$text_field}</textarea>
EOS;

}

/**
 * meta keywords 設定
 */
function elc_custom_field_keywords() {
  global $post;

  $text_field = get_post_meta($post->ID,'elc_keywords',true);

  echo <<<EOS
  <input type="text" value="{$text_field}" name="elc_keywords" style="width:400px;">
EOS;

}

/**
 * Sub Title 設定
 */
function elc_custom_field_sub_title() {
  global $post;

  $text_field = get_post_meta($post->ID,'elc_sub_title',true);

  echo <<<EOS
  <input type="text" value="{$text_field}" name="elc_sub_title" style="width:400px;">
EOS;

}


/**
 * 保存処理
 */

add_action('save_post', function($post_id){

  $post_data = $_POST['elc_description'];
  $field_value = get_post_meta($post_id, 'elc_description', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_description', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_description', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_description', $field_value);

  $post_data = $_POST['elc_keywords'];
  $field_value = get_post_meta($post_id, 'elc_keywords', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_keywords', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_keywords', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_keywords', $field_value);

  $post_data = $_POST['elc_seo_title'];
  $field_value = get_post_meta($post_id, 'elc_seo_title', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_seo_title', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_seo_title', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_seo_title', $field_value);

});

if( function_exists('custom_admin_menu') === false ):
  locate_template('lib/cf_title_image.php', true);
  locate_template('lib/cf_service_slider.php', true);
  locate_template('lib/cf_add_js.php', true);
  locate_template('lib/cf_call_to_action.php', true);
endif;
