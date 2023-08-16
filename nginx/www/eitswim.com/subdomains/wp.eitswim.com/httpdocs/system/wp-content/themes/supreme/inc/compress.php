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
require_once( INC . 'optimize.php' );
require_once( INC . 'web-font.php' );

/*---------------------------------------------------------------------------
 * CSS and Javascript 圧縮・結合
 *---------------------------------------------------------------------------*/
if( function_exists('thk_compress') === false ):
function thk_compress() {
	global $luxe, $wp_filesystem;

	require_once( INC . 'const.php' );
	require_once( INC . 'carray.php' );

	thk_cleanup( true );
	thk_php_strip();

	$conf = new defConfig();
	$conf->set_luxe_variable();

	$optimize = new thk_optimize();
	$optimize->css_optimize( $optimize->css_optimize_init(), 'style.min.css', true );
	$optimize->css_async_optimize( $optimize->css_async_optimize_init(), true );

	$optimize->js_async_optimize();
	$optimize->js_search_highlight();

	thk_create_template_style();

	if( isset( $luxe['amp_enable'] ) ) {
		thk_create_amp_style( $optimize->css_amp_optimize() );
	}

	// jQuery 使用しないならここで終わり (同梱の Javascript が全て jQuery 依存なので、これ以降の処理は意味ない)
	if( !isset( $luxe['jquery_load'] ) ) return;

	$optimize->js_defer_optimize();
	$optimize->jquery_optimize();

	return;
}
add_action( 'customize_save_after', 'thk_compress', 75 );
endif;

/*---------------------------------------------------------------------------
 * 親テーマのスタイルシートを子テーマに結合
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_parent_css_bind' ) === false ):
function thk_parent_css_bind() {
	global $luxe, $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	$parent_css = TPATH . DSEP . 'style.min.css';
	$child_css  = SPATH . DSEP . 'style.css';
	$child_min  = SPATH . DSEP . 'style.min.css';

	$css    = '';
	$parent = '';
	$child  = '';

	if( is_child_theme() === false || get_theme_mod( 'all_clear', false ) === true || $luxe['child_css_compress'] === 'none' ) {
		return;
	}
	elseif( $luxe['child_css_compress'] !== 'bind' ) {
		return thk_child_css_min( '' );
	}

	if( file_exists( $parent_css ) === true ) $parent = $wp_filesystem->get_contents( $parent_css );
	if( file_exists( $child_css  ) === true ) $child  = $wp_filesystem->get_contents( $child_css  );

	$css = trim( $parent ) . "\n/*! luxe child css */" . trim( $child );

	return thk_child_css_min( $css );
}
add_action( 'customize_save_after', 'thk_parent_css_bind', 80 );
endif;

