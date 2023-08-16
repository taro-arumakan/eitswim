<?php
/**
 * Luxeritas WordPress Theme - free/libre wordpress platform
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @copyright Copyright (C) 2015 Thought is free.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @author LunaNuko
 * @link https://thk.kanzae.net/
 * @translators rakeem( http://rakeem.jp/ )
 */

/*---------------------------------------------------------------------------
 * CSS / Javascript の圧縮・結合で必要なファイルのインクルード
 * 条件によって、load header でもインクルードするので関数化
 *---------------------------------------------------------------------------*/
if( function_exists('thk_regenerate_files') === false ):
function thk_regenerate_files( $shutdown = false, $require_only = false ) {
	require( INC . 'custom-css.php' );
	require( INC . 'compress.php' );

	$conf = new defConfig();
	$conf->set_luxe_variable();

	if( $require_only === true ) {
		return;
	}

	if( $shutdown === false ) {
		thk_compress();
		thk_parent_css_bind();
		thk_child_js_comp();
		thk_create_inline_style();
		thk_empty_remove();
	}
	else {
		add_filter( 'shutdown', 'thk_compress', 75 );
		add_filter( 'shutdown', 'thk_parent_css_bind', 80 );
		add_filter( 'shutdown', 'thk_child_js_comp', 80 );
		add_filter( 'shutdown', 'thk_create_inline_style', 85 );
		add_filter( 'shutdown', 'thk_empty_remove', 90 );
	}
}
endif;

/*---------------------------------------------------------------------------
 * タイトル修正
 *---------------------------------------------------------------------------*/
if( function_exists('thk_title_separator') === false ):
function thk_title_separator( $sep ) {
	global $luxe;
	$sep = ( $luxe['title_sep'] === 'hyphen' ) ? '-' : '|';
	return $sep;
}
add_filter( 'document_title_separator', 'thk_title_separator' );
endif;

add_filter( 'document_title_parts', function( $title ) {
	global $luxe;
	$ret = $title;

	/* Memo: https://developer.wordpress.org/reference/hooks/document_title_parts/
	 *
	 * $title (array) The document title parts.
	 *	'title'   (string) Title of the viewed page.
	 *	'page'    (string) Optional. Page number if paginated.
	 *	'tagline' (string) Optional. Site description when on home page.
	 * 	'site'    (string) Optional. Site title when not on home page.
	 */

	switch( true ) {
		case is_home():
			if( $luxe['title_top_list'] === 'site' ) {
				$ret = array( 'site' => THK_SITENAME );
				if( isset( $title['page'] ) ) $ret['page'] = $title['page'];
			}
			break;
		case is_front_page():
			if( $luxe['title_front_page'] === 'site' ) {
				$ret = array( 'site' => THK_SITENAME );
			}
			elseif( $luxe['title_front_page'] === 'site_title' ) {
				$ret = array( 'site' => THK_SITENAME, 'title' => get_the_title() );
			}
			elseif( $luxe['title_front_page'] === 'title_site' ) {
				$ret = array( 'title' => get_the_title(), 'site' => THK_SITENAME );
			}
			break;
		case is_singular():
			if( $luxe['title_other'] === 'title' ) {
				$ret = array( 'title' =>  get_the_title() );
				if( isset( $title['page'] ) ) $ret['page'] = $title['page'];
			}
			elseif( $luxe['title_other'] === 'site_title' ) {
				$ret = array( 'site' => THK_SITENAME, 'title' => get_the_title() );
				if( isset( $title['page'] ) ) $ret['page'] = $title['page'];
			}
			break;
		default:
			if( $luxe['title_other'] === 'title' ) {
				$ret = array( 'title' =>  current( $title ) );
				if( isset( $title['page'] ) ) $ret['page'] = $title['page'];
			}
			elseif( $luxe['title_other'] === 'site_title' ) {
				$ret = array( 'site' => THK_SITENAME, 'title' => current( $title ) );
				if( isset( $title['page'] ) ) $ret['page'] = $title['page'];
			}
			break;
	}
	return $ret;
} );

/*---------------------------------------------------------------------------
 * グローバルナビにホームへのリンク追加
 *---------------------------------------------------------------------------*/
