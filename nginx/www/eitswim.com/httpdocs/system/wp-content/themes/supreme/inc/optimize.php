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
 * Optimize
 *---------------------------------------------------------------------------*/
class thk_optimize {
	private $_thk_files	= null;
	private $_filesystem	= null;
	private $_js_dir	= null;
	private $_css_dir	= null;
	private $_tmpl_dir	= null;

	public function __construct() {
		require_once( INC . 'files.php' );
		require_once( INC . 'crete-javascript.php' );

		$this->_js_dir   = TPATH . DSEP . 'js' . DSEP;
		$this->_css_dir  = TPATH . DSEP . 'css' . DSEP;
		$this->_tmpl_dir = TPATH . DSEP . 'styles' . DSEP;

		$this->_thk_files = new thk_files();

		// filesystem initialization
		$this->_filesystem = new thk_filesystem();
		if( $this->_filesystem->init_filesystem() === false ) return false;
	}

	/*
	 * CSS Optimize initialization
	 */
	public function css_optimize_init() {
		global $luxe;

		$files = $this->_thk_files->styles();

		// get overall image
		if( get_theme_mod( 'all_clear', false ) === false ) {
			$files['style_thk'] = TPATH . DSEP . get_overall_image();
		}

		// file exists check
		foreach( $files as $key => $val ) {
			if( file_exists( $val ) === false ) unset( $files[$key] );
		}

		// determining the conditions
		if( isset( $luxe['luxe_mode_select'] ) && $luxe['luxe_mode_select'] === 'bootstrap' ) {
			unset( $files['luxe-mode'] );
			unset( $files['bootstrap4'] );
		}
		elseif( isset( $luxe['luxe_mode_select'] ) && $luxe['luxe_mode_select'] === 'bootstrap4' ) {
			unset( $files['luxe-mode'] );
			unset( $files['bootstrap'] );
		}
		else {
			unset( $files['bootstrap'] );
			unset( $files['bootstrap4'] );
		}

		// Font Awesome
		if( $luxe['awesome_load'] !== 'sync' ) {
			unset( $files['awesome'] );
			unset( $files['awesome-minimum'] );
			unset( $files['icomoon'] );
		}
		else {
			if( $luxe['awesome_css_type'] === 'full' ) {
				unset( $files['awesome-minimum'] );
			}
			else {
				unset( $files['awesome'] );
			}
		}

		// Grid layout
		if( !isset( $luxe['grid_enable'] ) ) {
			unset( $files['grid'] );
		}

		// sns buttons
		if( isset( $luxe['sns_tops_enable'] ) || isset( $luxe['sns_bottoms_enable'] ) ) {
			if( $luxe['sns_tops_type'] !== 'color' && $luxe['sns_tops_type'] !== 'white' && $luxe['sns_bottoms_type'] !== 'color' && $luxe['sns_bottoms_type'] !== 'white' ) {
				unset( $files['sns'] );
			}
			if( $luxe['sns_tops_type'] !== 'flatc' && $luxe['sns_tops_type'] !== 'flatw' && $luxe['sns_bottoms_type'] !== 'flatc' && $luxe['sns_bottoms_type'] !== 'flatw' ) {
				unset( $files['sns-flat'] );
			}
			if( $luxe['sns_tops_type'] !== 'iconc' && $luxe['sns_tops_type'] !== 'iconw' && $luxe['sns_bottoms_type'] !== 'iconc' && $luxe['sns_bottoms_type'] !== 'iconw' ) {
				unset( $files['sns-icon'] );
			}
			if( $luxe['sns_tops_type'] !== 'normal' && $luxe['sns_bottoms_type'] !== 'normal' ) {
				unset( $files['sns-normal'] );
			}
		}

		if( !isset( $luxe['blogcard_enable'] ) )	unset( $files['blogcard'] );
		if( !isset( $luxe['css_search'] ) )		unset( $files['search'] );
		if( !isset( $luxe['css_archive'] ) )		unset( $files['archive'] );
		if( !isset( $luxe['css_calendar'] ) )		unset( $files['calendar'] );
		if( !isset( $luxe['css_new_post'] ) )		unset( $files['new-post'] );
		if( !isset( $luxe['css_rcomments'] ) )		unset( $files['rcomments'] );
		if( !isset( $luxe['css_adsense'] ) )		unset( $files['adsense'] );
		if( !isset( $luxe['css_follow_button'] ) )	unset( $files['follow-button'] );
		if( !isset( $luxe['css_rss_feedly'] ) )		unset( $files['rss-feedly'] );
		if( !isset( $luxe['css_qr_code'] ) )		unset( $files['qr-code'] );

		if( !isset( $luxe['global_navi_mobile_type'] ) ) {
			unset( $files['mobile-common'] );
			unset( $files['mobile-menu'] );
			unset( $files['mobile-luxury'] );
		}
		elseif( isset( $luxe['global_navi_mobile_type'] ) && $luxe['global_navi_mobile_type'] === 'luxury' ) {
			unset( $files['mobile-menu'] );
		}
		else {
			unset( $files['mobile-luxury'] );
		}

		if( !isset( $luxe['head_band_search'] ) ) {
			unset( $files['head-search'] );
		}

		return array_filter( $files, 'strlen' );
	}

