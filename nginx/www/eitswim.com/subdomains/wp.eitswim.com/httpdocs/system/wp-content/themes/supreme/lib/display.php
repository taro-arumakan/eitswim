<?php
/**
 * タイトル出力,パンくずリスト,ページング等の関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

/**
 * URLからcategoryを除去
 */
add_filter('user_trailingslashit', function($link){
  return str_replace("/category/", "/", $link);
});
add_action('init', function(){
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
});
add_filter('generate_rewrite_rules', function($wp_rewrite){
  $new_rules = array('(.+)/page/(.+)/?' => 'index.php?category_name='.$wp_rewrite->preg_index(1).'&paged='.$wp_rewrite->preg_index(2));
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
});

/** 自動挿入されるpタグとbrタグを削除する **/
remove_filter('the_excerpt', 'wpautop');
remove_filter('the_content', 'wpautop');

/** ビジュアルエディタ <-> テキスト での変換を無効にする */
add_filter( 'tiny_mce_before_init', function($init_array){
  $init_array['valid_elements']          = '*[*]';
  $init_array['extended_valid_elements'] = '*[*]';
  $init_array['indent']                  = true;
  $init_array['wpautop']                 = false;
  $init_array['force_p_newlines']        = false;

  return $init_array;
} );

/**
 * Rewrite Endpoint
 *
 */
add_action( 'init', function(){
  add_rewrite_endpoint( 'section', EP_PERMALINK );
});

/**
 * Custom Templates
 *
 */
add_action( 'template_include', function($template){
  //if(is_404()) {
    $news_slug = get_option( 'elc_news_page_news_slug', 'news' );

    $url = $_SERVER['REQUEST_URI'];
    $url = substr($url, 1, strlen($news_slug));
    if($url===$news_slug) {
      global $is_category;
      $is_category = false;
      global $is_tag;
      $is_tag = false;
      global $is_archive;
      $is_archive = false;
      global $is_news;
      $is_news = true;
      $template = get_index_template();
    }
  //}
  return $template;
});

/**
<li><a href="/">Home</a></li>
<li><a href="/news/">News</a></li>
<li><a href="/news/press/">Press</a></li>
<li class="active">デジタルマーケティングにおいてデザイナーが持つべき2つの視点</li>
 **/