add_filter( 'wp_page_menu_args', function( $args ) {
	global $luxe;

	if( isset( $luxe['home_text'] ) ) {
		$args['show_home'] = $luxe['home_text'];
	}
	elseif( !isset( $args['show_home'] ) ) {
		$args['show_home'] = true;
	}

	return $args;
} );

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
if( function_exists( 'thk_rel_canonical' ) === false && is_admin() === false ):
function thk_rel_canonical() {
	global $paged, $page, $wp_query;

	$canonical_url = null;

	switch( true ) {
		case is_home():
			if( get_option('page_for_posts') ){
				$canonical_url = canonical_paged_uri( get_page_link( get_option('page_for_posts') ) );
			}
			else {
				$canonical_url = canonical_paged_uri( THK_HOME_URL );
			}
			break;
		case is_front_page():
			$canonical_url = canonical_paged_uri( THK_HOME_URL );
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
if( function_exists( 'thk_rel_next_prev' ) === false && is_admin() === false ):
function thk_rel_next_prev() {
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
 * サイドバーのカラム数を決めて、サイドバーを呼び出す
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_call_sidebar' ) === false ):
function thk_call_sidebar() {
	global $luxe;

	if( isset( $luxe['buffering_enable'] ) ) {
		if( ob_get_level() < 1 || ob_get_length() === false ) ob_start();
		if( ob_get_length() !== false ) {
		       	ob_flush();
		       	flush();
			ob_end_flush();
		}
	}

	// 1カラムの時はサイドバー表示しない
	if( $luxe['column_style'] === '1column' ) return;

	if( $luxe['column_style'] === '3column' && !isset( $luxe['amp'] ) ) {
		if( isset( $luxe['column3_reverse'] ) ) {
			echo apply_filters( 'thk_sidebar', '' );
		}
		else {
			echo apply_filters( 'thk_sidebar', 'col3' );
		}
?>
</div><!--/#field-->
<?php
		if( !isset( $luxe['column3_reverse'] ) ) {
			echo apply_filters( 'thk_sidebar', '' );
		}
		else {
			echo apply_filters( 'thk_sidebar', 'col3' );
		}
	}
	else {
		echo apply_filters( 'thk_sidebar', '' );
	}

	if( isset( $luxe['buffering_enable'] ) ) {
		if( ob_get_level() < 1 || ob_get_length() === false ) ob_start();
		if( ob_get_length() !== false ) {
		       	ob_flush();
		       	flush();
			ob_end_flush();
		}
	}
}
endif;

/*---------------------------------------------------------------------------
 * ヘッダー、サイドバー、その他の書き換え
 *---------------------------------------------------------------------------*/
// common
if( function_exists( 'thk_html_format' ) === false && is_admin() === false ):
function thk_html_format( $contents ) {
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
add_filter( 'wp_nav_menu', 'thk_html_format', 10, 2 );
endif;

/*---------------------------------------------------------------------------
 * スクリプト書き換え
 *---------------------------------------------------------------------------*/
add_filter( 'script_loader_tag', function( $ret ) {
	global $luxe;

	if(
		is_feed() === true ||
		is_admin() === true ||
		is_customize_preview() === true
	) return $ret;

	$ret = str_replace( "'", '"', $ret );

	// dummy.js
	if( stripos( $ret, 'luxe.dummy-' ) ) {
		$ret = null;
	}

	// jquery bind
	if( isset( $luxe['jquery_load'] ) && isset( $luxe['jquery_migrate'] ) && $luxe['jquery_migrate'] !== 'not' ) {
		if( stripos( $ret, 'jquery/jquery.js' ) ) {
			if( $luxe['jquery_migrate'] === 'migrate' ) {
				$ret = '<script src="' . TDEL . '/js/jquery.bind.min.js"></script>' . "\n";
			}
			else {
				$ret = '<script src="' . TDEL . '/js/jquery.luxe.min.js?v=' . $_SERVER['REQUEST_TIME'] . '"></script>' . "\n";
        $ret = null;
			}
		}
		if( stripos( $ret, 'jquery/jquery-migrate.min.js' ) ) {
			$ret = null;
		}
	}

	// bootstrap.min.js
	if( isset( $luxe['jquery_load'] ) && $luxe['bootstrap_js_load_type'] !== 'none' ) {
		if( stripos( $ret, 'bootstrap.min.js' ) ) {
			if( $luxe['bootstrap_js_load_type'] === 'sync' ) {
				$bootstrap_js = '';
			}
			elseif( $luxe['bootstrap_js_load_type'] === 'asyncdefer' ) {
				$bootstrap_js = ' async defer';
			}
			else {
				$bootstrap_js = ' ' . $luxe['bootstrap_js_load_type'];
			}
			$ret = str_replace( '><', $bootstrap_js . '><', $ret );
		}
	}

	// luxe.async.min.js
	if( stripos( $ret, 'luxe.async.min.js' ) ) {
		$ret = str_replace( '><', ' async defer><', $ret );
	}

	// jquery defer
	if( isset( $luxe['jquery_defer'] ) && stripos( $ret, 'async' ) === false && stripos( $ret, 'defer' ) === false ) {
		if( stripos( $ret, '/js/jquery.luxe.min.js' ) ) {
			$ret = str_replace( '><', ' async defer><', $ret );
			$ret = '';
		}
		elseif( stripos( $ret, '/js/jquery.bind.min.js' ) ) {
			$ret = str_replace( '><', ' async defer><', $ret );
		}
		else {
			$ret = str_replace( '><', ' defer><', $ret );
		}
	}
	elseif( stripos( $ret, '/js/luxe.min.js' ) ) {
		$ret = str_replace( '><', ' async defer><', $ret );
		$ret = '';
	}

	$ret = str_replace( array( 'http:', 'https:' ), '', $ret );

	return str_replace( '  ', ' ', $ret );
} );

/*---------------------------------------------------------------------------
 * スタイルシート書き換え
 *---------------------------------------------------------------------------*/
add_filter( 'style_loader_tag', function( $ret ) {
	global $luxe;

	return;

	if( isset( $luxe['amp'] ) ) return;

	if(
		is_feed() === true ||
		is_admin() === true ||
		is_customize_preview() === true
	) return $ret;

	$ret = str_replace( array( 'http:', 'https:' ), '', $ret );
	$ret = str_replace( "'", '"', $ret );

	if( strpos( $ret, 'id="async-css"' ) !== false ) {
		$ret = '<noscript>' . trim( $ret ) . '</noscript>' . "\n";
	}

	if( strpos( $ret, 'id="nav-css"' ) !== false ) {
		$ret = '<noscript>' . trim( $ret ) . '</noscript>' . "\n";
	}

	if( isset( $luxe['css_to_style'] ) ) {
		if( stripos( $ret, 'id="luxe-css"' ) !== false || stripos( $ret, 'id="luxech-css"' ) !== false ) {
			$ret = null;
		}
	}
	if( isset( $luxe['css_to_plugin_style'] ) ) {
		if( stripos( $ret, 'id="plugin-styles-css"' ) !== false ) {
			$ret = null;
		}
	}

	if( strpos( $ret, 'id="luxe1-css"' ) !== false || strpos( $ret, 'id="luxe2-css"' ) !== false || strpos( $ret, 'id="luxe3-css"' ) !== false ) {
		$ret = null;
	}

	return str_replace( '  ', ' ', $ret );
} );

/*---------------------------------------------------------------------------
 * Lazy Load 用の img タグ置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'add_lazyload' ) === false ):
function add_lazyload( $content ) {
	if( is_feed() === true || wp_is_mobile() ) {
		return $content;
	}

	global $luxe;

	if( isset( $luxe['lazyload_crawler'] ) ) {
		if( is_crawler( isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null ) === true ) {
			return $content;
		}
	}

	if( stripos( $content, 'data-lazy=' ) === false && stripos( $content, 'data-original=' ) === false  ) {
		$content = preg_replace(
			'/<img ([^>]*?)src=[\'\"]([^>]+?(\.jpg|\.png|\.gif))[\'\"]([^>]+?)>/i',
			'<img src="' . TDEL . '/images/trans.png" data-lazy="1" data-original="${2}"${1}${4}><noscript><img src="${2}"${1}${4}></noscript>',
			$content
		);
	}

	return $content;
}
endif;

/*---------------------------------------------------------------------------
 * Tosrus 用の a タグ置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'add_tosrus' ) === false ):
function add_tosrus( $content ) {
	if( is_feed() === true ) return $content;

	if( stripos( $content, 'data-rel="tosrus"' ) !== false ) {
		return $content;
	}

	$content = preg_replace(
		'/(<a[^>]+?href[^>]+?(\.jpg|\.png|\.gif)[\'\"][^>]*?)>\s*(<img[^>]+?(alt=[\'\"](.*?)[\'\"]|[^>]+?)+[^>]+?>)\s*<\/a>/i',
		'${1} data-rel="tosrus" data-title="${5}">${3}</a>',
		$content
	);

	return $content;
}
endif;

/*---------------------------------------------------------------------------
 * Lightcase 用の a タグ置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'add_lightcase' ) === false ):
function add_lightcase( $content ) {
	if( is_feed() === true ) return $content;

	if( stripos( $content, 'data-rel="lightcase"' ) !== false ) {
		return $content;
	}

	$content = preg_replace(
		'/(<a[^>]+?href[^>]+?(\.jpg|\.png|\.gif)[\'\"][^>]*?)>\s*(<img[^>]+?>)\s*<\/a>/i',
		'${1} data-rel="lightcase:myCollection">${3}</a>',
		$content
	);

	return $content;
}
endif;

/*---------------------------------------------------------------------------
 * Fluidbox 用の a タグ置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'add_fluidbox' ) === false ):
function add_fluidbox( $content ) {
	if( is_feed() === true ) return $content;

	if( stripos( $content, 'data-fluidbox' ) !== false ) {
		return $content;
	}

	$content = preg_replace(
		'/(<a[^>]+?href[^>]+?(\.jpg|\.png|\.gif)[\'\"][^>]*?)>\s*(<img[^>]+?>)\s*<\/a>/i',
		'${1} data-fluidbox>${3}</a>',
		$content
	);

	return $content;
}
endif;

/*---------------------------------------------------------------------------
 * クローラー判定 ( Lazy Load 用 )
 *---------------------------------------------------------------------------*/
if( function_exists( 'is_crawler' ) === false ) :
function is_crawler( $user_agent ) {
	$crawler = array(
		'Y!J',			// Yahoo!
		'Slurp',		// Yahoo!
		'Googlebot',		// Google
		'adsence-Google',	// Google
		'Mediapartners',	// Google
		'msnbot',		// Microsoft
		'bingbot',		// Microsoft
		'Yeti/',		// Never
		'NaverBot',		// Never
		'Baidu',		// Baidu (百度)
		'ichiro',		// goo
		'Hatena',		// Hatena
		'YPBot',		// イエローページ
		'BecomeBot',		// Become.com (アメリカ)
		'YandexBot',		// ロシアの画像検索
		'heritr',		// オープンソース
		'Spider',
		'crawl'
	);

	foreach( $crawler as $val ) {
		if( stripos( $user_agent, $val ) ) {
			return true;
		}
	}
	return false;
}
endif;

/*---------------------------------------------------------------------------
 * 「記事を読む」の後ろに短いタイトル追加
 *---------------------------------------------------------------------------*/
if( function_exists( 'read_more_title_add' ) === false ):
function read_more_title_add( $word = '', $length = 16 ) {
	$more_title = the_title_attribute('echo=0');

	if( is_int( $length ) === false ) {
		$length = 16;
	}
	if( mb_strlen( $more_title ) > $length ) {
		$more_title = mb_strimwidth( $more_title, 0, $length ) . ' ...';
	}
	return $word . ' <i class="fa fa-angle-double-right"></i>&nbsp; ' . $more_title;
}
endif;

/*---------------------------------------------------------------------------
 * more タグ除去（オリジナルのものに変えるので要らない）
 *---------------------------------------------------------------------------*/
add_filter( 'the_content_more_link', function( $more ) {
	return null;
} );

/*---------------------------------------------------------------------------
 * インラインフレーム (Youtube とか Google Map 等) の responsive 対応
 * 外部リンクに external や icon 追加
 * AMP 用の置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_the_content' ) === false ):
function thk_the_content( $contents ) {
	global $luxe;

	if( !empty( $contents ) ) {
	 	/***
		 * H2 タグ上のウィジェット挿入
		 ***/
		if( function_exists('dynamic_sidebar') === true && is_active_sidebar('post-h2-upper') === true ) {
			if( stripos( $contents, '<h2>' ) !== false || stripos( $contents, '<h2 ' ) !== false ) {
				ob_start();
				if( !isset( $luxe['amp'] ) ) {
					dynamic_sidebar( 'post-h2-upper' );
				}
				else {
					dynamic_sidebar( 'post-h2-upper-amp' );
				}
				$widget = ob_get_clean();
				$widget = str_replace( "\t", '', $widget );
				$contents = preg_replace( '/(<h2.*?>)/i', $widget . "\n$1", $contents, 1 );
			}
		}

	 	/***
		 * AMP 置換 (その1)
		 ***/
		if( isset( $luxe['amp'] ) ) {
			$contents = thk_amp_not_allowed_tag_replace( $contents );
		}

	 	/***
		 * インラインフレーム
		 ***/
		// インラインフレームで、且つ embed を含むものを探す
		$i_frame = 'i' . 'frame';

		preg_match_all( "/<\s*${i_frame}[^>]+?embed[^>]+?>[^<]*?<\/${i_frame}>/i", $contents, $frame_array );

		// 置換する
		foreach( array_unique( $frame_array[0] ) as $value ) {
			$replaced = '';

			// WordPress だと、ほぼ自動で p で囲まれるため、div でなく、あえて span (display:block) を使う
			if( stripos( $value, 'youtube.com' ) !== false || stripos( $value, '.google.com/maps' ) !== false ) {
				$replaced = str_replace( "<$i_frame", "<span class=\"i-video\"><$i_frame", $value );
				$replaced = str_replace( "</$i_frame>", "</$i_frame></span>", $replaced );
			}
			else {
				$replaced = str_replace( "<$i_frame", "<span class=\"i-embed\"><$i_frame", $value );
				$replaced = str_replace( "</$i_frame>", "</$i_frame></span>", $replaced );
			}
			$contents = str_replace( $value, $replaced, $contents );
		}

	 	/***
		 * AMP 置換 (その2)
		 ***/
		if( isset( $luxe['amp'] ) ) {
			$contents = thk_amp_tag_replace( $contents );
		}

	 	/***
		 * 外部リンクに external や icon 追加
		 ***/
		if(
			isset( $luxe['add_target_blank'] ) ||
			isset( $luxe['add_rel_nofollow'] ) ||
			isset( $luxe['add_class_external'] ) ||
			isset( $luxe['add_external_icon'] )
		) {
			preg_match_all( '/<a[^>]+?href[^>]+?>.+?<\/a>/i', $contents, $link_array );
			//$my_url = preg_quote( rtrim( THK_HOME_URL, '/' ) . '/', '/' );

			foreach( array_unique( $link_array[0] ) as $link ) {
				$replaced = '';
				$last = '';

				$compare = str_replace( array( "'", '"', ' ' ), '', $link );

				if( stripos( $compare, '://' ) === false && stripos( $compare, 'href=//' ) === false ) continue;
				if( stripos( $compare, '\\' ) !== false ) continue;
				if( stripos( $compare, 'data-blogcard' ) !== false ) continue;

				//if( !preg_match( '/href=[\'|\"]?\s?' . $my_url . '[^>]+?[\'|\"]/i', $link ) ) {
				if( stripos( $compare, 'href=' . THK_HOME_URL ) === false ) {
					$atag = preg_split( '/>/i', $link );
					$atag = array_filter( $atag );

					// target="_blank"
					if( isset( $luxe['add_target_blank'] ) && stripos( $atag[0], 'target' ) === false ) {
						$atag[0] .= ' target="_blank"';
					}
					// rel="nofollow"
					if( isset( $luxe['add_rel_nofollow'] ) && stripos( $atag[0], 'nofollow' ) === false ) {
						$atag[0] .= ' rel="nofollow"';
					}
					// class="external"
					if( isset( $luxe['add_class_external'] ) ) {
						$atag[0] .= ' class="external"';
					}

					foreach( $atag as $key => $value ) $atag[$key] = $value . '>';

					// external icon
					if( isset( $luxe['add_external_icon'] ) ) {
						$last = end( $atag );
						$last .= '<span class="ext_icon"></span>';
						array_pop( $atag );

					}

					foreach( $atag as $value ) $replaced .= $value;
					$replaced .= $last;

					$contents = str_replace( $link, $replaced, $contents );

					if( isset( $luxe['add_external_icon'] ) ) {
						// img の時はアイコン消す（class="external" は残す）
						$contents = preg_replace(
							'/(<a[^>]+?href[^>]+?external[^>]+?>\s*?<\s?img[^>]+?src[^>]+?><\/a>)<span class=\"ext_icon\"><\/span>/is',
							'$1', $contents
						);
					}
				}
			}
		}
	}
	return $contents;
}
add_filter( 'the_content', 'thk_the_content', 2147483647 );
endif;

/*---------------------------------------------------------------------------
 * コピーライト生成
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_create_copyright' ) === false ):
function thk_create_copyright( $type, $auth, $since = null, $text = null ) {
	$ret = '';

	if( $type !== 'free' || ( $type === 'free' && !empty( $text ) ) ) {
		//$ret .= '<p class="copy">';
    $ret .= '';
	}

	switch( $type ) {
		case 'ccsra':
			$ret .= 'Copyright &copy; <span itemprop="copyrightYear">' . $since . '</span>-' . date('Y') . ' <span itemprop="copyrightHolder name">' . $auth . '</span> All Rights Reserved.';
			break;
		case 'ccsa':
			$ret .= 'Copyright &copy; <span itemprop="copyrightYear">' . $since . '</span>&nbsp;<span itemprop="copyrightHolder name">' . $auth . '</span> All Rights Reserved.';
			break;
		case 'cca':
			$ret .= 'Copyright &copy; <span itemprop="copyrightHolder name">' . $auth . '</span> All Rights Reserved.';
			break;
		case 'ccsr':
			$ret .= 'Copyright &copy; <span itemprop="copyrightYear">' . $since . '</span>-' . date('Y') . ' <span itemprop="copyrightHolder name">' . $auth . '</span>';
			break;
		case 'ccs':
			$ret .= 'Copyright &copy; <span itemprop="copyrightYear">' . $since . '</span>&nbsp;<span itemprop="copyrightHolder name">' . $auth . '</span>';
			break;
		case 'cc':
			$ret .= 'Copyright &copy; <span itemprop="copyrightHolder name">' . $auth . '</span>';
			break;
		case 'csr':
			$ret .= '&copy; <span itemprop="copyrightYear">' . $since . '</span>-' . date('Y') . ' <span itemprop="copyrightHolder name">' . $auth . '</span>';
			break;
		case 'cs':
			$ret .= '&copy; <span itemprop="copyrightYear">' . $since . '</span>&nbsp;<span itemprop="copyrightHolder name">' . $auth . '</span>';
			break;
		case 'c':
			$ret .= '&copy; <span itemprop="copyrightHolder name">' . $auth . '</span>';
			break;
		case 'free':
			if( !empty( $text ) ) {
				$ret .= $text;
			}
			break;
		default:
			$ret .= 'Copyright &copy; <span itemprop="copyrightYear">' . $since . '</span> <span itemprop="copyrightHolder name">' . $auth . '</span>. All Rights Reserved.';
			break;
	}

	if( $type !== 'free' || ( $type === 'free' && !empty( $text ) ) ) {
		//$ret .= '</p>';
    $ret .= '';
	}

	return $ret;
}
endif;

/*---------------------------------------------------------------------------
 * カスタマイズで hentry 削除にチェックがついてたら hentry 削除
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_remove_hentry' ) === false ):
function thk_remove_hentry( $ret ) {
	$ret = array_diff( $ret, array('hentry') );
	return $ret;
}
endif;

/*---------------------------------------------------------------------------
 * 全体イメージの CSS ファイル名取得
 *---------------------------------------------------------------------------*/
if( function_exists( 'get_overall_image' ) === false ):
function get_overall_image() {
	$overall = get_theme_mod( 'overall_image', 'white' );
	if( $overall !== null && $overall !== 'white' ) {
		//$overall = 'styles/style-' . $overall . '.css';
    $overall = 'style.css';
	}
	else {
		$overall = 'style.css';
	}
	return $overall;
}
endif;

/*---------------------------------------------------------------------------
 * CSS を HTML に直接埋め込む場合 (パス変換済みの CSS を require する)
 * または、テンプレートごとにカラム数が違う場合
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_direct_style' ) === false ):
function thk_direct_style( $require_file ) {
	if( file_exists( $require_file ) === false ) return;

	ob_start();
	require( $require_file );
	$css = ob_get_clean();

	return $css;
}
endif;

/*---------------------------------------------------------------------------
 * サイトマップ用インラインスタイル
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_sitemap_inline_style' ) === false ):
function thk_sitemap_inline_style() {
	return <<< STYLE
#sitemap .sitemap-home {
	margin: 0 0 0 20px;
}
#sitemap ul {
	margin: 0 0 30px 0x;
}
#sitemap ul ul,
#sitemap ul ul ul,
#sitemap ul ul ul ul {
	margin: 0 0 0 3px;
	padding: 0;
}
#sitemap li {
	line-height: 1.7;
	margin-left: 10px;
	padding: 0 0 0 22px;
	border-left: 1px solid #000;
	list-style-type: none;
}
#sitemap li:before {
	content: "-----";
	font-size: 14px; font-size: 1.4rem;
	margin-left: -23px;
	margin-right: 12px;
	letter-spacing: -3px;
}
#sitemap .sitemap-home a,
#sitemap li a {
	text-decoration: none;
}
STYLE;
}
endif;

/*---------------------------------------------------------------------------
 * Web フォントの preload
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_preload_web_font' ) === false ):
function thk_preload_web_font( $font_name = null ) {
	if( !empty( $font_name ) ) {
		if( file_exists( TPATH . DSEP . 'webfonts' . DSEP . $font_name ) ) {
			ob_start();
			require( TPATH . DSEP . 'webfonts' . DSEP . $font_name );
			$href = trim( ob_get_clean() );
			$type = substr( $href, strripos( $href, '.' ) + 1 );
			if( stripos( $href, '//' ) !== false && !empty( $type ) ) {
?>
<link rel="preload" as="font" type="font/<?php echo $type; ?>" href="<?php echo $href; ?>" crossorigin />
<?php
			}
		}
	}
}
endif;

/*---------------------------------------------------------------------------
 * ブログカードキャッシュ削除処理
 *---------------------------------------------------------------------------*/
/* 削除処理 */
if( function_exists( 'blogcard_cache_cleanup' ) === false ):
function blogcard_cache_cleanup( $rm_dir = false, $del_transient_only = false ) {
	global $wp_filesystem, $wpdb;

	if( $del_transient_only === false ) {
		require_once( INC . 'optimize.php' );

		$filesystem = new thk_filesystem();
		if( $filesystem->init_filesystem( site_url() ) === false ) return false;

		$wp_upload_dir = wp_upload_dir();
		$cache_dir = $wp_upload_dir['basedir'] . '/luxe-blogcard/';

		if( $wp_filesystem->is_dir( $cache_dir ) === true ) {
			if( $rm_dir === true ) {
				// ディレクトリごと消す場合
				if( $wp_filesystem->delete( $cache_dir, true ) === false ) {
					if( is_admin() === true ) {
						add_settings_error( 'luxe-custom', '', __( 'Could not delete cache directory.', 'luxeritas' ) . '<br />' . $cache_dir );
					}
					elseif( defined( 'WP_DEBUG' ) === true && WP_DEBUG == true ) {
						$result = new WP_Error( 'rmdir failed', __( 'Could not delete cache directory.', 'luxeritas' ), $cache_dir );
						thk_error_msg( $result );
					}
				}
			}
			else {
				// ファイルだけ消す場合
				$dirlist = $wp_filesystem->dirlist( $cache_dir );
				foreach( (array)$dirlist as $filename => $fileinfo ) {
					$wp_filesystem->delete( $cache_dir . $filename, true );
				}
			}
		}
	}

	// transient を消す
	$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_luxe-bc-%')" );
	$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_timeout_luxe-bc-%')" );
}
endif;

/*---------------------------------------------------------------------------
 * 1週間に1度 SNS のカウントキャッシュを全クリア ( transient に登録)
 *---------------------------------------------------------------------------*/
if( function_exists( 'set_transient_sns_count_cache_weekly_cleanup' ) === false ):
function set_transient_sns_count_cache_weekly_cleanup() {
	if( get_transient( 'sns_count_cache_weekly_cleanup' ) === false ) {
		global $wpdb;

		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_luxe-sns-%')" );
		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_timeout_luxe-sns-%')" );
		delete_transient( 'sns_count_cache_weekly_cleanup' );
		set_transient( 'sns_count_cache_weekly_cleanup', 1, WEEK_IN_SECONDS );

		sns_count_cache_cleanup( false, false, true );
	}
}
endif;

/*---------------------------------------------------------------------------
 * SNS のカウントキャッシュ削除処理
 *---------------------------------------------------------------------------*/
/* 削除処理 */
if( function_exists( 'sns_count_cache_cleanup' ) === false ):
function sns_count_cache_cleanup( $rm_dir = false, $del_transient = false, $weekly = true ) {
	require_once( INC . 'optimize.php' );
	global $wp_filesystem;

	$target = $weekly === true ? get_theme_mod( 'sns_count_weekly_cleanup', 'dust' ) : 'all';

	$filesystem = new thk_filesystem();
	if( $filesystem->init_filesystem( site_url() ) === false ) return false;

	$wp_upload_dir = wp_upload_dir();
	$cache_dir = $wp_upload_dir['basedir'] . '/luxe-sns/';

	if( $wp_filesystem->is_dir( $cache_dir ) === true ) {
		if( $rm_dir === true ) {
			// ディレクトリごと消す場合
			if( $wp_filesystem->delete( $cache_dir, true ) === false ) {
				if( defined( 'WP_DEBUG' ) === true && WP_DEBUG == true ) {
					$result = new WP_Error( 'rmdir failed', __( 'Could not delete cache directory.', 'luxeritas' ), $cache_dir );
					thk_error_msg( $result );
				}
			}
		}
		else {
			// ファイルだけ消す場合
			$dirlist = $wp_filesystem->dirlist( $cache_dir );

			if( $target === 'dust' ) {
				// 明らかなゴミだけ削除する場合
				foreach( (array)$dirlist as $filename => $fileinfo ) {
					$size = filesize( $cache_dir . $filename );
					if( ctype_xdigit( $filename ) === false || strlen( $filename ) !== 32 || $size < 14 || $size > 8200 ) {
						$wp_filesystem->delete( $cache_dir . $filename );
					}
				}
			}
			elseif( $target === 'all' ) {
				// 全ファイル削除する場合
				foreach( (array)$dirlist as $filename => $fileinfo ) {
					$wp_filesystem->delete( $cache_dir . $filename );
				}
			}
		}
	}

	// transient も全部消す場合
	if( $del_transient === true || $target === 'all' ) {
		global $wpdb;
		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_luxe-sns-%')" );
		$wpdb->query( "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE ('_transient_timeout_luxe-sns-%')" );
		delete_transient( 'sns_count_cache_weekly_cleanup' );
	}
}
add_action( 'sns_count_cache_weekly_cleanup', 'sns_count_cache_cleanup' );
endif;

/*---------------------------------------------------------------------------
 * SNS カウントキャッシュの transient 登録
 *---------------------------------------------------------------------------*/
/* 空ファイル作成 (template_redirect に add_filter) */
if( function_exists( 'touch_sns_count_cache' ) === false ):
function touch_sns_count_cache() {
	$url = is_front_page() === true ? THK_HOME_URL : get_permalink();
	$sns = new sns_cache();
	$sns->touch_sns_count_cache( esc_url( $url ) );
}
endif;

/* transient 登録 (shutdown に add_filter) */
if( function_exists( 'set_transient_sns_count_cache' ) === false ):
function set_transient_sns_count_cache() {
	$url = is_front_page() === true ? THK_HOME_URL : get_permalink();
	$sns = new sns_cache();
	$sns->set_transient_sns_count_cache( 'sns_count_cache', esc_url( $url ) );
}
endif;

/* カウント数取得 (shutdown に add_filter) */
add_action( 'sns_count_cache', function( $url = null ){
	$sns = new sns_cache();
	$sns->create_sns_cache( esc_url( $url ) );
} );

/*---------------------------------------------------------------------------
 * Feedly カウントキャッシュの transient 登録
 *---------------------------------------------------------------------------*/
/* 空ファイル作成 (template_redirect に add_filter) */
if( function_exists( 'touch_feedly_cache' ) === false ):
function touch_feedly_cache() {
	$sns = new sns_cache();
	$sns->touch_sns_count_cache( esc_url( get_bloginfo( 'rss2_url' ) ) );
}
endif;

/* transient 登録 (shutdown に add_filter) */
if( function_exists( 'transient_register_feedly_cache' ) === false ):
function transient_register_feedly_cache() {
	$sns = new sns_cache();
	$sns->set_transient_sns_count_cache( 'feedly_count_cache', esc_url( get_bloginfo( 'rss2_url' ) ) );
}
endif;

/* カウント数取得 (shutdown に add_filter) */
add_action( 'feedly_count_cache', function( $url = null ){
	$sns = new sns_cache();
	$sns->create_feedly_cache();
} );

/*---------------------------------------------------------------------------
 * SNS カウントキャッシュの中身取得 (初回と失敗時は ajax で取得)
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_get_sns_count_cache' ) === false ):
function thk_get_sns_count_cache() {
	$id_cnt = array( 'f' => '', 'g' => '', 'h' => '', 'l' => '', 'p' => '' );

	foreach( $id_cnt as $key => $val ) {
		$id_cnt[$key] = '<i class="fa fa-spinner fa-spin"></i>';
	}

	$url = is_front_page() === true ? esc_url( THK_HOME_URL ) : esc_url( get_permalink() );

	$wp_upload_dir = wp_upload_dir();
	$cache_dir = $wp_upload_dir['basedir'] . '/luxe-sns/';
	$sns_count_cache = $cache_dir . md5( $url );

	if( file_exists( $sns_count_cache ) === true ) {
		global $wp_filesystem;

		$cache = '';
		if( method_exists( $wp_filesystem, 'get_contents' ) === true ) {
			$cache = $wp_filesystem->get_contents( $sns_count_cache );
		}

		if( !empty( $cache ) && strpos( $cache, $url ) !== false ) {
			$ids = explode( "\n", $cache );
			array_shift( $ids );
			foreach( (array)$ids as $value ) {
				foreach( (array)$id_cnt as $key => $val ) {
					if( strpos( $value, $key . ':' ) !== false ) {
						$value = trim( $value, $key . ':' );
						if( ctype_digit( $value ) === true ) {
							$id_cnt[$key] = $value;
						}
					}
				}
			}
		}
	}
	return $id_cnt;
}
endif;

/*---------------------------------------------------------------------------
 * Feedly カウントキャッシュの中身取得 (初回と失敗時は ajax で取得)
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_get_feedly_count_cache' ) === false ):
function thk_get_feedly_count_cache() {
	$feedly_count = '<i class="fa fa-spinner fa-spin"></i>';

	$url = esc_url( get_bloginfo( 'rss2_url' ) );

	$wp_upload_dir = wp_upload_dir();
	$cache_dir = $wp_upload_dir['basedir'] . '/luxe-sns/';
	$feedly_count_cache = $cache_dir . md5( $url );

	if( file_exists( $feedly_count_cache ) === true ) {
		global $wp_filesystem;

		$cache = '';
		if( method_exists( $wp_filesystem, 'get_contents' ) === true ) {
			$cache = $wp_filesystem->get_contents( $feedly_count_cache );
		}

		if( !empty( $cache ) && strpos( $cache, $url ) !== false ) {
			$cnt = explode( "\nr:", $cache );
			if( ctype_digit( trim( $cnt[1] ) ) === true ) {
				$feedly_count = trim( $cnt[1] );
			}
		}
	}
	return $feedly_count;
}
endif;

/*---------------------------------------------------------------------------
 * SNS カウントのリアルタイムでの取得 (ajax で取得)
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_sns_real' ) === false ):
function thk_sns_real() {
	$sns = new sns_real();
	$sns->thk_sns_real();
	exit;
}
endif;

/*---------------------------------------------------------------------------
 * AMP 用のパーマリンク取得
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_get_amp_permalink' ) === false ):
function thk_get_amp_permalink( $post_id ) {
	$paged = get_query_var('page');
	if( empty( $paged ) ) $paged = 1;

	if( $paged > 1 ) {
		$amplink = wp_get_canonical_url( $post_id );
		$amplink = add_query_arg( 'amp', 1, $amplink );
	}
	elseif( get_option( 'permalink_structure' ) != '' ) {
		$amplink = trailingslashit( get_permalink( $post_id ) ) . user_trailingslashit( 'amp' );
	}
	else {
		$amplink = add_query_arg( 'amp', 1, get_permalink( $post_id ) );
	}
	return $amplink;
}
endif;

/*---------------------------------------------------------------------------
 * AMP の固定フロントページ ENDPOINT (FAKE)
 *---------------------------------------------------------------------------*/
if ( function_exists( 'set_fake_root_endpoint_for_amp' ) === false ):
function set_fake_root_endpoint_for_amp() {
	$page_on_front = wp_cache_get( 'page_on_front', 'luxe' );

	if( $page_on_front === false ) {
		$opts = wp_cache_get( 'alloptions', 'options' );
		wp_cache_set( 'page_on_front', $opts['page_on_front'], 'luxe' );

		$opts['show_on_front'] = 'posts';
		$opts['page_on_front'] = 0;
		wp_cache_replace( 'alloptions', $opts, 'options' );
	}
}
endif;

if ( function_exists( 'remove_fake_root_endpoint_for_amp' ) === false ):
function remove_fake_root_endpoint_for_amp( $page_on_front ) {
	$opts = wp_cache_get( 'alloptions', 'options' );
	$opts['show_on_front'] = 'page';
	$opts['page_on_front'] = $page_on_front;
	wp_cache_replace( 'alloptions', $opts, 'options' );
}
endif;

/*---------------------------------------------------------------------------
 * AMP 用 MU プラグインをコピー
 *---------------------------------------------------------------------------*/
if ( function_exists( 'thk_amp_mu_plugin_copy' ) === false ):
function thk_amp_mu_plugin_copy() {
	if ( function_exists( 'get_plugin_data' ) === false ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	$src = INC . 'luxe-amp-mu.php';
	$dst = WPMU_PLUGIN_DIR . '/luxe-amp-mu.php';
	$sv = 0;
	$dv = 0;

	if( file_exists( $dst ) === true ) {
		$s = get_plugin_data( $src );
		$d = get_plugin_data( $dst );

		$sv = isset( $s['Version'] ) ? $s['Version'] : 0;
		$dv = isset( $d['Version'] ) ? $d['Version'] : 0;
	}

	if( file_exists( $dst ) === false || $sv !== $dv ) {
		if( file_exists( WPMU_PLUGIN_DIR ) === false ) {
			if( wp_mkdir_p( WPMU_PLUGIN_DIR ) === false ) {
				global $wp_filesystem;

				require_once( INC . 'optimize.php' );
				$_filesystem = new thk_filesystem();
				if( $_filesystem->init_filesystem() === false ) return false;

				if( $wp_filesystem->is_dir( WPMU_PLUGIN_DIR ) === false ) {
					$wp_filesystem->mkdir( WPMU_PLUGIN_DIR, FS_CHMOD_DIR );
				}
			}
		}
		if( @copy( $src, $dst ) === false ) {
			return false;
		}
	}
}
endif;

/*---------------------------------------------------------------------------
 * 画像の URL から attachemnt_id を取得する
 *---------------------------------------------------------------------------*/
/***
 Source: https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
 ***/
if( function_exists( 'pn_get_attachment_id_from_url' ) === false ):
function pn_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;
	$attachment_id = false;

	// If there is no url, return.
	if( '' == $attachment_url ) return;

	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();

	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	}
	return $attachment_id;
}
endif;

/*---------------------------------------------------------------------------
 * 画像の URL から画像情報を取得する
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_get_image_size' ) === false ):
function thk_get_image_size( $src ){
	$ret = false;

	if( stripos( $src, WP_CONTENT_URL ) !== false ) {
		$src = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $src );
	}
	else {
		return $ret;
	}

	$up_dir = wp_upload_dir();

	if( is_callable( 'getimagesize' ) === true ) {
		if( isset( $up_dir['baseurl'] ) && isset( $up_dir['basedir'] ) ) {
			$replace = str_replace( $up_dir['baseurl'], $up_dir['basedir'], $src );
			if( file_exists( $replace ) === true ) {
				$ret = getimagesize( $replace );
			}
		}
	}
	return $ret;
}
endif;

/*---------------------------------------------------------------------------
 * 画像の URL から srcset 付きの img タグを生成する
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_create_srcset_img_tag' ) === false ):
function thk_create_srcset_img_tag( $url, $alt = null, $cls = null, $prop_img = false, $prop_logo = false ) {
	global $luxe;

	$aid  = pn_get_attachment_id_from_url( $url );
	$meta = wp_get_attachment_metadata( $aid );

	if( $alt === null ) $alt = get_post( $aid )->post_title;
	$width = isset( $meta['width'] ) ? $meta['width'] : '';
	$height = isset( $meta['height'] ) ? $meta['height'] : '';
	$content = $content = '<img src="' . $url . '" alt="' . $alt . '" width="' . $width . '" height="' . $height . '" ';

	if( !empty( $cls ) ) {
		$content .= 'class="' . $cls . '" ';
	}

	if( $prop_img === true || $prop_logo === true ) {
		if( $prop_img === true && $prop_logo === true ) {
			$content .= 'itemprop="image logo" />';
		}
		elseif( $prop_logo === true ) {
			$content .= 'itemprop="logo" />';
		}
		else {
			$content .= 'itemprop="image" />';
		}
	}
	else {
		$content .= '/>';
	}

	$ret = wp_image_add_srcset_and_sizes( $content, $meta, $aid );

	if( isset( $luxe['amp'] ) ) {
		if( stripos( $ret, ' sizes=' ) === false ) {
			$ret = preg_replace( '/<img ([^>]+?)\s*\/*>/', '<amp-img layout="responsive" $1 sizes="(max-width:' . $width . 'px) 100vw,' . $width .'px"></amp-img>', $ret );
		}
		else {
			$ret = preg_replace( '/<img ([^>]+?)\s*\/*>/', '<amp-img layout="responsive" $1></amp-img>', $ret );
		}
	}
	return $ret;
}
endif;

/*---------------------------------------------------------------------------
 * remote request ( wp_remote_request -> wp_filesystem )
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_remote_request' ) === false ):
function thk_remote_request( $url, $sslverify = false ){
	// 2段階で取得を試みる ( wp_remote_request -> wp_filesystem )

	$ret  = false;
	$code = 0;

	$results = wp_remote_request( $url, array(
		'timeout'	=> 30,
		'redirection'	=> 5,
		'compress'	=> true,  // 文字化け対策
		'sslverify'	=> $sslverify, // 悩ましい
		'user-agent'	=> $_SERVER['HTTP_USER_AGENT']
	) );
	if( is_wp_error( $results ) === false ) {
		$code = wp_remote_retrieve_response_code( $results );

		if( $code !== 200 ) {
			$msg = wp_remote_retrieve_response_message( $results );
			$ret = array( $code, $msg );
		}
		else {
			$ret = wp_remote_retrieve_body( $results );
		}
	}

	/* この処理に来ることは、ほぼあり得ないけど、一応 $wp_filesystem->get_contents での取得も入れておく*/
	if( $ret === false ) {
		require_once( INC . 'optimize.php' );
		global $wp_filesystem;

		$filesystem = new thk_filesystem();
		if( $filesystem->init_filesystem( site_url() ) !== false ) {
			$ret = $wp_filesystem->get_contents( $url );
		}
	}

	return $ret;
}
endif;