	/*
	 * CSS Optimize
	 */
	public function css_optimize( $files = array(), $name = 'style.min.css', $dir_replace_flag = false ) {
		global $luxe, $wp_filesystem;
		$contents = array();

		$style_min = TPATH . DSEP . $name;

		// get stylesheet file content
		$replaces = $this->_thk_files->dir_replace();
		foreach( $files as $key => $val ) {
			if( isset( $replaces[$key] ) && $dir_replace_flag === true ) {
				$contents[$key] = str_replace( '../', './', $wp_filesystem->get_contents( $val ) );
			}
			else {
				$contents[$key] = $wp_filesystem->get_contents( $val );
			}
		}

		// create web fonts stylesheet
		$webfont = new Create_Web_Font();
		$font_arr = $webfont->create_web_font_stylesheet();

		$contents['font_alphabet'] = $font_arr['font_alphabet'];
		$contents['font_japanese'] = $font_arr['font_japanese'];
		$contents['font_family']   = $font_arr['font_family'];

		// get luxe customizer css
		if( get_theme_mod( 'all_clear', false ) === false ) {
			$files['style_thk'] = TPATH . DSEP . get_overall_image();

			// 管理画面でのカスタマイズ内容
			$contents['customize'] = trim( str_replace( array( '<style>', '</style>' ), '', thk_custom_css() ) );
			if( $contents['customize'] === '/*! luxe customizer css */' ) {
				$contents['customize'] = '';
			}
			else {
				$contents['customize'] = str_replace( '/*! luxe customizer css */' . "\n", '/*! luxe customizer css */', $contents['customize'] );
			}
		}

		// css bind
		$save = '';
		foreach( $contents as $value ) {
			$save .= $value . "\n";
		}

		// css compression and save
		if( isset( $luxe['parent_css_uncompress'] ) ) {
			if( $this->_filesystem->file_save( $style_min, $save ) === false ) return false;
		}
		else {
			if( $this->_filesystem->file_save( $style_min, thk_cssmin( $save ) ) === false ) return false;
		}

		return true;
	}

	/*
	 * Asynchronous CSS Optimize initialization
	 */
	public function css_async_optimize_init() {
		$files = $this->_thk_files->styles_async();

		// file exists check
		foreach( $files as $key => $val ) {
			if( file_exists( $val ) === false ) unset( $files[$key] );
		}
		return array_filter( $files, 'strlen' );
	}

	/*
	 * Asynchronous CSS Optimize
	 */
	public function css_async_optimize( $files = array(), $dir_replace_flag = false ) {
		global $luxe, $wp_filesystem;
		$contents = array();

		$style_min = TPATH . DSEP . 'style.async.min.css';

		// Font Awesome
		if( $luxe['awesome_load'] !== 'async' ) {
			unset( $files['awesome'] );
			unset( $files['awesome-minimum'] );
			unset( $files['icomoon'] );
		}
		else {
			if( $luxe['awesome_css_type'] === 'full' ) {
				unset( $files['awesome-minimum'] );
			}
			else {
				unset( $files['awesome'] );
			}
		}

		// tosrus
		if( $luxe['gallery_type'] !== 'tosrus' ) {
			unset( $files['tosrus'] );
		}
		// lightcase
		if( $luxe['gallery_type'] !== 'lightcase' ) {
			unset( $files['lightcase'] );
		}
		// fluidbox
		if( $luxe['gallery_type'] !== 'fluidbox' ) {
			unset( $files['fluidbox'] );
		}

		// Google Autocomplete
		if( !isset( $luxe['autocomplete'] ) ) {
			unset( $files['autocomplete'] );
		}

		// get stylesheet file content
		$replaces = $this->_thk_files->dir_replace();
		foreach( $files as $key => $val ) {
			if( isset( $replaces[$key] ) && $dir_replace_flag === true ) {
				$contents[$key] = str_replace( '../', './', $wp_filesystem->get_contents( $val ) );
			}
			else {
				$contents[$key] = $wp_filesystem->get_contents( $val );
			}
		}

		// css bind
		$save = '';
		foreach( $contents as $value ) {
			$save .= $value . "\n";
		}

		// css compression and save
		if( isset( $luxe['parent_css_uncompress'] ) ) {
			if( $this->_filesystem->file_save( $style_min, $save ) === false ) return false;
		}
		else {
			if( $this->_filesystem->file_save( $style_min, thk_cssmin( $save ) ) === false ) return false;
		}

		return true;
	}

