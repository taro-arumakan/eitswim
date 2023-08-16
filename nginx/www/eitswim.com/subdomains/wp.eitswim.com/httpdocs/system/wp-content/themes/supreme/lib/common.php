<?php
/**
 * 共通関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

function elc_is_home() {
  $is_home = false;
  if ( is_front_page() && is_home() ) {
    // デフォルトホームページ
    $is_home = true;
  } elseif ( is_front_page() ) {
    // 固定ペーシを使ったホームページ
    $is_home = true;
  } elseif ( is_home() ) {
    // ブログページ
  } else {
    // それ以外
  }
  return $is_home;
}

/**
 * 抜粋文字数
 * @return int
 */
function get_excerpt_size() {
  return 150;
}

//余分なタグを削除
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * 大文字強制変換禁止
 */
add_filter( 'aioseop_capitalize_categories' , '__return_false');


/** SEO PACKのコメントを制御する START **/

// wp_head 出力フィルタリング・ハンドラ追加
add_action( 'wp_head', 'wp_head_buffer_start', 0 );
add_action( 'wp_head', 'wp_head_buffer_end', 100 );

/*
 * wp_head 出力フィルタリング
 */
function wp_head_filter($buffer) {
  // All in One Seo Pack のHTMLコメントを削除
  $buffer = preg_replace( '/^.*<!--.*all in one seo pack.*-->.*\r?\n/im', '', $buffer );
  return $buffer;
}

/*
 * wp_head バッファリング開始
 */
function wp_head_buffer_start() {
  // 出力バッファリング開始
  ob_start( 'wp_head_filter' );
}

/*
 * wp_head バッファリング終了
 */
function wp_head_buffer_end() {
  // 出力バッファリング終了
  ob_end_flush();
}

/** SEO PACKのコメントを制御する END **/

/*---------------------------------------------------------------------------
 * プロトコル消去
 *---------------------------------------------------------------------------*/
if( function_exists( 'pdel' ) === false ):
  function pdel( $url ) {
    return str_replace( array( 'http:', 'https:' ), '', esc_url( $url ) );
  }
endif;

/*---------------------------------------------------------------------------
 * スクリプト類に勝手に入ってくるバージョン番号消す
 *---------------------------------------------------------------------------*/
if( function_exists( 'remove_url_version' ) === false ):
  function remove_url_version( $arg ) {
    if( strpos( $arg, 'ver=' ) !== false ) {
      $arg = esc_url( remove_query_arg( 'ver', $arg ) );
    }
    return $arg;
  }
  add_filter( 'style_loader_src', 'remove_url_version', 99 );
  add_filter( 'script_loader_src', 'remove_url_version', 99 );
endif;

/*---------------------------------------------------------------------------
 * ヘッダーに canonical 追加
 *---------------------------------------------------------------------------*/
if( function_exists( 'elc_rel_canonical' ) === false && is_admin() === false ):
  function elc_rel_canonical() {
    global $paged, $page, $wp_query;

    $canonical_url = null;

    switch( true ) {
      case is_home():
        if( get_option('page_for_posts') ){
          $canonical_url = canonical_paged_uri( get_page_link( get_option('page_for_posts') ) );
        }
        else {
          $canonical_url = canonical_paged_uri( ELC_HOME_URL );
        }
        break;
      case is_front_page():
        $canonical_url = canonical_paged_uri( ELC_HOME_URL );
        break;
      case is_category():
        $canonical_url = canonical_paged_uri( get_category_link( get_query_var('cat') ) );
        break;
      case is_tag():
        $canonical_url = canonical_paged_uri( get_tag_link( get_query_var('tag_id') ) );
        break;
      case is_author():
        $canonical_url = canonical_paged_uri( get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) ) );
        break;
      case is_year():
        $canonical_url = canonical_paged_uri( get_year_link( get_the_time('Y') ) );
        break;
      case is_month():
        $canonical_url = canonical_paged_uri( get_month_link( get_the_time('Y'), get_the_time('m') ) );
        break;
      case is_day():
        $canonical_url = canonical_paged_uri( get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d') ) );
        break;
      case is_post_type_archive() :
        $post_type = get_query_var( 'post_type' );
        if( is_array( $post_type ) === true ) { $post_type = reset( $post_type ); }
        $canonical_url = canonical_paged_uri( get_post_type_archive_link( $post_type ) );
        break;
      default:
        break;
    }

    if( $canonical_url !== null ):
      ?>
      <link rel="canonical" href="<?php echo esc_url( $canonical_url ); ?>" />
      <?php
    endif;
  }