/*---------------------------------------------------------------------------
 * 子テーマの CSS 圧縮・最適化 (カスタマイズ画面のプレビューでは圧縮されてない方を読み込む)
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_child_css_min' ) === false ):
function thk_child_css_min( $css = '' ) {
	global $luxe, $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	$style_min = SPATH . DSEP . 'style.min.css';

	if( empty( $css ) ) {
		$style_css = SPATH . DSEP . 'style.css';
		$css = $wp_filesystem->get_contents( $style_css );
	}

	$css = thk_cssmin( $css );

	$filesystem->file_save( $style_min, $css );

	return;
}
endif;

/*---------------------------------------------------------------------------
 * 子テーマの Javascript を圧縮・結合
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_child_js_comp' ) === false ):
function thk_child_js_comp() {
	global $luxe, $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	if( is_child_theme() === false || $luxe['child_js_compress'] === 'none' || $luxe['child_js_compress'] === 'noload' ) {
		$child_min = SPATH . DSEP . 'luxech.min.js';
		if( file_exists( $child_min ) === true ) {
			if( $wp_filesystem->delete( $child_min ) === false ) {
				$filesystem->file_save( $child_min, null );
			}
		}
		return;
	}

	$js = '';
	$child_js = SPATH . DSEP . 'luxech.js';

	if( file_exists( $child_js ) === true ) {
		$js .= $wp_filesystem->get_contents( $child_js );
		$js .= "\n";
	}

	$files = array();
	if( isset( $luxe['child_js_file_1'] ) ) $files[] = $luxe['child_js_file_1'];
	if( isset( $luxe['child_js_file_2'] ) ) $files[] = $luxe['child_js_file_2'];
	if( isset( $luxe['child_js_file_3'] ) ) $files[] = $luxe['child_js_file_3'];

	foreach( (array)$files as $value ) {
		if( strpos( $value, DSEP ) !== false || strpos( $value, '/' ) !== false ) continue;
		if( file_exists( SPATH . DSEP . $value . '.js' ) === true ) {
			$js .= $wp_filesystem->get_contents( SPATH . DSEP . $value . '.js' );
			$js .= "\n";
		}
	}
	$js = thk_jsmin( $js );

	$filesystem->file_save( SPATH . DSEP . 'luxech.min.js', $js );

	return ;
}
add_action( 'customize_save_after', 'thk_child_js_comp', 80 );
endif;

/*---------------------------------------------------------------------------
 * CSS をインラインで直接読み込む場合用の PATH 置換済み CSS を生成
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_create_inline_style' ) === false ):
function thk_create_inline_style() {
	global $luxe, $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	$styles = array(
		TPATH . DSEP . 'style.css'	=> '',
		TPATH . DSEP . 'style.min.css'	=> '',
		SPATH . DSEP . 'style.css'	=> '',
		SPATH . DSEP . 'style.min.css'	=> ''
	);

	if( $luxe['child_css_compress'] !== 'bind' ) {
		if( isset( $luxe['parent_css_uncompress'] ) ) {
			$styles[TPATH . DSEP . 'style.css'] = TPATH . DSEP . 'style.replace.min.css';
		}
		else {
			$styles[TPATH . DSEP . 'style.min.css'] = TPATH . DSEP . 'style.replace.min.css';
		}
	}

	if( isset( $luxe['child_css'] ) && TDEL !== SDEL ) {
		if( $luxe['child_css_compress'] !== 'none' ) {
			$styles[SPATH . DSEP . 'style.min.css'] = SPATH . DSEP . 'style.replace.min.css';
		}
		else {
			$styles[SPATH . DSEP . 'style.css'] = SPATH . DSEP . 'style.replace.min.css';
		}
	}

	foreach( $styles as $in => $out ) {
		if( empty( $out ) ) continue;

		if( isset( $luxe['css_to_style'] ) ) {
			$conf = new defConfig();
			$save = '';
			$save = $wp_filesystem->get_contents( $in );

			if( stripos( $in, TPATH . DSEP ) !== false ) {
				$save = thk_path_to_root( $save, TDEL );
			}
			else {
				$save = thk_path_to_root( $save, SDEL );
			}
			$save = str_replace( '@charset "UTF-8";', '', $save );

			if( $filesystem->file_save( $out, $save ) === false ) return false;
		}
	}

	return true;
}
add_action( 'customize_save_after', 'thk_create_inline_style', 85 );
endif;

/*---------------------------------------------------------------------------
 * Amp 用 CSS 生成
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_create_amp_style' ) === false ):
function thk_create_amp_style( $files ) {
	global $luxe, $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	$save = '';
	foreach( $files as $val ) {
		$css = str_replace( '../', './', $wp_filesystem->get_contents( $val ) );
		$css = str_replace( '!important', '', $css );
		$save .= $css;
	}

	// create web fonts stylesheet
	$webfont = new Create_Web_Font();
	$font_arr = $webfont->create_web_font_stylesheet();
	$save .= $font_arr['font_family'] . "\n";

	// 管理画面でのカスタマイズ内容
	$luxe['amp_css'] = true;
	if( $luxe['column_style'] === '3column' ) {
		$luxe['column_style'] = '2column';
	}

	$css = trim( str_replace( array( '<style>', '</style>' ), '', thk_custom_css() ) );
	if( $css === '/*! luxe customizer css */' ) {
		$save .= '';
	}
	else {
		$css = str_replace( '/*! luxe customizer css */' . "\n", '/*! luxe customizer css */', $css );
		$css = str_replace( '!important', '', $css );
		$save .= $css;
	}

	$save = thk_path_to_root( $save, TDEL );
	$save = str_replace( '@charset "UTF-8";', '', $save );
	$save = thk_cssmin( $save );

	if( $filesystem->file_save( TPATH . DSEP . 'style-amp.min.css', $save ) === false ) return false;

	if( TDEL !== SDEL ) {
		$save = str_replace( '../', './', $wp_filesystem->get_contents( SPATH . DSEP . 'style-amp.css' ) );
		$save = thk_path_to_root( $save, SDEL );
		$save = thk_cssmin( $save );
		$save = "/*! luxe child css */" . trim( $save );

		if( $filesystem->file_save( SPATH . DSEP . 'style-amp.min.css', $save ) === false ) return false;
	}

	return true;
}
add_action( 'customize_save_after', 'thk_create_amp_style', 100 );
endif;

