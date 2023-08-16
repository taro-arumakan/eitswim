<?php
echo "<script>console.log('supreme functions.php');</script>";
/**
 * studioelc functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package studioelc
 */

global $maintenance;

locate_template('lib/common.php', true);      // 共通関数
locate_template('lib/display.php', true);     // タイトル出力,パンくずリスト,ページング等の関数
locate_template('lib/widgets.php', true);     // サイドバー、ウィジェットの関数
locate_template('lib/media.php', true);       // メディア関連の関数
locate_template('lib/contact.php', true);     // コンタクトフォーム関連の関数
locate_template('lib/post_field.php', true);  // カスタム投稿、カスタムフィールドの関数
locate_template('lib/custom_fields.php', true); // カスタムフィールド登録
locate_template('lib/custom_search.php', true); // カスタム検索登録
locate_template('lib/custom.php', true);      // その他カスタマイズの関数

// WordPress の例の定数を使うとチェックで怒られるので再定義
define( 'TPATH', get_template_directory() );
define( 'SPATH', get_stylesheet_directory() );
define( 'THEME', get_option( 'stylesheet' ) );
define( 'DSEP' , DIRECTORY_SEPARATOR );
define( 'INC'  , TPATH . DSEP . 'inc' . DSEP );
define( 'LIB'  , TPATH . DSEP . 'lib' . DSEP );
define( 'AGNI'  , TPATH . DSEP . 'agni' . DSEP );
define( 'TURI', get_template_directory_uri() );
define( 'ELC_IMG'  , TURI . DSEP . 'images' . DSEP );

define( 'NEWS_ROOT_URI_EKY'  , 'news_root_uri');

/*---------------------------------------------------------------------------
 * luxe Theme only works in PHP 5.3 or later.
 * luxe Theme only works in WordPress 4.4 or later.
 *---------------------------------------------------------------------------*/
if(
  version_compare( PHP_VERSION, '5.3', '<' ) === true ||
  version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) === true
) {
  require( INC . 'back-compat.php' );
  switch_theme( 'default' );
}

/*---------------------------------------------------------------------------
 * global
 *---------------------------------------------------------------------------*/
$luxe = get_option( 'theme_mods_' . THEME );
$fchk = false;

$html_lang = get_theme_mod( 'html_lang', 'ja' );
$html_hreflang = get_theme_mod( 'html_hreflang', '' );
$html_gtag = get_theme_mod( 'html_gtag', '' );

$dns_prefetch_enable = get_theme_mod( 'dns_prefetch_enable', true );

if(!$dns_prefetch_enable) {
    //WordPressのdns-prefetchを削除
    add_filter('wp_resource_hints', function ($hints, $relation_type) {
        if ('dns-prefetch' === $relation_type) {
            return array_diff(wp_dependencies_unique_hosts(), $hints);
        }
        return $hints;
    }, 10, 2);
} else {
    //追加のdns-prefetchを設定
    global $dns_prefetch_text;
    $dns_prefetch_text = get_theme_mod( 'dns_prefetch_text', '' );
    if(!empty($dns_prefetch_text)) {
        add_filter( 'wp_resource_hints', function($hints, $relation_type){
            global $dns_prefetch_text;
            if(!is_admin()){
                if ( 'dns-prefetch' === $relation_type ) {
                    $dns = explode("\r\n", $dns_prefetch_text);
                    if(!empty($dns)) {
                        $hints = array_merge($hints, $dns);
                    }
                }
            }
            return $hints;
        }, 10, 2 );
    }
}

// textdomain
if( is_admin() === true && current_user_can( 'edit_posts' ) === true ) {
  load_theme_textdomain( 'studioelc', TPATH . DSEP . 'languages' . DSEP . 'admin' );
  load_theme_textdomain( 'luxeritas', TPATH . DSEP . 'languages' . DSEP . 'admin' );
}
else {
  load_theme_textdomain( 'studioelc', TPATH . DSEP . 'languages' . DSEP . 'site' );
  load_theme_textdomain( 'luxeritas', TPATH . DSEP . 'languages' . DSEP . 'site' );
}

require( INC . 'wpfunc.php' );
require( INC . 'widget.php' );
require( INC . 'stinger.php' );
require( INC . 'sns-cache.php' );