endif;

if( function_exists( 'canonical_paged_uri' ) === false ):
  function canonical_paged_uri( $canonical_url ) {
    global $paged, $page, $wp_rewrite;

    if( $paged >= 2 || $page >= 2 ) {
      // パーマリンクが設定されてる場合
      if( is_object( $wp_rewrite ) === true && $wp_rewrite->using_permalinks() ) {
        if( substr( $canonical_url, -1 ) === '/' ) {
          $canonical_url .= 'page/' . max( $paged, $page ) . '/';
        }
        else {
          $canonical_url .= '/page/' . max( $paged, $page );
        }
      }
      // パーマリンクがデフォルト設定(動的URL)の場合
      else {
        if( is_front_page() === true ) {
          $canonical_url .= '?paged=' . max( $paged, $page );
        }
        else {
          $canonical_url .= '&amp;paged=' . max( $paged, $page );
        }
      }
    }
    return $canonical_url;
  }
endif;

/*---------------------------------------------------------------------------
 * ヘッダーに next / prev 追加
 *---------------------------------------------------------------------------*/
if( function_exists( 'elc_rel_next_prev' ) === false && is_admin() === false ):
  function elc_rel_next_prev() {
    global $paged, $post, $wp_query;

    if( is_singular() === false ) {
      $max_page = (int)$wp_query->max_num_pages;

      if( empty( $paged ) ) {
        $paged = 1;
      }
      $nextpage = (int)$paged + 1;
      if( $nextpage <= $max_page ) {
        ?>
        <link rel="next" href="<?php echo next_posts( $max_page, false ); ?>" />
        <?php
      }
      if( $paged > 1 ) {
        ?>
        <link rel="prev" href="<?php echo previous_posts( false ); ?>" />
        <?php
      }
    }
    else {
      $pages = count( explode('<!--nextpage-->', $post->post_content) );

      if( $pages > 1 ) {
        $prev = singular_nextpage_link( 'prev', $pages );
        $next = singular_nextpage_link( 'next', $pages );

        if( !empty( $prev ) ) {
          ?>
          <link rel="prev" href="<?php echo $prev; ?>" />
          <?php
        }
        if( !empty( $next ) ) {
          ?>
          <link rel="next" href="<?php echo $next; ?>" />
          <?php
        }
      }
    }
  }
endif;

/*---------------------------------------------------------------------------
 * 投稿・固定ページを <!--nextpage--> で分割した場合の next / prev 追加関数
 *---------------------------------------------------------------------------*/
if( function_exists( 'singular_nextpage_link' ) === false && is_admin() === false ):
  function singular_nextpage_link( $rel = 'prev', $pages ) {
    global $post, $page;
    $url = '';

    if( $pages > 1 ) {
      $i = $rel === 'prev' ? $page - 1 : $page + 1;
      if( $i >= 0 && $i <= $pages ) {
        if( 1 === $i ) {
          if( $rel === 'prev' ) {
            $url = get_permalink();
          }
          else {
            $url = trailingslashit( get_permalink() ) . user_trailingslashit( $i + 1, 'single_paged' );
          }
        }
        else {
          $opt = get_option('permalink_structure');
          if( empty( $opt ) || in_array( $post->post_status, array('draft', 'pending') ) ) {
            $url = add_query_arg( 'page', $i, get_permalink() );
          }
          else {
            $url = trailingslashit( get_permalink() ) . user_trailingslashit( $i, 'single_paged' );
          }
        }
      }
    }
    return $url;
  }