/*---------------------------------------------------------------------------
 * thk_php_strip
 *---------------------------------------------------------------------------*/
if( function_exists('thk_php_strip') === false ):
function thk_php_strip() {
	if( is_admin() === false ) {
		$cjphp = php_strip_whitespace( INC . 'crete' . '-' . 'java' . 'script' . '.php' );
		$cjphp = preg_replace( '/\/\*.+?\*\//ism', '', $cjphp );
		$cjphp = preg_replace( '/\/\/.+?\n/im', '', $cjphp );

		if( substr_count( $cjphp, '{$ins' . '_func}') < 5 ) thk_shutdown();
		if( substr_count( $cjphp, '{$wt_' . 'txt[1]}') < 1 ) thk_shutdown();
	}
}
endif;

/*---------------------------------------------------------------------------
 * CSS 内の相対パスをルートから始まるURIに変換する処理
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_path_to_root' ) === false ):
function thk_path_to_root( $css, $theme_path ) {
	// url(data:～); url(http://～); url(https://～); の形を url のパス変換処理にかけないよう退避
	preg_match_all( "/url\(\s*([\"|']?)(data\:|http\:\/\/|https\:\/\/)[^\)]+?\)[^;|\}]*?[;|\}]/i", $css, $data_array );

	foreach( array_unique( $data_array[0] ) as $data ) {
		$css = str_replace( $data, md5( $data ), $css );
	}

	// css 内で ../ の形式で書かれた相対パスをルートから始まるURIに変換する
	$css_dir = str_replace( '//' . $_SERVER['HTTP_HOST'], '', $theme_path );
	$dir_explode = array_filter( explode( '/', $css_dir ) );

	$relative = '';
	$relative_array = array();

	foreach( $dir_explode as $val ) {
		$relative .= '/' . $val;
		$relative_array = array_merge( $relative_array, array( $relative => null ) );
	}

	$sep = '';
	$tmp_array = array();

	foreach( array_reverse( $relative_array ) as $key => $val ) {
		$tmp_array = array_merge( $tmp_array, array( $key => $sep ) );
		$sep .= '../';
	}

	$path = '';
	$relative_array = array_reverse( $tmp_array );

	foreach( $relative_array as $path => $val ) {
		$css = str_replace( $val, $path . '/', $css );
	}

	$css = str_replace( './', '', $css );

	// css 内で ../ の形式以外の相対パスをルートから始まるURIに変換する
	$css = preg_replace( "/(url\([\"|']?)((?:[^\/][A-z0-9]|\.\/).+?)([\"|']*\))/i", '${1}' . $path . '/' . '${2}${3}', $css );

	// url(data:～); url(http://～); url(https://～);  の形を元に戻す
	foreach( array_unique( $data_array[0] ) as $data ) {
		$css = str_replace( md5( $data ), $data, $css );
	}

	return $css;
}
endif;

/*---------------------------------------------------------------------------
 * テンプレートごとにカラム数が違う場合の3カラム用 CSS 生成
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_create_template_style' ) === false ):
function thk_create_template_style() {
	global $luxe, $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	$styles = array(
		'1column' => 'style.1col.min.css',
		'2column' => 'style.2col.min.css',
		'3column' => 'style.3col.min.css'
	);

	foreach( $styles as $key => $style ) {
		if(
			$luxe['column_home'] === '1column' || $luxe['column_post'] === '1column' || $luxe['column_page'] === '1column' || $luxe['column_archive'] === '1column' ||
			$luxe['column_home'] === '3column' || $luxe['column_post'] === '3column' || $luxe['column_page'] === '3column' || $luxe['column_archive'] === '3column' ||
			( $luxe['column3'] === '1column' && ( $luxe['column_home'] !== 'default' || $luxe['column_post'] !== 'default' || $luxe['column_page'] !== 'default' || $luxe['column_archive'] !== 'default' ) ) ||
			( $luxe['column3'] === '3column' && ( $luxe['column_home'] !== 'default' || $luxe['column_post'] !== 'default' || $luxe['column_page'] !== 'default' || $luxe['column_archive'] !== 'default' ) )
		) {
			require_once( INC . 'colors.php' );

			$conf = new defConfig();
			$colors_class = new thk_colors();
			$defaults = $conf->default_variables();
			$default_colors = $conf->over_all_default_colors();

			$save = format_media_query( thk_adjust_column_css( array(), $key, $defaults, $default_colors, $colors_class ), $defaults );

			if( $filesystem->file_save( TPATH . DSEP . $style, thk_cssmin( $save ) ) === false ) return false;
		}
	}
	return true;
}
endif;

/*---------------------------------------------------------------------------
 * CSS Compress
 *---------------------------------------------------------------------------*/