if( is_customize_preview() === true ) {
  $fchk = true;
  require( INC . 'custom.php' );
  require( INC . 'custom-css.php' );
  require( INC . 'compress.php' );
}
elseif( is_admin() === true ) {
  if( current_user_can( 'edit_theme_options' ) === true ) {
    $fchk = true;
    require( INC . 'admin-func.php' );
  }
  if( current_user_can( 'edit_posts' ) === true ) {
    require( INC . 'og-img-admin.php' );
    require( INC . 'post-update-level.php');
    require( INC . 'post-amp.php' );
    add_editor_style( array( 'css/bootstrap.min.css', 'editor-style.css' ) );
  }
}

if( is_admin() === false && isset( $luxe['amp_enable'] ) ) {
  $rules = get_option( 'rewrite_rules' );
  if( !isset( $rules['^amp/?'] ) ) {
    require( INC . 'rewrite-rules.php' );
    add_action( 'init', 'thk_add_endpoint', 11 );
  }
  unset( $rules );
}
if( isset( $luxe['amp_enable'] ) ) {
  thk_amp_mu_plugin_copy();
}

/*---------------------------------------------------------------------------
 * initialization
 *---------------------------------------------------------------------------*/
add_action( 'init', function() use( $luxe ) {
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'editor-style' );
  add_theme_support( 'html5', array( 'caption', 'gallery', 'search-form' ) );

  /** custom_nav_menusが無い場合のデフォルト設定 **/
  if( function_exists( 'custom_nav_menus' ) === false ):
    register_nav_menus( array('global-nav' => __( 'Header Nav (Global Nav)', 'luxeritas' ) ) );
    register_nav_menus( array('footer-link' => __( 'Footer Link', 'luxeritas' ) ) );
  endif;

  // get sns count
  if( stripos( $_SERVER['REQUEST_URI'], 'wp-admin/admin-ajax.php' ) !== false ) {
    if( is_preview() === false && is_customize_preview() === false ) {
      if( isset( $luxe['sns_tops_count'] ) || isset( $luxe['sns_bottoms_count'] ) ) {
        add_action( 'wp_ajax_thk_sns_real', 'thk_sns_real' );
        add_action( 'wp_ajax_nopriv_thk_sns_real', 'thk_sns_real' );
      }
    }
  }

  // set amp endpoint
  if( is_admin() === false && isset( $luxe['amp_enable'] ) ) {
    $q_amp = stripos( $_SERVER['QUERY_STRING'], 'amp=1' );
    if( $q_amp !== false ) {
      if( $q_amp > 0 ) {
        add_rewrite_endpoint( 'amp', EP_PERMALINK | EP_PAGES );
      }
    }
    else {
      add_rewrite_endpoint( 'amp', EP_PERMALINK | EP_PAGES );
    }

    add_filter( 'request', function( $vars ) {
      if( isset( $vars['amp'] ) && ( $vars['amp'] === '' ) ) {
        $vars['amp'] = 1;
      }
      return $vars;
    });
  }
}, 10 );

/*---------------------------------------------------------------------------
 * pre get posts
 *---------------------------------------------------------------------------*/
add_action( 'pre_get_posts', function( $q ) {
  if( is_search() === true ) {
    get_template_part( 'inc/search-func' );
    thk_search_extend();
  }

  // グリッドの通常表示部分は１ページに表示する件数に含めないようにする
  $mods = get_theme_mods();

  if(
    ( isset( $mods['grid_home'] ) && $mods['grid_home'] === 'none' ) ||
    ( isset( $mods['grid_archive'] ) && $mods['grid_archive'] === 'none' ) ||
    ( isset( $mods['grid_category'] ) && $mods['grid_category'] === 'none' )
  ) {
    return;
  }

  $query = $q->query;
  if( empty( $query['posts_per_page'] ) && empty( $query['offset'] ) ) {
    $grid_first = 0;

    if( $q->is_home === true && isset( $mods['grid_home_first'] ) ) {
      $grid_first = $mods['grid_home_first'];
    }
    elseif( $q->is_category === true && isset( $mods['grid_category_first'] ) ) {
      $grid_first = $mods['grid_category_first'];
    }
    elseif( $q->is_archive === true && isset( $mods['grid_archive_first'] ) ) {
      $grid_first = $mods['grid_archive_first'];
    }
    unset( $mods );

    if( $grid_first <= 0 ) return;

    $per2 = get_option( 'posts_per_page' );
    $per1 = $per2 + $grid_first;

    $paged = get_query_var( 'paged' ) ? (int)get_query_var( 'paged' ) : 1;
    if( $paged >= 2 ){
      $q->set( 'offset', $per1 + ( $paged - 2 ) * $per2 );
      $q->set( 'posts_per_page', $per2 );
    }
    else {
      $q->set( 'posts_per_page', $per1 );
    }
  }
});