endif;

/*---------------------------------------------------------------------------
 * ヘッダー、サイドバー、その他の書き換え
 *---------------------------------------------------------------------------*/
// common
if( function_exists( 'elc_html_format' ) === false && is_admin() === false ):
  function elc_html_format( $contents ) {
    global $luxe;

    // 連続改行削除
    $contents = preg_replace( '/(\n|\r|\r\n)+/us',"\n", $contents );
    // 行頭の余計な空白削除
    $contents = preg_replace( '/\n+\s*</', "\n".'<', $contents );

    // タグ間の余計な空白や改行の削除
    if( $luxe['html_compress'] === 'low' ) {
      $contents = preg_replace( '/>[\t| ]+?</', '><', $contents );
      $contents = preg_replace( '/\n+<\/([^b|^h])/', '</$1', $contents );
    }
    elseif( $luxe['html_compress'] === 'high' ) {
      $contents = preg_replace( '/>\s*?</', '><', $contents );
    }

    return $contents;
  }
  add_filter( 'wp_nav_menu', 'elc_html_format', 10, 2 );
endif;

/*---------------------------------------------------------------------------
 * remove URL
 *---------------------------------------------------------------------------*/
if( function_exists( 'elc_remove_url' ) === false ):
  function elc_remove_url( $value ){
    $url_reg = '/(https?|ftp|HTTPS?|FTP)(:\/\/[-_\.!~*\'()a-zA-Z0-9;\/?:\@&;=+\$,%#]+)/';
    return  preg_replace( $url_reg, '', $value );
  }
endif;

/*---------------------------------------------------------------------------
 * URL Encode と Convert
 *---------------------------------------------------------------------------*/
if( function_exists( 'elc_encode' ) === false ):
  function elc_encode( $value ){
    return rawurlencode( elc_convert( $value ) );
  }
endif;

if( function_exists( 'elc_convert' ) === false ):
  function elc_convert( $value ){
    if( empty( $value ) ) return;
    if( stripos( $value, null ) !== false ) return;
    mb_language( 'Japanese' );
    $charcode = check_charcode( $value );
    if( $charcode !== null && $charcode !== 'UTF-8' ) {
      $value = mb_convert_encoding( $value, 'UTF-8', $charcode );
    }
    $detect = mb_detect_encoding( $value, 'ASCII,JIS,UTF-8,CP51932,SJIS-win', true );
    if( $detect !== false ) {
      return mb_convert_encoding( $value, 'UTF-8', $detect );
    }
    return $value;
  }
endif;

// mb_detect_encoding でうまくいかない場合用
if( function_exists( 'check_charcode' ) === false ):
  function check_charcode( $value ) {
    if( empty( $value ) ) return;
    $codes = array( 'UTF-8','SJIS-win','eucJP-win','ASCII','JIS','ISO-2022-JP-MS' );
    foreach( $codes as $charcode ){
      if( mb_convert_encoding( $value, $charcode, $charcode ) === $value ){
        return $charcode;
      }
    }
    return null;
  }
endif;

/*---------------------------------------------------------------------------
 * URL Decode
 *---------------------------------------------------------------------------*/
if( function_exists( 'elc_decode' ) === false ):
  function elc_decode( $value ){
    while( $value !== rawurldecode( $value ) ) {
      $value = rawurldecode( $value );
    }
    return $value;
  }
endif;

/*---------------------------------------------------------------------------
 * Punycode Encode
 *---------------------------------------------------------------------------*/