// パンくずリスト
if( function_exists('elc_breadcrumb') === false ):
  function elc_breadcrumb(){
    global $post;
    global $is_news;
    $str = '';
    if(!elc_is_home()&&!is_admin()){ /* !is_admin は管理ページ以外という条件分岐 */
      $str.= '<li><a href="' . home_url('/') .'">Home</a></li>'."\n";
      /* 投稿のページ */
      if(is_single()){
        if($post->post_type!=='post') {
          $str .= '<li><a href="/'.$post->post_type.'"> '.$post->post_type.' </a></li>' . "\n";
        } else {
          $categories = get_ordered_terms($post->ID);
          $str .= '<li><a href="'.home_url('/').'news"> News </a></li>' . "\n";
          foreach((array)$categories as $category){
            $cat = get_category_link($category->term_id);
            $cat = str_replace('/category/', '/', $cat);
            $str.='<li><a href="'. $cat.'">'. $category->name. '</a></li>'."\n";
            break;
          }
        }
        //$str.='<li><a href="'. get_permalink($post->ID).'">'. $post->post_title. '</a></li>'."\n";
        $str.='<li>'. $post->post_title. '</li>'."\n";
      }

      /* 固定ページ */
      /**
      <li><a href="/">Home</a></li>
      <li class="active">Schedule</li>
       */
      elseif(is_page()){
        if($post -> post_parent != 0 ){
          $ancestors = array_reverse(get_post_ancestors( $post->ID ));
          foreach($ancestors as $index=>$ancestor){
            $slug = get_post($ancestor)->post_name;
            $a = get_the_title($ancestor);
            $str.='<li><a href="'. get_permalink($ancestor).'">'. get_the_title($ancestor) .'</a></li>'."\n";
          }
        }
        $str.='<li class="active">'. ucfirst($post -> post_title) .'</li>'."\n";
      }
      elseif($is_news){
        $news_title = get_option( 'elc_news_page_news_title', 'News' );
        $str.='<li class="active">'. ucfirst($news_title) .'</li>'."\n";
      }

      /* カテゴリページ */
      elseif(is_category()) {
        $cat = get_queried_object();
        $str .= '<li><a href="'.home_url('/').'news"> News </a></li>' . "\n";
        if($cat -> parent != 0){
          $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
          foreach($ancestors as $ancestor){
            $u = get_category_link($ancestor);
            //$u = str_replace('/category/', '/', $u);
            $str.='<li><a href="'. $u .'">'. get_cat_name($ancestor) .'</a></li>'."\n";
          }
        }
        $u = get_category_link($cat -> cat_ID);
        //$u = str_replace('/category/', '/', $u);
        $str.='<li><a href="'. $u.'">'. $cat -> name .'</a></li>'."\n";
      }

      /* タグページ */
      elseif(is_tag()){
        //$str .= '<li><a href="/news"> News </a></li>' . "\n";
        $str.='<li>'. single_tag_title( 'Tag：' , false ). '</li>'."\n";
      }

      /* 時系列アーカイブページ */
      elseif(is_date()){
        if(get_query_var('day') != 0){
          $str.='<li><a href="'. get_year_link(get_query_var('year')). '">' . get_query_var('year'). '</a></li>'."\n";
          $month = date('M', strtotime(get_query_var('year') .'-' . get_query_var('monthnum') . '-' . get_query_var('day')));
          $str.='<li><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. $month .'</a></li>'."\n";
          $str.='<li>'. get_query_var('day'). '</li>'."\n";
        } elseif(get_query_var('monthnum') != 0){
          $str.='<li><a href="'. get_year_link(get_query_var('year')) .'">'. get_query_var('year') .'</a></li>'."\n";
          $month = date('M', strtotime(get_query_var('year') .'-' . get_query_var('monthnum') . '-01'));
          $str.='<li><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. $month .'</a></li>'."\n";
        } else {
          $str.='<li><a href="'. get_year_link(get_query_var('y')). '">' . get_query_var('y'). '</a></li>'."\n";
        }
      }

      /* 時系列アーカイブページ（日本語表記）
      elseif(is_date()){
        if(get_query_var('day') != 0){
          $str.='<li><a href="'. get_year_link(get_query_var('year')). '">' . get_query_var('year'). '年</a></li>'."\n";
          $str.='<li><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. get_query_var('monthnum') .'月</a></li>'."\n";
          $str.='<li>'. get_query_var('day'). '日</li>'."\n";
        } elseif(get_query_var('monthnum') != 0){
          $str.='<li><a href="'. get_year_link(get_query_var('year')) .'">'. get_query_var('year') .'年</a></li>'."\n";
          $str.='<li>'. get_query_var('monthnum'). '月</li>'."\n";
        } else {
          $str.='<li>'. get_query_var('year') .'年</li>'."\n";
        }
      }
      */

      /* 投稿者ページ */
      elseif(is_author()){
        $str .='<li>投稿者 : '. get_the_author_meta('display_name', get_query_var('author')).'</li>'."\n";
      }

      /* 添付ファイルページ */
      elseif(is_attachment()){
        if($post -> post_parent != 0 ){
          $str.= '<li><a href="'. get_permalink($post -> post_parent).'">'. get_the_title($post -> post_parent) .'</a></li>'."\n";
        }
        $str.='<li><a href="'. get_permalink($post -> ID).'">'. $post -> post_title .'</a></li>'."\n";
      }

      /* 検索結果ページ */
      elseif(is_search()){
        $str.='<li>検索結果：'. get_search_query() . '</li>'."\n";
      }

      /* 404 Not Found ページ */
      elseif(is_404()){
        $str.= '<li>404 Not Found</li>'."\n";
      }

      /* その他のページ */
      else{
        $str.='<li>'. wp_title('', false) .'</li>'."\n";
      }

    }
    echo $str;
  }
endif;