	/*
	 * Amp CSS Optimize initialization
	 */
	public function css_amp_optimize() {
		global $luxe, $wp_filesystem;

		$files = $this->_thk_files->styles_amp();

		// get overall image
		if( get_theme_mod( 'all_clear', false ) === false  ) {
			$files['style_amp'] = TPATH . DSEP . str_replace( '.css', '-amp.css', get_overall_image() );
		}

		// file exists check
		foreach( $files as $key => $val ) {
			if( file_exists( $val ) === false ) unset( $files[$key] );
		}

		if( isset( $luxe['sns_tops_enable'] ) || isset( $luxe['sns_bottoms_enable'] ) ) {
			if( $luxe['sns_tops_type'] === 'normal' ) $luxe['sns_tops_type'] = 'color';
			if( $luxe['sns_bottoms_type'] === 'normal' ) $luxe['sns_bottoms_type'] = 'color';

			if( $luxe['sns_tops_type'] !== 'color' && $luxe['sns_tops_type'] !== 'white' && $luxe['sns_bottoms_type'] !== 'color' && $luxe['sns_bottoms_type'] !== 'white' ) {
				unset( $files['sns'] );
			}
			if( $luxe['sns_tops_type'] !== 'flatc' && $luxe['sns_tops_type'] !== 'flatw' && $luxe['sns_bottoms_type'] !== 'flatc' && $luxe['sns_bottoms_type'] !== 'flatw' ) {
				unset( $files['sns-flat'] );
			}
			if( $luxe['sns_tops_type'] !== 'iconc' && $luxe['sns_tops_type'] !== 'iconw' && $luxe['sns_bottoms_type'] !== 'iconc' && $luxe['sns_bottoms_type'] !== 'iconw' ) {
				unset( $files['sns-icon'] );
			}
		}

		if( !isset( $luxe['css_archive'] ) )		unset( $files['archive'] );
		if( !isset( $luxe['css_calendar'] ) )		unset( $files['calendar'] );
		if( !isset( $luxe['css_new_post'] ) )		unset( $files['new-post'] );
		if( !isset( $luxe['css_rcomments'] ) )		unset( $files['rcomments'] );
		if( !isset( $luxe['css_adsense'] ) )		unset( $files['adsense'] );
		if( !isset( $luxe['css_follow_button'] ) )	unset( $files['follow-button'] );
		if( !isset( $luxe['css_rss_feedly'] ) )		unset( $files['rss-feedly'] );
		if( !isset( $luxe['css_qr_code'] ) )		unset( $files['qr-code'] );

		if( !isset( $luxe['head_band_search'] ) )	unset( $files['head-search'] );

		return array_filter( $files, 'strlen' );
	}

