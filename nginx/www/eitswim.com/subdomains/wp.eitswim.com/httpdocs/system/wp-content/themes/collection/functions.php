<?php

//Image Resize Plugin
locate_template('lib/aq_resizer.php', true);

//外観カスタム
locate_template('lib/custom.php', true);

//カスタム投稿追加
locate_template('lib/custom_post.php', true);

//カスタムフィールド追加
locate_template('lib/custom_field.php', true);

//表示カスタム
locate_template('lib/display.php', true);

//WIDGETカスタム
locate_template('lib/widgets.php', true);

//カスタム検索
//locate_template('lib/custom_search.php', true);

add_filter( 'jetpack_enable_opengraph', '__return_false', 99 );

/**
 * ファイルの更新時刻をバージョンとする
 * @param $url
 * @return string
 * $ver = file_ver(SDEL.'/images/slide02.jpg');
 */
function file_ver($url) {
    $ver = file_time($url, dirname( __FILE__ ));
    echo empty($ver)?'':'?v='.$ver;
}

/**
 * カスタム投稿タイプをカテゴリーIDから取得
 * @param $cat_ID
 * @return string
 */
function get_custom_filed_name($cat_ID) {
  $args = array(
    'post_type' => array('city','surf'),
    'orderby'  => 'ID',
    'posts_per_page' => -1,
    'order' => 'ASC',
  );
  $args['meta_query'] = array(
    array( 'key'=>'elc_item_category',
      'value'=>$cat_ID,
      'compare'=>'=',
    )
  );
  $posts = get_posts( $args );
  $fields = array();
  foreach($posts as $item) {
    if(empty($fields[$item->post_type])) {
      $fields[$item->post_type] = 1;
    } else {
      $fields[$item->post_type] += 1;
    }
  }
  if(!empty($fields)) {
    //最多のタイプを取得
    $max = max($fields);
    $find = array_keys($fields, $max);
    return $find[0];
  } else {
    return '';
  }
}

/**
 * URLからカテゴリー画像を取得
 * @param $url
 * @return string
 */
function get_category_image($url) {
  $slug = basename($url);
  $cat_id = get_category_by_slug($slug);
  $cat_id = $cat_id->cat_ID;
  $cat_meta = get_option("cat_{$cat_id}");
  $image = '';
  if(isset ( $cat_meta['img'])) {
    $image = esc_html($cat_meta['img']);
  }
  return $image;
}

/**
 * 同じカスタムカテゴリーの前の記事を取得
 * @return bool|int|string
 */
function get_custom_previous_post() {
  global $post;
  $cat_ID = get_post_meta( $post->ID, 'elc_item_category', true);
  $args = array(
    'post_type' => array('city','surf'),
    'orderby'  => 'ID',
    'posts_per_page' => -1,
    'order' => 'ASC',
  );
  $args['meta_query'] = array(
    array( 'key'=>'elc_item_category',
      'value'=>$cat_ID,
      'compare'=>'=',
    )
  );
  $posts = get_posts( $args );
  $fields = array();
  foreach($posts as $item) {
    $fields[$item->ID] = $item->ID;
  }
  $tmp=false;
  foreach($fields as $k=>$v) {
    if($k==$post->ID) {
      if($tmp===false) {
        return $tmp;
      } else {
        return get_post($tmp);
      }
    }
    $tmp=$k;
  }
  return false;
}

/**
 * 同じカスタムカテゴリーの次の記事を取得
 * @return bool|int|string
 */
function get_custom_next_post() {
  global $post;
  $cat_ID = get_post_meta( $post->ID, 'elc_item_category', true);
  $args = array(
    'post_type' => array('city','surf'),
    'orderby'  => 'ID',
    'posts_per_page' => -1,
    'order' => 'ASC',
  );
  $args['meta_query'] = array(
    array( 'key'=>'elc_item_category',
      'value'=>$cat_ID,
      'compare'=>'=',
    )
  );
  $posts = get_posts( $args );
  $fields = array();
  foreach($posts as $item) {
    $fields[$item->ID] = $item->ID;
  }
  $tmp=false;
  foreach($fields as $k=>$v) {
    if($k==$post->ID) {
      $tmp=$k;
    } elseif($tmp!==false) {
      return get_post($k);
    }
  }
  return false;
}

/*
 * タイトルを変更する
 */
function custom_document_title_parts($title) {
  $ret = $title;
  switch_to_blog(1);
  $site = get_bloginfo('name');
  restore_current_blog();
  if(!empty($ret['site'])) {
    $ret['site'] = $site;
  }
  if(!empty($ret['title']) && $ret['title'] == 'ページが見つかりませんでした') {
    $ret['title'] = '404 Not Found';
  }

  return $ret;
}

function main_home_url($url) {
  switch_to_blog(1);
  $site = home_url($url);
  restore_current_blog();
  return $site;
}