if( function_exists('elc_get_title') === false ):
  function elc_get_title($tag = 'h1', $class = '', $style = ''){
    global $post;
    global $is_news;
    if(!empty($style)) $style = ' style="' . $style .'"';
    if(!empty($class)) $class = ' class="' . $class .'"';
    $str ='';
    if(!elc_is_home()&&!is_admin()){ /* !is_admin は管理ページ以外という条件分岐 */
      /* 投稿のページ */
      if(is_single()){
        $str .= '<'.$tag.$class.$style.'>' . $post->post_title . '</'.$tag.'>';
      }

      //$str.='<li>'. wp_title('', false) .'</li>'."\n";

      /* 固定ページ */
      elseif(is_page()){
        $year = $_GET['y'];
        if(!empty($year)) {
          //$str .= '<'.$tag.$class.'>' . wp_title('', true) . ' ' . $year . '</'.$tag.'>';
          $str .= '<'.$tag.$class.$style.'>' . $post->post_title . ' ' . $year . '</'.$tag.'>';
        } else {
          //$str .= '<'.$tag.$class.'>' . wp_title('', true) . '</'.$tag.'>';
          $str .= '<'.$tag.$class.$style.'>' . $post->post_title . '</'.$tag.'>';
        }
      }
      /* 投稿ページを固定ページとした場合 */
      elseif($is_news){
        $news_title = get_option( 'elc_news_page_news_title', 'News' );
        $str .= '<'.$tag.$class.$style.'>' . $news_title . '</'.$tag.'>';
      }

      /* カテゴリページ */
      elseif(is_category()) {
        $cat = get_queried_object();
        $str .= '<'.$tag.$class.$style.'>' . $cat -> name . '</'.$tag.'>';
      }

      /* タグページ */
      elseif(is_tag()){
        $str.='<'.$tag.$class.$style.'>Tag：'. single_tag_title( '' , false ). '</'.$tag.'>'."\n";
      }

      /* 時系列アーカイブページ */
      elseif(is_date()){
        if(get_query_var('day') != 0){
          $title = date('d M Y', strtotime(get_query_var('year') .'-' . get_query_var('monthnum') . '-' . get_query_var('day')));
          $str .= '<'.$tag.$class.$style.'>'.$title.'</'.$tag.'>';
        } elseif(get_query_var('monthnum') != 0){
          $title = date('M Y', strtotime(get_query_var('year') .'-' . get_query_var('monthnum') . '-01'));
          $str .= '<'.$tag.$class.$style.'>'.$title.'</'.$tag.'>';
        } else {
          $str.='<'.$tag.$class.$style.'>'. get_query_var('year') .'</'.$tag.'>';
        }
      }

      /* 時系列アーカイブページ（日本語表記）
      elseif(is_date()){
        if(get_query_var('day') != 0){
          $str.='<h1>' . get_query_var('year'). '年';
          $str.= get_query_var('monthnum') .'月';
          $str.= get_query_var('day'). '日</h1>';
        } elseif(get_query_var('monthnum') != 0){
          $str.='<h1>'. get_query_var('year') .'年';
          $str.= get_query_var('monthnum'). '月</h1>';
        } else {
          $str.='<h1>'. get_query_var('year') .'年</h1>';
        }
      }
      */

      /* 添付ファイルページ */
      elseif(is_attachment()){
        $str.= '<'.$tag.$class.$style.'>'. $post -> post_title .'</'.$tag.'>';
      }

      /* 検索結果ページ */
      elseif(is_search()){
        $str.='<'.$tag.$class.$style.'>検索結果：'. get_search_query() . '</'.$tag.'>'."\n";
      }

      /* 404 Not Found ページ */
      elseif(is_404()){
        $str.= '<'.$tag.$class.$style.'>404 Not Found</'.$tag.'>'."\n";
      }

      /* その他のページ */
      else{
        $str.='<'.$tag.$class.$style.'>'. wp_title('', false) .'</'.$tag.'>'."\n";
      }

    }
    echo $str;
  }
endif;

/**
 * ページネーション
 * 使い方
//Pagination
if (function_exists("pagination")) {
pagination($additional_loop->max_num_pages);
}
 * @param string $pages
 */