	/*
	 * Synchronous Javascript Optimize
	 */
	public function js_defer_optimize() {
		global $luxe, $wp_filesystem;
		$contents = array();

		$luxe_min = $this->_js_dir . 'luxe.min.js';

		$files = $this->_thk_files->scripts_defer();

		// file exists check
		foreach( $files as $key => $val ) {
			if( $val === true ) continue;
			if( file_exists( $val ) === false ) unset( $files[$key] );
		}

		// jquery.lazyload ( jquery defer )
		if( !isset( $luxe['jquery_load'] ) || !isset( $luxe['lazyload_enable'] ) ) {
			unset( $files['lazyload'] );
		}

		// tosrus
		if( $luxe['gallery_type'] !== 'tosrus' ) {
			unset( $files['tosrus'] );
		}
		// lightcase
		if( $luxe['gallery_type'] !== 'lightcase' ) {
			unset( $files['lightcase'] );
		}
		// fluidbox
		if( $luxe['gallery_type'] !== 'fluidbox' ) {
			unset( $files['fluidbox'] );
			unset( $files['throttle'] );
		}

		// Google Autocomplete
		if( !isset( $luxe['autocomplete'] ) ) {
			unset( $files['autocomplete'] );
		}

		$files = array_filter( $files, 'strlen' );

		// get javascript file content
		$jscript = new create_Javascript();

		foreach( $files as $key => $val ) {
			if( $val !== null ) {
				$contents[$key] = $wp_filesystem->get_contents( $val );
			}

			// Lazy Load Options ( jquery defer )
			if( $key === 'lazyload' ) {
				$contents[$key] .= $jscript->create_lazy_load_script();
			}

			// luxe script
			if( $key === 'luxe' ) {
				$various = $jscript->create_luxe_various_script();
				if( stripos( $various, 'insert' . '_luxe' ) === false || stripos( $various, 'thk' . '_get' . '_yuv' ) === false ) {
					wp_die();
				}
				$contents[$key] .= $various;
				// SNS Count script
				if(
					( isset( $luxe['sns_tops_enable'] ) && isset( $luxe['sns_tops_count'] ) ) ||
					( isset( $luxe['sns_bottoms_enable'] ) && isset( $luxe['sns_bottoms_count'] ) ) ||
					( isset( $luxe['sns_toppage_view'] ) && isset( $luxe['sns_bottoms_count'] ) )
				){
					$contents[$key] .= $jscript->create_sns_count_script();
				}
			}
		}

		// javascript bind
		$save = '';
		foreach( $contents as $value ) {
			$save .= $value . "\n";
		}
		if( $this->_filesystem->file_save( $luxe_min, thk_jsmin( $save ) ) === false ) return false;

		return true;
	}

	/*
	 * Asynchronous Javascript Optimize
	 */
	public function js_async_optimize() {
		global $luxe, $wp_filesystem;
		$contents = array();

		$luxe_async = $this->_js_dir . 'luxe.async.min.js';

		$files = $this->_thk_files->scripts_async();

		// file exists check
		foreach( $files as $key => $val ) {
			if( $val === true ) continue;
			if( file_exists( $val ) === false ) unset( $files[$key] );
		}

		// jquery.lazyload ( jquery no defer )
		if( !isset( $luxe['jquery_load'] ) || isset( $luxe['jquery_defer'] ) || !isset( $luxe['lazyload_enable'] ) ) {
			unset( $files['lazyload'] );
		}

		$files = array_filter( $files, 'strlen' );

		// get Asynchronous javascript file content
		$jscript = new create_Javascript();
		$tdel = pdel( get_template_directory_uri() );
		$sdel = pdel( get_stylesheet_directory_uri() );

		foreach( $files as $key => $val ) {
			if( $key === 'async' ) {
				$contents[$key] = $jscript->create_css_load_script( $tdel . '/style.async.min.css' );
				continue;
			}

			$contents[$key] = $wp_filesystem->get_contents( $val );
		}

		// Asynchronous javascript bind
		$save = '';
		foreach( $contents as $value ) {
			$save .= $value . "\n";
		}

		if( $this->_filesystem->file_save( $luxe_async, thk_jsmin( $save ) ) === false ) return false;

		return true;
	}