/*---------------------------------------------------------------------------
 * pre comment on post
 *---------------------------------------------------------------------------*/
add_action( 'pre_comment_on_post', function() use( $luxe ) {
  if( isset( $luxe['captcha_enable'] ) ) {
    if( $luxe['captcha_enable'] === 'recaptcha' ) {
      if( isset( $_POST['g-recaptcha-response'] ) ) {
        $verify = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $luxe['recaptcha_secret_key'] . '&response=' . $_POST['g-recaptcha-response'];
        $json = (object)array( 'success' => false );

        $ret = thk_remote_request( $verify );

        if( $ret !== false && is_array( $ret ) === false ) {
          $json = @json_decode( $ret );
        }

        if( $json->success !== true ) {
          wp_die( __( 'Authentication is not valid.', 'luxeritas' ), '', array( 'response' => 418, 'back_link' => true ) );
        }
      }
    }
    elseif( $luxe['captcha_enable'] === 'securimage' ) {
      if( !isset( $_SESSION ) ) session_start();

      if( isset( $_POST['captcha_code'] ) && empty( $_POST['captcha_code'] ) ) {
        wp_die( __( 'Please enter image authentication.', 'luxeritas' ), '', array( 'response' => 418, 'back_link' => true ) );
      }
      elseif( isset( $_POST['captcha_code'] ) &&
              isset( $_SESSION['securimage_code_disp']['default'] ) &&
              $_POST['captcha_code'] !== $_SESSION['securimage_code_disp']['default']
      ) {
        wp_die( __( 'Image authentication is incorrect.', 'luxeritas' ), '', array( 'response' => 418, 'back_link' => true ) );
      }
    }
  }
  return;
});

/*---------------------------------------------------------------------------
 * wp
 *---------------------------------------------------------------------------*/
add_action( 'wp', function() {
  global $luxe;

  if( is_admin() === false ) require_once( INC . 'const.php' );
  if( is_singular() === true ) wp_enqueue_script( 'comment-reply' );

  if( isset( $luxe['sns_count_cache_enable'] ) && is_preview() === false && is_customize_preview() === false ) {
    if(
      ( is_singular() === true &&
        ( isset( $luxe['sns_count_cache_force'] ) || (
            ( isset( $luxe['sns_tops_enable'] ) && isset( $luxe['sns_tops_count'] ) ) ||
            ( isset( $luxe['sns_bottoms_enable'] ) && isset( $luxe['sns_bottoms_count'] ) ) ) ) ) ||
      ( is_home() === true &&
        ( isset( $luxe['sns_count_cache_force'] ) || (
          ( isset( $luxe['sns_toppage_view'] ) && isset( $luxe['sns_bottoms_count'] ) ) ) ) )
    ) {
      add_filter( 'template_redirect', 'touch_sns_count_cache', 10 );
      add_filter( 'shutdown', 'set_transient_sns_count_cache', 90 );
      add_filter( 'shutdown', 'set_transient_sns_count_cache_weekly_cleanup', 95 );
      if(
        isset( $luxe['sns_count_cache_force'] ) ||
        isset( $luxe['feedly_share_tops_button'] ) || isset( $luxe['feedly_share_bottoms_button'] )
      ) {
        add_filter( 'template_redirect', 'touch_feedly_cache', 10 );
        add_filter( 'shutdown', 'transient_register_feedly_cache', 90 );
      }
    }
  }

  if( isset( $luxe['amp'] ) ) {
    // AMP for front_page
    $url = '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $uri = trim( str_replace( pdel( THK_HOME_URL ), '',  $url ), '/' );
    if( $uri === 'amp' ) {
      set_fake_root_endpoint_for_amp();
    }
  }
});

/*---------------------------------------------------------------------------
 * template redirect
 *---------------------------------------------------------------------------*/