if( function_exists( 'puny_encode' ) === false ):
  function puny_encode( $value ) {
    if( version_compare( PHP_VERSION, '5.3', '<' ) === true ) {
      return $value;
    }

    if( class_exists('Punycode') === true ) {
      $Punycode = new Punycode();

      if( method_exists( $Punycode, 'encode' ) === true ) {
        $parse = parse_url( $value );

        if( isset( $parse['host'] ) ) {
          $parse['host'] = $Punycode->encode( $parse['host'] );
          $value = http_build_url( $value, $parse );
        }
        else {
          $value = $Punycode->encode( $value );
        }
      }
    }

    return $value;
  }
endif;

/*---------------------------------------------------------------------------
 * Punycode Decode
 *---------------------------------------------------------------------------*/
if( function_exists( 'puny_decode' ) === false ):
  function puny_decode( $value ) {
    if( version_compare( PHP_VERSION, '5.3', '<' ) === true ) {
      return $value;
    }

    if( class_exists('Punycode') === true ) {
      $Punycode = new Punycode();

      if( method_exists( $Punycode, 'decode' ) === true ) {
        $parse = parse_url( $value );

        if( isset( $parse['host'] ) ) {
          $parse['host'] = $Punycode->decode( $parse['host'] );
          $value = http_build_url( $value, $parse );
        }
        else {
          $value = $Punycode->decode( $value );
        }
      }
    }

    return $value;
  }
endif;

/*---------------------------------------------------------------------------
 * Remove home from url
 * http://domain.com/wp-content... => /wp-content...
 *---------------------------------------------------------------------------*/
if( function_exists( 'remove_home_url' ) === false ):
  function remove_home_url($url) {
    $home = get_option( 'home' );
    $url = str_replace($home, '', $url);
    return $url;
  }
endif;

//コメントが許可されているか
if( function_exists( 'is_comment_open' ) === false ):
  function is_comment_open($id = null){
    global $post;
    if(empty($post->ID) && !empty($id)) {
      $new_post = get_post($id);
    } else {
      $new_post = $post;
    }
    if(isset($new_post->comment_status) ) {
      return $post->comment_status == 'open';
    }
    return false;
  }
endif;

//構造化 SITE NAME TYPE
if( function_exists( 'site_name_type' ) === false ):
    function site_name_type(){
        global $luxe;
        // Structured data of site information.
        $site_name_type = '';
        if( isset( $luxe['site_name_type'] ) ) {
            if( $luxe['site_name_type'] === 'Organization' ) {
                if( isset( $luxe['organization_type'] ) ) {
                    $site_name_type = ' itemscope itemtype="https://schema.org/' . $luxe['organization_type'] . '"';
                }
            }
            else {
                $site_name_type = ' itemscope itemtype="https://schema.org/' . $luxe['site_name_type'] . '"';
            }
        }
        return $site_name_type;
    }
endif;

if( function_exists( 'compress_start' ) === false ):
    /*
    *  圧縮開始 single.phpなどの先頭に入れる
    */
    function compress_start() {
        ob_start();
    }
endif;

if( function_exists( 'compress_end' ) === false ):
    /*
    *  圧縮終了 single.phpなどの最後に入れる
    */
    function compress_end() {
        global $luxe;
        $compress = ob_get_clean();

        // 連続改行削除
        $compress = preg_replace( '/(\n|\r|\r\n)+/us',"\n", $compress );
        // 行頭の余計な空白削除
        $compress = preg_replace( '/\n+\s*</', "\n".'<', $compress );
        // コメントを削除
        $compress = preg_replace('/<!--[\s\S]*?-->/', '', $compress);

        // />を置換
        $compress = str_replace('/>', '>', $compress);

        // タグ間の余計な空白や改行の削除
        if( $luxe['html_compress'] === 'low' ) {
            $compress = preg_replace( '/>[\t| ]+?</', '><', $compress );
            $compress = preg_replace( '/\n+<\/([^b|^h])/', '</$1', $compress );
        }
        elseif( $luxe['html_compress'] === 'high' ) {
            $compress = preg_replace( '/>\s*?</', '><', $compress );
        }

        echo $compress;
    }
endif;