if( function_exists('thk_cssmin') === false ):
function thk_cssmin( $css ) {
	global $wp_filesystem;

	// get version number
	$ver = '1.00';
	$curent = wp_get_theme();
	$copyright = '';

	if( TDEL !== SDEL ) {
		$parent = wp_get_theme( $curent->get('Template') );
		$ver = $parent->get('Version');
	}
	else {
		$ver = $curent->get('Version');
	}

	$css = preg_replace( '/(\.woff|\.woff2|\.otf|\.eot|\.ttf|\.svg)\?[^\'|\)]+?([\'|\)])/ism', "$1$2", $css );
	$css = str_replace( '/*! luxe', '/* luxe', $css );
	$css = str_replace( '/*!', '/*', $css );
	$css = str_replace( '/* luxe', '/*! luxe', $css );

	if( class_exists('CSSmin') === false ) {
		require( INC . 'cssmin.php' );
	}
	$minify = new CSSmin();
	if( method_exists( $minify, 'run' ) === true ) {
		$css = trim( $minify->run( thk_convert( $css ) ) );
		$css = str_replace( array("\r\n", "\r", "\n"), "\n", $css );
	}

	if( !empty( $css ) && stripos( $css, '/*!' ) !== false ) {
		$copyright = <<< COPYRIGHT
/*! Luxeritas WordPress Theme {$ver} - free/libre wordpress platform
 * @copyright Copyright (C) 2015 Thought is free. */
COPYRIGHT;
	}

	return $copyright . $css;
}
endif;

/*---------------------------------------------------------------------------
 * Javascript Compress
 *---------------------------------------------------------------------------*/
if( function_exists('thk_jsmin') === false ):
function thk_jsmin( $js ) {
	if( class_exists('JSMin') === false ) {
		require( INC . 'jsmin.php' );
	}
	$js = trim( JSMin::minify( thk_convert( $js ) ) );
	$js = str_replace( array("\r\n", "\r", "\n"), "\n", $js );
	return $js;
}
endif;

/*---------------------------------------------------------------------------
 * delete theme_mod that is no longer necessary
 *---------------------------------------------------------------------------*/
