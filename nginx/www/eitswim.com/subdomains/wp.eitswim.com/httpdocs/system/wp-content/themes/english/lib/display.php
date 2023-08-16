<?php
/**
 * タイトル出力,パンくずリスト,ページング等の関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

/**
 * 検索でカスタム投稿タイプの投稿を検索対象から除く
 */
add_filter( 'pre_get_posts', function($query){
  if($query->is_search() && $query->is_main_query() && ! is_admin()) {
    $query->set('post_type', array('post', 'page') );
  }
});

/**
 * Admin Login CSSが効かない対応 2019.03.01
 */
add_action( 'login_enqueue_scripts', function() {
  $base = site_url();
  echo "<link rel='stylesheet' id='dashicons-css'  href='{$base}/wp-includes/css/dashicons.min.css' type='text/css' media='all' />\n";
  echo "<link rel='stylesheet' id='buttons-css'  href='{$base}/wp-includes/css/buttons.min.css' type='text/css' media='all' />\n";
  echo "<link rel='stylesheet' id='forms-css'  href='{$base}/wp-admin/css/forms.min.css' type='text/css' media='all' />\n";
  echo "<link rel='stylesheet' id='l10n-css'  href='{$base}/wp-admin/css/l10n.min.css' type='text/css' media='all' />\n";
  echo "<link rel='stylesheet' id='login-css'  href='{$base}/wp-admin/css/login.min.css' type='text/css' media='all' />\n";
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
      $str.= '<li><a href="' . home_url('/') .'">HOME</a></li>'."\n";
      /* 投稿のページ */
      if(is_single()){
        if($post->post_type!=='post') {
          $str .= '<li><a href="/'.$post->post_type.'"> '.$post->post_type.' </a></li>' . "\n";
        } else {
          $categories = get_ordered_terms($post->ID);
          $news_slug = get_option( 'elc_news_page_news_slug', 'news' );
          $news_title = get_option( 'elc_news_page_news_title', 'NEWS' );
          $str .= '<li><a href="'.home_url('/').$news_slug.'"> '.strtoupper($news_title).' </a></li>' . "\n";
          /**
          foreach((array)$categories as $category){
            $cat = get_category_link($category->term_id);
            $cat = str_replace('/category/', '/', $cat);
            $str.='<li><a href="'. $cat.'">'. $category->name. '</a></li>'."\n";
            break;
          }
           */
        }
        $str.='<li class="active"><a href="'. get_permalink($post->ID).'">'. $post->post_title. '</a></li>'."\n";
        //$str.='<li>'. $post->post_title. '</li>'."\n";
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
            $str.='<li><a href="'. get_permalink($ancestor).'">'. strtoupper(get_the_title($ancestor)) .'</a></li>'."\n";
          }
        }
        $str.='<li class="active">'. strtoupper($post -> post_title) .'</li>'."\n";
      }
      elseif($is_news){
        $news_slug = get_option( 'elc_news_page_news_slug', 'news' );
        $news_title = get_option( 'elc_news_page_news_title', 'MEDIA' );
        $str .= '<li class="active"><a href="'.home_url('/').$news_slug.'"> '.$news_title.' </a></li>' . "\n";
        //$news_title = get_option( 'elc_news_page_news_title', 'News' );
        //$str.='<li class="active">'. ucfirst($news_title) .'</li>'."\n";
      }

      /* カテゴリページ */
      elseif(is_category()) {
        $cat = get_queried_object();
        $news_slug = get_option( 'elc_news_page_news_slug', 'news' );
        $news_title = get_option( 'elc_news_page_news_title', 'NEWS' );
        $str .= '<li><a href="'.home_url('/').$news_slug.'"> '.$news_title.' </a></li>' . "\n";
        if($cat -> parent != 0){
          $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
          foreach($ancestors as $ancestor){
            $u = get_category_link($ancestor);
            //$u = str_replace('/category/', '/', $u);
            $str.='<li><a href="'. $u .'">'. strtoupper(get_cat_name($ancestor)) .'</a></li>'."\n";
          }
        }
        $u = get_category_link($cat -> cat_ID);
        //$u = str_replace('/category/', '/', $u);
        $str.='<li><a href="'. $u.'">'. strtoupper($cat -> name) .'</a></li>'."\n";
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
        $str.='<li>Search Result：'. get_search_query() . '</li>'."\n";
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
      elseif(is_page() && !empty($_GET['y'])){
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
        $str.='<'.$tag.$class.$style.'>Search Result：'. get_search_query() . '</'.$tag.'>'."\n";
      }

      /* 404 Not Found ページ */
      elseif(is_404()){
        $str.= '<'.$tag.$class.$style.'>404 Not Found</'.$tag.'>'."\n";
      }

      /* その他のページ */
      else{
        global $is_lineup;
        global $is_lineup_area;

        if($is_lineup) {
          $str.='<'. $tag . $class .$style.'><a href="/lineup">上映作品</a></'.$tag.'>'."\n";
        } else
        if($is_lineup_area) {
          $str.='<'. $tag . $class .$style.'><a href="/lineup">上映作品</a></'.$tag.'>'."\n";
        } else {
          $str .= '<' . $tag . $class . $style.'>' . wp_title( '', false ) . '</' . $tag . '>' . "\n";
        }
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
    $pg = 'page';

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
      //$link = $_SERVER['REDIRECT_URL'].'?'.$pg.'=' . ($paged - 1);
      $link = '?'.$pg.'=' . ($paged - 1);
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
      //$link = $_SERVER['REDIRECT_URL'].'?'.$pg.'=' . $i;
      $link = '?'.$pg.'=' . $i;
      echo '			<li '.$active.'><a href="'.$link.'">'.$i."</a></li>\n";
    }

    echo '	<li>'."\n";
    if ($paged < $pages) {
      //Next：総ページ数より現在のページ値が小さい場合は表示
      //echo '		<a aria-label="Next" href="'.get_pagenum_link($paged + 1).'">'."\n";
      //$link = $_SERVER['REDIRECT_URL'].'?'.$pg.'=' . ($paged + 1);
      $link = '?'.$pg.'=' . ($paged + 1);
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

function search_pagination($pages = '') {
  global $paged;//現在のページ値
  if(empty($paged)) $paged = 1;//デフォルトのページ

  $url = $_SERVER['REQUEST_URI'];
  $url = explode('&',$url);
  $url = $url[0];

  //リンクURL用
  global $is_category;
  global $is_tag;
  if($is_category || $is_tag) {
    $pg = 'page';
  } else {
    $pg = 'page';
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
    $link = $url.'&paged='.$paged;
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
    $link = $url.'&paged='.$i;
    echo '			<li '.$active.'><a href="'.$link.'">'.$i."</a></li>\n";
  }

  echo '	<li>'."\n";
  if ($paged < $pages) {
    //Next：総ページ数より現在のページ値が小さい場合は表示
    //echo '		<a aria-label="Next" href="'.get_pagenum_link($paged + 1).'">'."\n";
    $link = $url.'&paged='.$paged;
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
function custom_nav_menus() {
    register_nav_menus( array('left-nav' => 'Header Nav (Left)' ) );
    register_nav_menus( array('right-nav' => 'Header Nav (Right)' ) );
}
custom_nav_menus();

function get_menu_array($menu_name) {

  $menu_name = $menu_name;
  if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
    $menus = array();
    $children = array();
    $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    $index = 0;
    foreach ( (array) $menu_items as $key => $menu_item ) {
      $parent = $menu_item->menu_item_parent;
      if(!empty($parent)) {
        $children[$parent][$index]['title'] = $menu_item->title;
        $children[$parent][$index]['url'] = $menu_item->url;
      } else {
        $item = array();
        $item['title'] = $menu_item->title;
        $item['url'] = $menu_item->url;
        $menus[$menu_item->ID] = $item;
      }
      $index += 1;
    }
    foreach((array)$menus as $key => $menu) {
      if(!empty($children[$key])) {
        $menus[$key]['children'] = $children[$key];
      }
    }
    return $menus;
  }

}

function get_menu($menu_name) {
  $menus = get_menu_array($menu_name);
  $result = '';
  $result .= "<ul>\n";
  foreach((array)$menus as $item) {
    if(empty($item['children'])) {
      $result .= "<li><a href=\"{$item['url']}\">{$item['title']}</a></li>\n";
    } else {
      $result .= "<li class=\"dropdown\"> <a href=\"{$item['url']}\">{$item['title']}</a>\n";
      $result .= "  <ul class=\"dropdown-menu\">\n";
      foreach($item['children'] as $child_menu) {
        $result .= "    <li class=\"dropdown\">\n";
        $result .= "      <li><a href=\"{$child_menu['url']}\"><i class=\"fa fa-calendar\"></i>{$child_menu['title']}</a></li>\n";
        $result .= "    </li>\n";
      }
      $result .= "  </ul>\n";
      $result .= "</li>\n";
    }
  }
  $result .= "</ul>\n";
  return $result;
}

function get_cat($id = null) {
    // 記事のカテゴリー情報を取得する
    if(!empty($id)) {
        $cats = get_the_category($id);
    } else {
        $cats = get_the_category();
    }
    $html = '';
    $sep = '';
    foreach($cats as $cat) {
        $cat_name = $cat->cat_name; // カテゴリー名
        $cat_slug  = $cat->category_nicename; // カテゴリースラッグ
        $html .= $sep."<a href=\"/{$cat_slug}\">{$cat_name}</a>";
        $sep = ', ';
    }
    return $html;
}

/*
<a href=""><i class="fa fa-tag"></i> Lifestyle</a>,
<a href=""><i class="fa fa-tag"></i>Magazine</a>
 */
function get_cat_detail($id = null) {
    // 記事のカテゴリー情報を取得する
    if(!empty($id)) {
        $cats = get_the_category($id);
    } else {
        $cats = get_the_category();
    }
    $html = '';
    $sep = '';
    foreach($cats as $cat) {
        $cat_name = $cat->cat_name; // カテゴリー名
        $cat_slug  = $cat->category_nicename; // カテゴリースラッグ
        $html .= $sep."<a href=\"/{$cat_slug}\"><i class=\"fa fa-tag\"></i>{$cat_name}</a>";
        $sep = ', ';
    }
    return $html;
}