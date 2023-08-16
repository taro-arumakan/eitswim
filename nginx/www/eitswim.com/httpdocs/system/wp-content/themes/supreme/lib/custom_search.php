<?php

if( function_exists( 'get_search_result' ) === false ):

  function get_search_result($query_string, $order = 'DESC') {
    global $paged;
    $page_count = get_option('posts_per_page');
    if(empty($page_count)) {
      $page_count = 10;
    }
    //post_typeが"post","page"の場合のみ、検索対象とする
    $post_types = array('post','page');
    $ordered = array();
    $query_string=esc_attr($query_string);
    $blogs = get_blog_list(0,'all');
    foreach($blogs as $blog) {
      switch_to_blog($blog['blog_id']);
      $search = new WP_Query($query_string);
      if($search->found_posts>0) {
        foreach($search->posts as $post) {
          if(in_array($post->post_type, $post_types)) {
            $item = array();
            $item['blog_id'] = $blog['blog_id'];
            $item['post'] = $post;
            $ordered[$post->post_date.str_pad($post->ID, 5, 0, STR_PAD_LEFT).$blog['blog_id']] = $item;
          }
        }
      }
    }
    restore_current_blog(); // Reset settings to the current blog
    if ( strtoupper( $order ) == 'DESC' ) {
      $func = 'krsort';
    } else {
      $func = 'ksort';
    }
    $func($ordered);

    // スタートのポジションを計算する
    if ($paged > 1) {
      $start = ($paged * $page_count) - $page_count;
    } else {
      $start = 0;
    }
    $result = array_slice($ordered, $start, $page_count);

    global $wp_query;
    $mod = count($ordered) % $page_count;
    $add = 0;
    if($mod>0) $add = 1;
    $wp_query->max_num_pages = floor(count($ordered) / $page_count) + $add;

    return $result;

  }
endif;