/*---------------------------------------------------------------------------
 * remove URL
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_remove_url' ) === false ):
function thk_remove_url( $value ){
	$url_reg = '/(https?|ftp|HTTPS?|FTP)(:\/\/[-_\.!~*\'()a-zA-Z0-9;\/?:\@&;=+\$,%#]+)/';
	return  preg_replace( $url_reg, '', $value );
}
endif;

/*---------------------------------------------------------------------------
 * URL Encode と Convert
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_encode' ) === false ):
function thk_encode( $value ){
	return rawurlencode( thk_convert( $value ) );
}
endif;

if( function_exists( 'thk_convert' ) === false ):
function thk_convert( $value ){
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
if( function_exists( 'thk_decode' ) === false ):
function thk_decode( $value ){
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
 * Error message
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_error_msg' ) === false ):
function thk_error_msg( $result ) {
	echo '<div style="margin:50px 100px;font-weight:bold">';
	echo '<p>' . $result->get_error_message() . '</p>';
	echo '<p>' . $result->get_error_data() . '</p>';
	echo '</div>';
}
endif;

/*---------------------------------------------------------------------------
 * URLを組み立て( PECL の http_build_url 代替版)
 *---------------------------------------------------------------------------*/
/***
 Source: http://stackoverflow.com/questions/7751679/php-http-build-url-and-pecl-install
 ***/

