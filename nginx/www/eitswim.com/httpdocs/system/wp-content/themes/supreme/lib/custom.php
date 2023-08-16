<?php
/**
 * その他カスタマイズの関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

//JetPackのCSSとJSを無効化する
add_filter( 'jetpack_implode_frontend_css', '__return_false' );
add_action( 'wp_enqueue_scripts', function(){
  wp_dequeue_script( 'devicepx' );
}, 20 );

/**
 * @param $slag
 * @return string
 */
function dimention_csv($slag){
  $root = dirname( dirname( __FILE__ ) ).'/studioelc';
  $csv = $slag . '.csv';
  $path = "{$root}/csv/{$csv}";
  $file = new SplFileObject($path);
  $file->setFlags(SplFileObject::READ_CSV);
  if(empty($file)) return '';

  $table = '<tbody>'."\n";
  foreach ($file as $data) {
    // 1行当たりのデータ数を取得
    $num = count($data);
    // 取得データを出力
    if($num > 1) {
      $table .= '  <tr>'."\n";
      for ($c=0; $c < $num; $c++) {
        $table .= "    <td>{$data[$c]}</td>\n";
      }
      $table .= '  </tr>'."\n";
    }
  }
  $table .= '</tbody>'."\n";
  return $table;
}

/**
register_taxonomy(
  'post_tag',
  $slugs,
  get_taxonomy('post_tag')
);
*/
/**
 * モデルのカテゴリー・タグ変換用
 */
global $model_categories;
$model_categories = array(
  'new' => 'New',
  'hperformance' => 'High Performance',
  'performance' => 'Performance',
  'everyday' => 'Everyday',
  'relaxed' => 'Relaxed',
);

/* 【出力カスタマイズ】 wp_get_archives の年表記を置き換える */
function my_archives_link($html){
  $html = replace_archive_year($html);
  //$html = str_replace('年','.',$html);
  //$html = str_replace('月','',$html);
  return $html;
}
add_filter('get_archives_link', 'my_archives_link');

function replace_archive_year($content) {
  $content = preg_replace_callback("/(>)(....?)(年)(.*?)(月)(<)/i",
    function ($matches) {
      return $matches[1].date('M Y', strtotime($matches[2].'-' .$matches[4] . '-01')).$matches[6];
    }
    , $content);
  return $content;
}

/**
 * Slugを指定して $post を取得
 * @param $value1
 * @return array|null|WP_Post
 */
function get_post_by_slug($value1) {
  global $post;
  $post_array_slug = array($value1);
  foreach($post_array_slug as $value) {
    $the_slug = $value;
    $args = array(
      'name' => $the_slug,
      'post_type' => 'surfboards',    //投稿タイプの指定
      'orderby' => 'date',
      'order' => 'DESC',
    );
    $my_posts = get_posts($args);
    foreach($my_posts as $post) {
      return $post;
    }
  }
}

if( function_exists( 'catch_that_image' ) === false ):
  function catch_that_image($id = 0, $width = 360, $height = 250) {
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
      $image_src = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full');
      $img = aq_resize($image_src[0], $width, $height, true, true);
      if($img){
        $ret = $img;
      }
      else{
        $ret = $image_src[0];
      }

    } else {
      ob_start();
      ob_end_clean();
      $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);
      $first_img_src = $matches [1] [0];
      $attachment_id = get_attachment_id_by_url( $first_img_src );
      $image_src = wp_get_attachment_image_src($attachment_id, 'full');
      if(!empty($image_src[0])) {
        $ret = $image_src[0];
      }
    }
    return $ret;
  }
endif;
