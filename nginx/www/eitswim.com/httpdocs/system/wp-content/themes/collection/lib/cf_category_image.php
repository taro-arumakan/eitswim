<?php

add_action ( 'edit_category_form_fields', function($tag){
  $t_id = $tag->term_id;
  $cat_meta = get_option( "cat_$t_id" );
  $image = '';
  if(isset ( $cat_meta['img'])) {
    $image = esc_html($cat_meta['img']);
  }

  $set = __( 'Set image', 'luxeritas' );
  $delete = __( 'Delete image', 'luxeritas' );

  if( !empty( $image ) ) {
    $str_image = "<img src=\"{$image}\"  style=\"width: 100%\">";
  } else {
    $str_image = '';
  }

    echo <<<EOS
  <tr class="form-field">
    <th><label for="upload_image">画像</label></th>
    <td>
    <script>thkImageSelector('{$t_id}', '画像')</script>
    <div id="{$t_id}-form" style="margin-bottom:15px">
      <input id="{$t_id}" type="hidden" name="Cat_meta[img]" value="{$image}" />
      <input id="{$t_id}-set" type="button" class="button" value="{$set}" />
      <input id="{$t_id}-del" type="button" class="button" value="{$delete}" />
    </div>
    <div id="{$t_id}-view">{$str_image}</div>
    </td>
  </tr>
EOS;

});

add_action ( 'edited_term', function($term_id){
  if ( isset( $_POST['Cat_meta'] ) ) {
    $t_id = $term_id;
    $cat_meta = get_option( "cat_$t_id");
    $cat_keys = array_keys($_POST['Cat_meta']);
    foreach ($cat_keys as $key){
      if (isset($_POST['Cat_meta'][$key])){
        $cat_meta[$key] = $_POST['Cat_meta'][$key];
      }
    }
    update_option( "cat_$t_id", $cat_meta );
  }
});

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