if( function_exists( 'http_build_url' ) === false ) {
	define( 'HTTP_URL_REPLACE', 1 );		// Replace every part of the first URL when there's one of the second URL
	define( 'HTTP_URL_JOIN_PATH', 2 );		// Join relative paths
	define( 'HTTP_URL_JOIN_QUERY', 4 );		// Join query strings
	define( 'HTTP_URL_STRIP_USER', 8 );		// Strip any user authentication information
	define( 'HTTP_URL_STRIP_PASS', 16 );		// Strip any password authentication information
	define( 'HTTP_URL_STRIP_AUTH', 32 );		// Strip any authentication information
	define( 'HTTP_URL_STRIP_PORT', 64 );		// Strip explicit port numbers
	define( 'HTTP_URL_STRIP_PATH', 128 );		// Strip complete path
	define( 'HTTP_URL_STRIP_QUERY', 256 );		// Strip query string
	define( 'HTTP_URL_STRIP_FRAGMENT', 512 );	// Strip any fragments (#identifier)
	define( 'HTTP_URL_STRIP_ALL', 1024 );		// Strip anything but scheme and host

	// Build an URL
	// The parts of the second URL will be merged into the first according to the flags argument. 
	// 
	// @param mixed	(Part(s) of) an URL in form of a string or associative array like parse_url() returns
	// @param mixed	Same as the first argument
	// @param int	A bitmask of binary or'ed HTTP_URL constants (Optional)HTTP_URL_REPLACE is the default
	// @param array	If set, it will be filled with the parts of the composed url like parse_url() would return 
	function http_build_url( $url, $parts=array(), $flags=HTTP_URL_REPLACE, &$new_url=false ) {
		$keys = array( 'user', 'pass', 'port', 'path', 'query', 'fragment' );

		// HTTP_URL_STRIP_ALL becomes all the HTTP_URL_STRIP_Xs
		if( $flags & HTTP_URL_STRIP_ALL ) {
			$flags |= HTTP_URL_STRIP_USER;
			$flags |= HTTP_URL_STRIP_PASS;
			$flags |= HTTP_URL_STRIP_PORT;
			$flags |= HTTP_URL_STRIP_PATH;
			$flags |= HTTP_URL_STRIP_QUERY;
			$flags |= HTTP_URL_STRIP_FRAGMENT;
		}
		// HTTP_URL_STRIP_AUTH becomes HTTP_URL_STRIP_USER and HTTP_URL_STRIP_PASS
		elseif( $flags & HTTP_URL_STRIP_AUTH ) {
			$flags |= HTTP_URL_STRIP_USER;
			$flags |= HTTP_URL_STRIP_PASS;
		}

		// Parse the original URL
		$parse_url = parse_url( $url );

		// Scheme and Host are always replaced
		if( isset( $parts['scheme'] ) ) $parse_url['scheme'] = $parts['scheme'];
		if( isset( $parts['host'] ) ) $parse_url['host'] = $parts['host'];

		// (If applicable) Replace the original URL with it's new parts
		if( $flags & HTTP_URL_REPLACE ) {
			foreach( $keys as $key ) {
				if( isset( $parts[$key] ) ) $parse_url[$key] = $parts[$key];
			}
		}
		else {
			// Join the original URL path with the new path
			if( isset( $parts['path'] ) && ( $flags & HTTP_URL_JOIN_PATH ) ) {
				if( isset( $parse_url['path'] ) ) {
					$parse_url['path'] = rtrim( str_replace( basename( $parse_url['path'] ), '', $parse_url['path'] ), '/' ) . '/' . ltrim( $parts['path'], '/' );
				}
				else {
					$parse_url['path'] = $parts['path'];
				}
			}

			// Join the original query string with the new query string
			if( isset( $parts['query'] ) && ( $flags & HTTP_URL_JOIN_QUERY ) ) {
				if( isset($parse_url['query'] ) ) {
					$parse_url['query'] .= '&' . $parts['query'];
				}
				else {
					$parse_url['query'] = $parts['query'];
				}
			}
		}

		// Strips all the applicable sections of the URL
		// Note: Scheme and Host are never stripped
		foreach( $keys as $key ) {
			if( $flags & (int)constant( 'HTTP_URL_STRIP_' . strtoupper( $key ) ) ) unset( $parse_url[$key] );
		}
		$new_url = $parse_url;

		return 
			 ( ( isset($parse_url['scheme'] ) ) ? $parse_url['scheme'] . '://' : '' )
			.( ( isset($parse_url['user'] ) ) ? $parse_url['user'] . ( ( isset( $parse_url['pass'] ) ) ? ':' . $parse_url['pass'] : '' ) .'@' : '' )
			.( ( isset($parse_url['host'] ) ) ? $parse_url['host'] : '' )
			.( ( isset($parse_url['port'] ) ) ? ':' . $parse_url['port'] : '' )
			.( ( isset($parse_url['path'] ) ) ? $parse_url['path'] : '' )
			.( ( isset($parse_url['query'] ) ) ? '?' . $parse_url['query'] : '' )
			.( ( isset($parse_url['fragment'] ) ) ? '#' . $parse_url['fragment'] : '' )
		;
	}
}
