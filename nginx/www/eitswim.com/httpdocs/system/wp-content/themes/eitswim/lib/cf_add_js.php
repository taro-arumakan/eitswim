<?php

/*---------------------------------------------------------------------------
 * JavaScript追加ボックス追加
 *---------------------------------------------------------------------------*/

/* ボックス追加 */
add_action('admin_menu', function(){
  //固定ページの subtitle を記入する
  add_meta_box( 'elc_add_javascript', 'JavaScriptの追加', 'elc_custom_field_add_javascript', 'page', 'advanced' );
});

/* 投稿画面に表示するフォーム */
if( function_exists( 'elc_custom_field_add_javascript' ) === false ):
  function elc_custom_field_add_javascript() {
    global $post;

    $options = array('ON','OFF');
    $n = count($options);
    $radio_field = get_post_meta($post->ID,'elc_add_js',true);
    echo '<label for="radio_field">ON：JavaScriptを追加します。</label><br>';
    for ($i=0; $i<$n; $i++) {
      $option = $options[$i];
      if ($option==$radio_field) {
        echo '<input type="radio" name="elc_add_js" value="'. esc_html($option) .'" checked > '. $option .' ';
      } else {
        echo '<input type="radio" name="elc_add_js" value="'. esc_html($option) .'" > '. $option .' ';
      }
    }
    //追加のJavaScript設定
    $text_field = get_post_meta($post->ID,'elc_add_js_content',true);
    echo <<<EOS
  <div id="elc_add_js_setting">
  <br><label for="text_field">追加のスクリプト</label><br>  
  <textarea rows="6" cols="40" name="elc_add_js_content" id="excerpt" style="height:100%;">{$text_field}</textarea>
  </div>
EOS;

    echo <<<EOS
  <script>
  jQuery(function($) {
    if($('input[name=elc_add_js]:checked').val() === 'ON') {
      $('#elc_add_js_setting').show();
    } else {
      $('#elc_add_js_setting').hide();
    }
    $('input[name=elc_add_js]').on('change', function(){
      if($(this).val()==='ON') {
        $('#elc_add_js_setting').show();
      } else {
        $('#elc_add_js_setting').hide();
      }
    });
  });
  </script>
EOS;

  }
endif;

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
  $post_data = $_POST['elc_add_js'];
  $field_value = get_post_meta($post_id, 'elc_add_js', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_add_js', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_add_js', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_add_js', $field_value);

  $post_data = $_POST['elc_add_js_content'];
  $field_value = get_post_meta($post_id, 'elc_add_js_content', true);
  if ($field_value == "")
    update_post_meta($post_id, 'elc_add_js_content', $post_data);
  elseif($post_data != $field_value)
    update_post_meta($post_id, 'elc_add_js_content', $post_data);
  elseif($post_data=="")
    delete_post_meta($post_id, 'elc_add_js_content', $field_value);

});