	/*
	 * Search highlight script Optimize
	 */
	public function js_search_highlight() {
		global $wp_filesystem;

		$contents = array();
		$files = $this->_thk_files->scripts_search_highlight();

		$luxe_search_highlight = $this->_js_dir . 'thk-highlight.min.js';

		foreach( $files as $key => $val ) {
			$contents[$key] = $wp_filesystem->get_contents( $val );
		}

		$save = '';

		$save .= <<< JQUERY_CHECK
// jQuery が読み込まれてなかったら、実行遅らせる
// 参考 URL： http://jsfiddle.net/ocfzf3bb/2/
var checkReady2 = function(callback) {
	if( window.jQuery ) {
		callback(jQuery);
	} else {
		window.setTimeout( function() {
			checkReady2(callback);
		}, 100 );
	}
};
checkReady2( function($) {

JQUERY_CHECK;

		foreach( $contents as $value ) {
			$save .= $value . "\n";
		}

		$save .= '});';

		if( $this->_filesystem->file_save( $luxe_search_highlight, thk_jsmin( $save ) ) === false ) return false;

		return true;
	}

	/*
	 * jQuery and bootstrap Optimize
	 */
	public function jquery_optimize() {
		global $luxe, $wp_filesystem;
		$contents = array();

		$bind = array(
			$this->_js_dir . 'jquery.bind.min.js',
			$this->_js_dir . 'jquery.luxe.min.js'
		);

		$jquery_migrate = $bind[0];
		if( $luxe['jquery_migrate'] === 'luxeritas' ) {
			$jquery_migrate = $bind[1];
		}

		$files = $this->_thk_files->jquery();

		// file exists check
		foreach( $files as $key => $val ) {
			if( file_exists( $val ) === false ) unset( $files[$key] );
		}

		// get script files
		if( isset( $luxe['jquery_load'] ) ) {
			if( isset( $luxe['jquery_migrate'] ) && $luxe['jquery_migrate'] !== 'not' && file_exists( $files['jquery'] ) === true ) {
				// jquery
				$contents['jquery'] = $wp_filesystem->get_contents( $files['jquery'] );
				// luxe.async.min.js
				$luxe_async = $this->_js_dir . 'luxe.async.min.js';
				// luxe.min.js
				$luxe_min = $this->_js_dir . 'luxe.min.js';

				if( file_exists( $files['migrate'] ) === true ) {
					// jquery-migrate
					$contents['migrate'] = $wp_filesystem->get_contents( $files['migrate'] );
				}

				if( $luxe['jquery_migrate'] === 'luxeritas' ) {
					if( file_exists( $luxe_async ) === true ) {
						// luxe.async.min.js
						$contents['migrate'] .= $wp_filesystem->get_contents( $luxe_async );
					}
					if( file_exists( $luxe_min ) === true ) {
						// luxe.min.js
						$contents['migrate'] .= $wp_filesystem->get_contents( $luxe_min );
					}

					$del_file = $bind[0];
				}
				else {
					$del_file = $bind[1];
				}

				if( $wp_filesystem->delete( $del_file ) === false ) {
					$this->_filesystem->file_save( $del_file, null );
				}
			}
		}

		if(
			get_theme_mod( 'all_clear', false ) === true ||
			!isset( $luxe['jquery_migrate'] ) ||
			( isset( $luxe['jquery_migrate'] ) && $luxe['jquery_migrate'] === 'not' )
		) {
			foreach( $bind as $val ) {
				if( $wp_filesystem->delete( $val ) === false ) {
					$this->_filesystem->file_save( $val, null );
				}
			}
			return true;
		}

		// javascript compression and save
		$save = '';
		foreach( $contents as $value ) {
			$save .= $value . "\n";
		}
		if( $this->_filesystem->file_save( $jquery_migrate, thk_jsmin( $save ) ) === false ) return false;

		return true;
	}
}

/*---------------------------------------------------------------------------
 * ファイル操作
 *---------------------------------------------------------------------------*/
class thk_filesystem {
	/* save */
	public function file_save( $file=THK_STYLE_TMP_CSS, $txt='' ) {
		global $wp_filesystem;

		add_filter( 'request_filesystem_credentials', '__return_true' );

		$this->init_filesystem();
		if( false === $wp_filesystem->put_contents( $file , $txt, FS_CHMOD_FILE ) ) {
			//echo "error saving file!";
			if( is_admin() === true ) {
				if( function_exists( 'add_settings_error' ) === true ) {
					add_settings_error( 'luxe-custom', '', __( 'Error saving file.', 'luxeritas' ) . '<br />' . $file );
				}
			}
			elseif( defined( 'WP_DEBUG' ) === true && WP_DEBUG == true ) {
				$result = new WP_Error( 'error saving file', __( 'Error saving file.', 'luxeritas' ), $file );
				thk_error_msg( $result );
			}
			return false;
		}
		return;
	}

	/* init */
	public function init_filesystem( $url = null ) {
		global $wp_filesystem;
		require_once( ABSPATH . 'wp-admin/includes/file.php' );

		// direct accsess
		$access_type = get_filesystem_method();

		if( $access_type !== 'direct') {
			add_filter( 'filesystem_method', function( $a ) {
				return 'direct';
			});
			if( defined( 'FS_CHMOD_DIR' ) === false ) {
				//define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
				define( 'FS_CHMOD_DIR', 0777 );
			}
			if( defined( 'FS_CHMOD_FILE' ) === false ) {
				//define( 'FS_CHMOD_FILE', ( 0644 & ~ umask() ) );
				define( 'FS_CHMOD_FILE', 0666 );
			}
		}

		// nonce
		if( $url === null ) {
			$url = wp_nonce_url( 'customize.php?return=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		}
		$creds = request_filesystem_credentials( $url, '', false, false, null );

		// Writable or Check
		if( $creds === false ) {
			return false;
		}
		// WP_Filesystem_Base init
		if( WP_Filesystem( $creds ) === false ) {
			request_filesystem_credentials( $url, '', true, false, null );
			return false;
		}
		return;
	}
}