if( function_exists('elc_pagination') === false ):
  function elc_pagination($pages = '') {
    global $paged;//現在のページ値
    if(empty($paged)) $paged = 1;//デフォルトのページ

    //リンクURL用
    global $is_category;
    global $is_tag;
    if($is_category || $is_tag) {
      $pg = 'page';
    } else {
      $pg = 'pg';
    }

    if($pages == '') {
      global $wp_query;
      $pages = $wp_query->max_num_pages;//全ページ数を取得
      if(!$pages) { //全ページ数が空の場合は、１とする
        $pages = 1;
      }
    }

    //if(1 != $pages) { //全ページが１でない場合はページネーションを表示する
    echo '<li>'."\n";
    if($paged > 1) {
      //Prev：現在のページ値が１より大きい場合
      //echo '		<a aria-label="Previous" href="'.get_pagenum_link($paged - 1).'">'."\n";
      $link = $_SERVER['REDIRECT_URL'].'?'.$pg.'=' . ($paged - 1);
      echo '		<a aria-label="Previous" href="'.$link.'">'."\n";
      echo '		  <span aria-label="true"><i class="fa fa-angle-left"></i></span>'."\n";
      echo '		</a>'."\n";
    } else {
      echo '		<a aria-label="Previous">'."\n";
      echo '		  <span aria-hidden="true"><i class="fa fa-angle-left"></i></span>'."\n";
      echo '		</a>'."\n";
    }
    echo "	</li>\n";

    $show_pages = 10;
    if($paged < $show_pages) {
      $start = 1;
      if($pages < $show_pages) {
        $end = $pages;
      } else {
        $end = $show_pages;
      }
    } else {
      $start = $paged - 4;
      $end = $paged + 5;
      if($end > $pages) {
        $end = $pages;
        $start = $end - $show_pages;
      }
    }
    for ($i=$start; $i <= $end; $i++) {
      $active = '';
      if($paged == $i) {
        $active = 'class="active"';
      }
      //echo '			<li '.$active.'><a href="'.get_pagenum_link($i).'">'.$i."</a></li>\n";
      $link = $_SERVER['REDIRECT_URL'].'?'.$pg.'=' . $i;
      echo '			<li '.$active.'><a href="'.$link.'">'.$i."</a></li>\n";
    }

    echo '	<li>'."\n";
    if ($paged < $pages) {
      //Next：総ページ数より現在のページ値が小さい場合は表示
      //echo '		<a aria-label="Next" href="'.get_pagenum_link($paged + 1).'">'."\n";
      $link = $_SERVER['REDIRECT_URL'].'?'.$pg.'=' . ($paged + 1);
      echo '		<a aria-label="Next" href="'.$link.'">'."\n";
      echo '		<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>'."\n";
      echo '		</a>'."\n";
    } else {
      echo '		<a aria-label="Next">'."\n";
      echo '		<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>'."\n";
      echo '		</a>'."\n";
    }
    echo "	</li>\n";
    //}
  }
endif;

if( function_exists('get_ordered_terms') === false ):
  function get_ordered_terms( $id = 0, $orderby = 'term_id', $order = 'ASC', $taxonomy = 'category' ) {
    $terms = get_the_terms( $id, $taxonomy );
    if ( $terms ) {
      $ordered = array();
      foreach ( $terms as $term ) {
        if ( isset( $term->$orderby ) ) {
          $ordered[$term->$orderby] = $term;
        }
      }
      if ( strtoupper( $order ) == 'DESC' ) {
        $func = 'krsort';
      } else {
        $func = 'ksort';
      }
      $func( $ordered );
      return $ordered;
    }
  }
endif;

if( function_exists('get_anchor') === false ):
  function get_anchor($cat) {
    $args = array(
      'hide_empty' => 0,
      'child_of'           => $cat->parent,
      'hierarchical'       => 0,
      'title_li'           => '',
      'current_category'   => 0,
      'orderby'						 => 'term_order',
    );
    $categories = get_categories( $args );
    $index=0;
    foreach($categories as $category) {
      $index++;
      $anchor = '#category' . $index;
      if($category->term_id == $cat->term_id) {
        return $anchor;
      }
    }
    return '';
  }
endif;

/**
 * 	抜粋する文字数を設定
 * @param $length
 * @return int
 */
function new_excerpt_mblength($length) {
  return 110;
}
add_filter('excerpt_mblength', 'new_excerpt_mblength');

/**
 * 抜粋表示の終わりを指定
 * @param $more
 * @return string
 */
function new_excerpt_more($more) {
  return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

//本文抜粋を取得する関数
function get_the_custom_excerpt($length = 70, $id = null) {
  if(!empty($id)) {
    $content = get_the_content($id);
  } else {
    $content = get_the_content();
  }
  $save_length = mb_strlen($content);
  //descriptionが設定されていれば、優先する
  if(!empty($id)) {
    $description = get_post_meta($id, _aioseop_description, true);
    if(!empty($description)) {
      $content = $description;
      $save_length = mb_strlen($content);
    }
  }

  $content =  preg_replace('/<!--more-->.+/is',"",$content); //moreタグ以降削除
  global $shortcode_tags;
  $shortcode_tags['mwform_formkey'] = 'contact_form_shorcode';
  $content =  strip_shortcodes($content);//ショートコード削除
  $content =  strip_tags($content);//タグの除去
  $content =  str_replace("&nbsp;","",$content);//特殊文字の削除（今回はスペースのみ）
  $content =  mb_substr($content,0,$length);//文字列を指定した長さで切り取る
  $new_length = mb_strlen($content);
  if($new_length < $save_length) {
    //$content .= '...';
  }
  return $content;
}

/**
 * カスタムメニューを設定
 */
if( function_exists('custom_nav_menus') === false ):
function custom_nav_menus() {
  register_nav_menus( array('global-menu' => 'Global menu'));
}
custom_nav_menus();
endif;