if( function_exists('thk_empty_remove') === false ):
function thk_empty_remove() {
	$conf = new defConfig();
	$luxe_defaults = $conf->default_variables();

	//$mods = get_theme_mods();
	$mods = get_option( 'theme_mods_' . THEME );
	if( is_array( $mods ) === true ) {
		foreach( $mods as $key => $val) {
			if( $val === null ) remove_theme_mod( $key );
			if(
				( array_key_exists( $key, $luxe_defaults ) === false && is_array( $val ) === false ) ||
				( array_key_exists( $key, $luxe_defaults ) === true && $luxe_defaults[$key] == $val )
			) {
				remove_theme_mod( $key );
			}
		}
	}
}
add_action( 'customize_save_after', 'thk_empty_remove', 90 );
endif;

/*---------------------------------------------------------------------------
 * thk_shutdown
 *---------------------------------------------------------------------------*/
if( function_exists('thk_shutdown') === false ):
function thk_shutdown() {
	if( is_admin() === false ) exit;
}
endif;

/*---------------------------------------------------------------------------
 * cleanup
 *---------------------------------------------------------------------------*/
if( function_exists('thk_cleanup') === false ):
function thk_cleanup( $file_only = false ) {
	global $wp_filesystem;

	$filesystem = new thk_filesystem();
	$filesystem->init_filesystem();

	if( get_theme_mod( 'all_clear', false ) !== false || $file_only === true ) {
		$del_files = array(
			TPATH . DSEP . 'style.min.css',
			TPATH . DSEP . 'style.async.min.css',
			TPATH . DSEP . 'style-amp.min.css',
			TPATH . DSEP . 'style.replace.min.css',
			TPATH . DSEP . 'style.1col.min.css',
			TPATH . DSEP . 'style.2col.min.css',
			TPATH . DSEP . 'style.3col.min.css',
			TPATH . DSEP . 'plugins.min.css',

			TPATH . DSEP . 'js' . DSEP . 'luxe.min.js',
			TPATH . DSEP . 'js' . DSEP . 'luxe.async.min.js',

			TPATH . DSEP . 'js' . DSEP . 'jquery.bind.min.js',
			TPATH . DSEP . 'js' . DSEP . 'jquery.luxe.min.js',
			TPATH . DSEP . 'js' . DSEP . 'thk-highlight.min.js',

			SPATH . DSEP . 'style.min.css',
			SPATH . DSEP . 'style-amp.min.css',
			SPATH . DSEP . 'style.replace.min.css',
			SPATH . DSEP . 'luxech.min.js',
		);

		foreach( $del_files as $del_file ) {
			if( file_exists( $del_file ) === true ) {
				$wp_filesystem->delete( $del_file );
			}
		}

		foreach( (array)glob( TPATH . DSEP . 'webfonts' . DSEP . '*' ) as $del_file ) {
			if( stripos( $del_file, 'index.php' ) === false ) {
				$wp_filesystem->delete( $del_file );
			}
		}

		if( $file_only === true ) return;

		remove_theme_mods();

		sns_count_cache_cleanup( true, true, false );
		blogcard_cache_cleanup( true, false );
	}
	else {
		if( get_theme_mod( 'sns_count_cache_cleanup', false ) !== false ) {
			remove_theme_mod( 'sns_count_cache_cleanup' );
			sns_count_cache_cleanup( false, true, false );
		}

		if( get_theme_mod( 'blogcard_cache_cleanup', false ) !== false ) {
			remove_theme_mod( 'blogcard_cache_cleanup' );
			remove_theme_mod( 'blogcard_cache_expire_cleanup' );
			blogcard_cache_cleanup( false, false );
		}
		elseif( get_theme_mod( 'blogcard_cache_expire_cleanup', false ) !== false ) {
			remove_theme_mod( 'blogcard_cache_expire_cleanup' );
			blogcard_cache_cleanup( false, true );
		}
	}
}
add_action( 'customize_save_after', 'thk_cleanup', 99 );
endif;