add_action( 'template_redirect', function() {
  if( is_feed() === true ) return;

  global $luxe;
  if( isset( $luxe['amp'] ) ) {
    $page_on_front = wp_cache_get( 'page_on_front', 'luxe' );
    if( $page_on_front !== false ) {
      remove_fake_root_endpoint_for_amp( $page_on_front );
    }
  }

  if(
    is_home()	=== true ||
    is_singular()	=== true ||
    is_archive()	=== true ||
    is_search()	=== true ||
    is_404()	=== true
  ) {
    require( INC . 'filters.php' );
    require( INC . 'load-styles.php' );
    require( INC . 'description.php' );
    require( INC . 'load-header.php' );
    if( isset( $luxe['blogcard_enable'] ) && is_singular() === true ) {
      require( INC . 'blogcard-func.php' );
    }
    if( isset( $luxe['amp'] ) ) {
      require( INC . 'amp-extensions.php' );
    }
  }
}, 99 );

/**
 * maintenance.php が存在すれば、メンテナンス表示とする
 * （管理画面は除く）
 */
if(file_exists(stream_resolve_include_path('maintenance.php'))) {
	$maintenance = true;
	if ( !is_admin() && empty($_GET['preview_id']) && !is_login_page()) {
		add_action( 'init', 'quick_maintenance_mode' );
	}
} else {
	$maintenance = false;
}

if ( ! function_exists( 'is_login_page' ) ) :
    /**
     * wp-login.phpなら true
     * @return bool
     */
    function is_login_page() {
        if(!empty($_SERVER['SCRIPT_NAME'])) {
            $script_name = $_SERVER['SCRIPT_NAME'];
            if(strpos($script_name, 'wp-login.php' ) !== false) {
                //
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
endif;

if ( ! function_exists( 'is_maintenance' ) ) :
    function is_maintenance() {
        if(!empty($_GET['preview_id']) || is_login_page()) {
            return false;
        }
        global $maintenance;
        return $maintenance;
    }
endif;

if ( ! function_exists( 'quick_maintenance_mode' ) ) :
    function quick_maintenance_mode() {
        include_once 'maintenance.php';
        die;
    }
endif;



if ( ! function_exists( 'studioelc_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function studioelc_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on studioelc, use a find and replace
	 * to change 'studioelc' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'studioelc', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

  add_theme_support( 'editor-style' );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'studioelc_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
	
}
endif;
add_action( 'after_setup_theme', 'studioelc_setup' );


/**
 * 投稿日からX日以内かのチェック For NEWマーク
 */
if( function_exists('is_new_post') === false ):
endif;
function is_new_post($post, $range = 14) {
    $datetime1 = new DateTime($post->post_date);
    $datetime2 = new DateTime();
    $interval = $datetime1->diff($datetime2);
    $is_new = false;
    if($interval->days < $range) {
        $is_new = true;
    }
    return $is_new;
}

//$file = TPATH . DSEP . 'js' . DSEP . 'luxe.min.js';
//file_put_contents($file,'');

/**
 * FIle の更新時刻を返す
 * @param $url  SDEL
 * @param $path = dirname( __FILE__ )
 * @return bool|int
 */
function file_time($url, $path) {
    $host = '//'.$_SERVER['HTTP_HOST'];
    $url = str_replace($host, '', $url);
    $path = $path . '/../../../../' . $url;
    $path = realpath($path);
    $filetime = filemtime($path);
    return $filetime;
}

function get_primary_term($id = null) {
    $category = get_the_category();
    if(empty($id)) {
        $id = get_the_ID();
    }
    $cats = get_post_meta($id, 'primary_category');
    if(!empty($cats) && is_array($cats)) {
        $term_id = $cats[0];
    }
    $term = get_term( $term_id );
    if (is_wp_error($term)) {
        // Default to first category (not Yoast) if an error is returned
        $term = $category[0];
    }
    return $term;
}

$_GET = array_map('stripslashes_deep', $_GET);
$_POST = array_map('stripslashes_deep', $_POST);
$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
$_SERVER = array_map('stripslashes_deep', $_SERVER);
$_REQUEST = array_map('stripslashes_deep', $_REQUEST);

function convert_nav_menu_array($menu) {
  $result = array();
  foreach($menu as $item) {
    if($item->menu_item_parent=="0") {
      $new_item = array();
      $new_item[$item->ID] = $item;
      $result[$item->ID] = $new_item;
    } else {
      if(!empty($result[$item->menu_item_parent])) {
        $old_item = $result[$item->menu_item_parent];
        $old_item[$item->ID] = $item;
        $result[$item->menu_item_parent] = $old_item;
      }
    }
  }
  return $result;
}

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

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

require_once( 'lib/pri-cat-box.php' );
$meta_box = new Pri_Cat_Box();

