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

$luxe_defaults = array();

/*---------------------------------------------------------------------------
 * after_setup_theme
 *---------------------------------------------------------------------------*/
add_action( 'after_setup_theme', function() {
	global $my_custom_image_sizes;

	$referer = wp_get_raw_referer();
	/**
	if( stripos( (string)$referer, 'wp-admin/widgets.php' ) !== false ) {
		if( stripos( $_SERVER['REQUEST_URI'], 'wp-admin/admin-ajax.php' ) !== false ) {
			thk_options_modify();
		}
	}
	*/

	if( stripos( (string)$referer, 'wp-admin/options-permalink.php' ) !== false ) {
		if( stripos( $_SERVER['REQUEST_URI'], 'options-permalink.php' ) !== false ) {
			require( INC . 'rewrite-rules.php' );
			add_action( 'init', 'thk_add_endpoint', 11 );
		}
	}
	elseif( stripos( (string)$referer, 'wp-admin/upgrade.php' ) !== false ) {
		require( INC . 'rewrite-rules.php' );
		add_action( 'init', 'thk_add_endpoint', 11 );
	}

	/* サムネイル追加
	 * メディア画面でサイズ選択できるようにしたバージョン
	 */
	$my_custom_image_sizes = array(
		'thumb75' => array(
			'name'       => __( 'Micro thumbnail', 'luxeritas' ),
			'width'      => 75,
			'height'     => 75,
			'crop'       => true,
			'selectable' => false
		),
		'thumb100' => array(
			'name'       => __( 'Small thumbnail', 'luxeritas' ),
			'width'      => 100,
			'height'     => 100,
			'crop'       => true,
			'selectable' => false
		),
		'thumb320' => array(
			'name'       => __( 'Horizontal thumbnail', 'luxeritas' ),	// 選択肢のラベル名
			'width'      => 320,         	// 最大画像幅
			'height'     => 180,         	// 最大画像高さ
			'crop'       => true,        	// 切り抜きを行うかどうか
			'selectable' => true         	// 選択肢に含めるかどうか
		),
		'thumb530' => array(
			'name'       => __( 'Small size', 'luxeritas' ) . '(' . __( 'Side bar with 336 px', 'luxeritas' ) . ')',
			'width'      => 530,
			'height'     => 530,
			'crop'       => false,
			'selectable' => true
		),
		'thumb565' => array(
			'name'       => __( 'Small size', 'luxeritas' ) . '(' . __( 'Side bar with 300 px', 'luxeritas' ) . ')',
			'width'      => 565,
			'height'     => 565,
			'crop'       => false,
			'selectable' => true
		),
		'thumb710' => array(
			'name'       => __( 'Large size', 'luxeritas' ) . '(' . __( 'Side bar with 336 px', 'luxeritas' ) . ')',
			'width'      => 710,
			'height'     => 710,
			'crop'       => false,
			'selectable' => true
		),
		'thumb725' => array(
			'name'       => __( 'Large size', 'luxeritas' ) . '(' . __( 'Side bar with 300 px', 'luxeritas' ) . ')',
			'width'      => 725,
			'height'     => 725,
			'crop'       => false,
			'selectable' => true
		),
	);

	$w = (int)get_theme_mod( 'thumbnail_width', 150 );
	$h = (int)get_theme_mod( 'thumbnail_height', 150 );
	if( $w >= 0 && $h >= 0 ) {
		if( $w !== 150 || $h !== 150 ) {
			$my_custom_image_sizes += array(
				'thumb' . $w . 'x' . $h => array(
					'name'       => __( 'User thumbnail', 'luxeritas' ),
					'width'      => $w,
					'height'     => $h,
					'crop'       => true,
					'selectable' => true
				),
			);

			$ws = (int)ceil( $w * 0.666 );
			$hs = (int)ceil( $h * 0.666 );

			if( $ws >= 0 && $hs >= 0 ) {
				$my_custom_image_sizes += array(
					'thumb' . $ws . 'x' . $hs => array(
						'name'       => __( 'User thumbnail small', 'luxeritas' ),
						'width'      => $ws,
						'height'     => $hs,
						'crop'       => true,
						'selectable' => false
					),
				);
			}
		}
	}

	foreach( $my_custom_image_sizes as $slug => $size ) {
		add_image_size( $slug, $size['width'], $size['height'], $size['crop'] );
	}
} );

add_action( 'image_size_names_choose', function( $size_names ) {
	global $my_custom_image_sizes;
	$custom_sizes = get_intermediate_image_sizes();
	foreach ( $custom_sizes as $custom_size ) {
		if ( isset( $my_custom_image_sizes[$custom_size]['selectable'] ) && $my_custom_image_sizes[$custom_size]['selectable'] ) {
			$size_names[$custom_size] = $my_custom_image_sizes[$custom_size]['name'];
		}
	}
	return $size_names;
} );

/*---------------------------------------------------------------------------
 * WordPress 管理画面に「Luxeritas」を追加する
 *---------------------------------------------------------------------------*/
add_action( 'admin_menu', function() {

	get_template_part( 'inc/customize' );

	$customize = new luxe_customize();

	$linefeed = get_locale() === 'ja' ? ' ' : '<br />';

	luxe_menu_page(
		'' . __( 'Customizer', 'luxeritas' ),
		'Supreme',
		'manage_options',
		'luxe',
		array( $customize, 'luxe_custom_form' ),
		'dashicons-layout',
		59
	);

  luxe_submenu_page(
		'luxe',
		'' . __( 'Customizer', 'luxeritas' ),
		__( 'Customize', 'luxeritas' ),
		'manage_options',
		'luxe',
		array( $customize, 'luxe_custom_form' )
	);

  /**
	luxe_submenu_page(
		'luxe',
		'Supreme Customize',
		__( 'Customize', 'luxeritas' ) . $linefeed .'(' . __( 'Appearance', 'luxeritas' ) . ')',
		'manage_options',
		'customize.php?return=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ) . '&amp;luxe=custom',
		''
	);
	luxe_submenu_page(
		'luxe',
		'SNS ' . __( 'Counter', 'luxeritas' ),
		'SNS ' . __( 'Counter', 'luxeritas' ),
		'manage_options',
		'luxe_sns',
		array( $customize, 'luxe_custom_form' )
	);
	luxe_submenu_page(
		'luxe',
		__( 'Child Theme Editor', 'luxeritas' ),
		__( 'Child Theme Editor', 'luxeritas' ),
		'manage_options',
		'luxe_edit',
		array( $customize, 'luxe_custom_form' )
	);
	*/

} );

/**
add_action( 'admin_menu', function(){
  add_submenu_page( 'luxe', esc_html__( 'Admin Panel', 'luxe' ), esc_html__( 'System Status', 'luxe' ), 'edit_theme_options', 'cookie', 'agni_admin_menu_welcome_page' );
}, 99 );
**/

/**
 * Admin panel function.
 */
//function agni_admin_menu_welcome_page(){
//  include ( AGNI . '/admin/agni-welcome-page.php' );
//}

/**
 * Load Adobe TypeKit plugin file.
 */
//require AGNI . '/agni-typekit/agni-typekit.php';

/**
 * Upload Custom Fonts plugin file.
 */
//require AGNI . '/agni-custom-fonts/agni-custom-fonts.php';

/*---------------------------------------------------------------------------
 * カスタマイズ内容の変更反映
 *---------------------------------------------------------------------------*/
add_action( 'admin_init', function() {
	global $luxe, $luxe_defaults;
	require_once( INC . 'const.php' );

	if(
		isset( $_GET['page'] ) && isset( $_POST['_wpnonce'] ) && isset( $_POST['option_page'] ) &&
		( $_GET['page'] === 'luxe' || substr( $_GET['page'], 0, 5 ) === 'luxe_' )
	) {

		if( $_POST['option_page'] === 'sns_get' ) return;

		// options.php を経由してないので、ここで nonce のチェック
		if( check_admin_referer( $_POST['option_page'] . '-options', '_wpnonce' ) ) {
			$conf = new defConfig();
			$luxe_defaults = $conf->default_variables();
			$err = false;

			if( $_POST['option_page'] === 'header' ) {
				// SEO
        thk_customize_result_set( 'html_lang', 'text' );
        thk_customize_result_set( 'html_hreflang', 'text' );
        thk_customize_result_set( 'html_gtag', 'text' );
        thk_customize_result_set( 'dns_prefetch_text', 'text' );
				thk_customize_result_set( 'canonical_enable', 'checkbox' );
				thk_customize_result_set( 'next_prev_enable', 'checkbox' );
				thk_customize_result_set( 'rss_feed_enable', 'checkbox' );
				thk_customize_result_set( 'atom_feed_enable', 'checkbox' );
        thk_customize_result_set( 'dns_prefetch_enable', 'checkbox' );
				thk_customize_result_set( 'top_description', 'text' );
        thk_customize_result_set( 'description_enable', 'checkbox' );
				thk_customize_result_set( 'site_name_type', 'radio' );
				thk_customize_result_set( 'organization_type', 'select' );
				thk_customize_result_set( 'organization_logo', 'radio' );
        thk_customize_result_set( 'top_keywords', 'text' );
				thk_customize_result_set( 'meta_keywords', 'radio' );
				thk_customize_result_set( 'published', 'select' );
				thk_customize_result_set( 'nextpage_index', 'checkbox' );
				// OGP
				thk_customize_result_set( 'facebook_ogp_enable', 'checkbox' );
				thk_customize_result_set( 'facebook_admin', 'text' );
				thk_customize_result_set( 'facebook_app_id', 'text' );
				thk_customize_result_set( 'twitter_card_enable', 'checkbox' );
				thk_customize_result_set( 'twitter_card_type', 'select' );
				thk_customize_result_set( 'twitter_id', 'text' );
				thk_customize_result_set( 'og_img', 'text' );
			}
			elseif( $_POST['option_page'] === 'title' ) {
				// Title
        thk_customize_result_set( 'top_seo_title', 'text' );
				thk_customize_result_set( 'title_sep', 'radio' );
				thk_customize_result_set( 'title_top_list', 'radio' );
				thk_customize_result_set( 'title_front_page', 'radio' );
				thk_customize_result_set( 'title_other', 'radio' );
			}
			elseif( $_POST['option_page'] === 'amp' ) {
				// AMP
				thk_customize_result_set( 'amp_enable', 'checkbox' );
				thk_customize_result_set( 'amp_hidden_comments', 'checkbox' );
				if( function_exists( 'get_plugins' ) === false ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$all_plugins = get_plugins();
				foreach( (array)$all_plugins as $key => $val ) {
					//if( is_plugin_active( $key ) === true ) {
						thk_customize_result_set( 'amp_plugin_' . strlen( $key ) . '_' . md5( $key ), 'checkbox' );
					//}
				}
				thk_customize_result_set( 'amp_logo', 'text' );
			}
			elseif( $_POST['option_page'] === 'optimize' ) {
				// HTML
				thk_customize_result_set( 'html_compress', 'select' );
				thk_customize_result_set( 'child_css_compress', 'select' );
				thk_customize_result_set( 'child_js_compress', 'select' );
				thk_customize_result_set( 'child_js_file_1', 'text' );
				thk_customize_result_set( 'child_js_file_2', 'text' );
				thk_customize_result_set( 'child_js_file_3', 'text' );
			}
			elseif( $_POST['option_page'] === 'style' ) {
				// Mode select
				thk_customize_result_set( 'luxe_mode_select', 'radio' );
				// Inline Style
				thk_customize_result_set( 'css_to_style', 'checkbox' );
				thk_customize_result_set( 'css_to_plugin_style', 'checkbox' );
				// Child CSS
				thk_customize_result_set( 'child_css', 'checkbox' );
				// Font Awesome
				thk_customize_result_set( 'awesome_css_type', 'radio' );
				thk_customize_result_set( 'awesome_load', 'select' );
				// Widget CSS
				thk_customize_result_set( 'css_search', 'checkbox' );
				thk_customize_result_set( 'css_archive', 'checkbox' );
				thk_customize_result_set( 'css_calendar', 'checkbox' );
				thk_customize_result_set( 'css_new_post', 'checkbox' );
				thk_customize_result_set( 'css_rcomments', 'checkbox' );
				thk_customize_result_set( 'css_adsense', 'checkbox' );
				thk_customize_result_set( 'css_follow_button', 'checkbox' );
				thk_customize_result_set( 'css_rss_feedly', 'checkbox' );
				thk_customize_result_set( 'css_qr_code', 'checkbox' );
			}
			elseif( $_POST['option_page'] === 'script' ) {
				// jQuery
				thk_customize_result_set( 'jquery_load', 'checkbox' );
				// jQuery defer
				thk_customize_result_set( 'jquery_defer', 'checkbox' );
				// Bootstrap Plugin
				thk_customize_result_set( 'bootstrap_js_load_type', 'select' );
				// Other Javascript
				thk_customize_result_set( 'jquery_migrate', 'radio' );
				thk_customize_result_set( 'child_script', 'checkbox' );
				thk_customize_result_set( 'html5shiv_load_type', 'checkbox' );
				thk_customize_result_set( 'respondjs_load_type', 'checkbox' );
				thk_customize_result_set( 'thk_emoji_disable', 'checkbox' );
				thk_customize_result_set( 'thk_embed_disable', 'checkbox' );
			}
			elseif( $_POST['option_page'] === 'search' ) {
				thk_customize_result_set( 'search_extract', 'radio' );
				thk_customize_result_set( 'search_match_method', 'radio' );
				thk_customize_result_set( 'comment_search', 'checkbox' );
				thk_customize_result_set( 'autocomplete', 'checkbox' );
				thk_customize_result_set( 'search_highlight', 'checkbox' );
				thk_customize_result_set( 'highlight_bold', 'checkbox' );
				thk_customize_result_set( 'highlight_oblique', 'checkbox' );
				thk_customize_result_set( 'highlight_bg', 'checkbox' );
				thk_customize_result_set( 'highlight_bg_color', 'text' );
				thk_customize_result_set( 'search_extract_length', 'text' );
				thk_customize_result_set( 'highlight_radius', 'text' );
			}
			elseif( $_POST['option_page'] === 'captcha' ) {
				thk_customize_result_set( 'captcha_enable', 'radio' );
				thk_customize_result_set( 'recaptcha_site_key', 'text' );
				thk_customize_result_set( 'recaptcha_secret_key', 'text' );
				thk_customize_result_set( 'recaptcha_theme', 'select' );
				thk_customize_result_set( 'recaptcha_size', 'select' );
				thk_customize_result_set( 'recaptcha_type', 'select' );
				thk_customize_result_set( 'secimg_image_width', 'text' );
				thk_customize_result_set( 'secimg_image_height', 'text' );
				thk_customize_result_set( 'secimg_start_length', 'text' );
				thk_customize_result_set( 'secimg_end_length', 'text' );
				thk_customize_result_set( 'secimg_charset', 'select' );
				thk_customize_result_set( 'secimg_font_ratio', 'range' );
				thk_customize_result_set( 'secimg_color', 'select' );
				thk_customize_result_set( 'secimg_bg_color', 'select' );
				thk_customize_result_set( 'secimg_perturbation', 'range' );
				thk_customize_result_set( 'secimg_noise_level', 'range' );
				thk_customize_result_set( 'secimg_noise_color', 'select' );
				thk_customize_result_set( 'secimg_num_lines', 'text' );
				thk_customize_result_set( 'secimg_line_color', 'select' );
			}
			elseif( $_POST['option_page'] === 'copyright' ) {
				thk_customize_result_set( 'copyright_since', 'text' );
				thk_customize_result_set( 'copyright_auth', 'text' );
				thk_customize_result_set( 'copyright_type', 'radio' );
				thk_customize_result_set( 'copyright_text', 'textarea' );
			}
			elseif( $_POST['option_page'] === 'others' ) {
				thk_customize_result_set( 'buffering_enable', 'checkbox' );
				thk_customize_result_set( 'add_role_attribute', 'checkbox' );
				thk_customize_result_set( 'remove_hentry_class', 'checkbox' );
				thk_customize_result_set( 'enable_mb_slug', 'checkbox' );
				thk_customize_result_set( 'user_scalable', 'radio' );
				thk_customize_result_set( 'categories_a_inner', 'checkbox' );
				thk_customize_result_set( 'archives_a_inner', 'checkbox' );
				thk_customize_result_set( 'parent_css_uncompress', 'checkbox' );
			}
			elseif( $_POST['option_page'] === 'restore' || $_POST['option_page'] === 'restore_appearance' ) {
				$files_key = $_POST['option_page'] === 'restore' ? 'luxe-restore' : 'luxe-restore-appearance';

				if( isset( $_FILES[$files_key] ) ) {
					require_once( INC . 'optimize.php' );
					global $wp_filesystem;

					$filesystem = new thk_filesystem();
					if( $filesystem->init_filesystem( site_url() ) === false ) return false;

					$json_file = $_FILES[$files_key]['tmp_name'];
					$file_type = $_FILES[$files_key]['type'];

					// ファイルが zip だったら解凍を試みる（解凍できないなら放置してそのまま json_decode のエラー出力に任せる）
					if( stripos( $file_type, '/zip' ) !== false || stripos( $file_type, '/x-zip' ) !== false || stripos( $file_type, '/octet-stream' ) !== false ) {
						//$temp_dir = sys_get_temp_dir() . DSEP . 'luxe-' . time();
						$temp_dir = get_temp_dir() . DSEP . 'luxe-' . time();

						if( $wp_filesystem->mkdir( $temp_dir ) === true ) {
							unzip_file( $json_file, $temp_dir );
						}
						foreach( (array)glob( $temp_dir . DSEP . '*.json' ) as $val ) {
							if( is_file( $val ) === true ) {
								$json_file = $val;
								break;
							}
						}
					}

					$json = $wp_filesystem->get_contents( $json_file );
					$json = @json_decode( $json );
					$json_error = json_error_code_to_msg( json_last_error() );

					if( $wp_filesystem->is_dir( $temp_dir ) === true ) {
						$wp_filesystem->delete( $temp_dir, true );
					}

					if( $json_error === JSON_ERROR_NONE ) {
						$i = 0;
						remove_theme_mods();

						if( $_POST['option_page'] === 'restore' ) {
							foreach( (array)$json as $key => $val ) {
								if( array_key_exists( $key, $luxe_defaults ) && $luxe_defaults[$key] != $val ) {
									set_theme_mod( $key, $val );
									++$i;
								}
							}
						}
						else {
							require( INC . 'appearance-settings.php' );
							foreach( (array)$json as $key => $val ) {
								if( array_key_exists( $key, $luxe_defaults ) && $luxe_defaults[$key] != $val && isset( Appearance::$design[$key] ) ) {
									set_theme_mod( $key, $val );
									++$i;
								}
							}
						}
						add_settings_error( 'luxe-custom', $_POST['option_page'],  sprintf( __( 'Restored %s settings', 'luxeritas' ), $i ), 'updated' );
					}
					else {
						add_settings_error( 'luxe-custom', $_POST['option_page'], __( 'Restore failed', 'luxeritas' ) . $json_error, 'error' );
					}
				}
				else {
					add_settings_error( 'luxe-custom', $_POST['option_page'], __( 'Not file selected', 'luxeritas' ) . $json_error, 'error' );
				}
				$err = true;
			}
			elseif( $_POST['option_page'] === 'reset' ) {
				thk_customize_result_set( 'all_clear', 'checkbox' );
				thk_customize_result_set( 'sns_count_cache_cleanup', 'checkbox' );
				thk_customize_result_set( 'blogcard_cache_cleanup', 'checkbox' );
				thk_customize_result_set( 'blogcard_cache_expire_cleanup', 'checkbox' );
			}
			elseif( $_POST['option_page'] === 'sns_setting' ) {
				thk_customize_result_set( 'sns_count_cache_enable', 'checkbox' );
				thk_customize_result_set( 'sns_count_cache_force', 'checkbox' );
				thk_customize_result_set( 'sns_count_cache_expire', 'select' );
				thk_customize_result_set( 'sns_count_weekly_cleanup', 'select' );
				thk_customize_result_set( 'sns_count_cache_cleanup', 'checkbox' );
			}
			elseif(
				$_POST['option_page'] === 'edit_style'		||
				$_POST['option_page'] === 'edit_script'		||
				$_POST['option_page'] === 'edit_header'		||
				$_POST['option_page'] === 'edit_footer'		||
				$_POST['option_page'] === 'edit_analytics'	||
				$_POST['option_page'] === 'edit_functions'	||
				$_POST['option_page'] === 'edit_amp'
			) {
				if( TPATH === SPATH ) return;

				require_once( INC . 'optimize.php' );
				global $wp_filesystem;

				$filesystem = new thk_filesystem();
				if( $filesystem->init_filesystem( site_url() ) === false ) return false;

				$save_file = null;
				$save_content = '';

				switch( $_POST['option_page'] ) {
					case 'edit_style':
						$save_file = SPATH . DSEP . 'style.css';
						break;
					case 'edit_script':
						$save_file = SPATH . DSEP . 'luxech.js';
						break;
					case 'edit_header':
						$save_file = SPATH . DSEP . 'add-header.php';
						break;
					case 'edit_footer':
						$save_file = SPATH . DSEP . 'add-footer.php';
						break;
					case 'edit_analytics':
						$save_file = SPATH . DSEP . 'add-analytics.php';
						break;
					case 'edit_functions':
						$save_file = SPATH . DSEP . 'functions.php';
						break;
					case 'edit_amp':
						$save_file = SPATH . DSEP . 'style-amp.css';
						break;
					default:
						$save_file = SPATH . DSEP . 'style.css';
						break;
				}

				$save_content .= isset( $_POST['newcontent'] ) ? $_POST['newcontent'] : '';
				$save_content = str_replace( "\r\n", "\n", $save_content );
				$save_content = stripslashes_deep( thk_convert( $save_content ) );

				$theme = wp_get_theme( get_stylesheet() );
				if( $_POST['option_page'] === 'edit_style' && $theme->errors() ) {
					add_settings_error( 'luxe-custom', $_POST['option_page'], __( 'This theme is broken.', 'luxeritas' ) . ' ' . $theme->errors()->get_error_message(), 'error' );
					$err = true;
				}
				if( $filesystem->file_save( $save_file, $save_content ) === false ) {
					add_settings_error( 'luxe-custom', $_POST['option_page'], __( 'Error saving file.', 'luxeritas' ), 'error' );
				}
			}

			if( $_POST['option_page'] === 'backup_child' ) {
				if( TPATH === SPATH ) {
					add_settings_error( 'luxe-custom', $_POST['option_page'],
						__( 'The theme selected is not the child theme, but the parent theme', 'luxeritas' ) . '<br />' .
						__( 'This feature can only be used when the child theme is selected.', 'luxeritas' ),
					'error' );
				}
				$err = true;
			}

			if( $err === false ) {
				add_settings_error( 'luxe-custom', $_POST['option_page'], __( 'Changes are properly reflected', 'luxeritas' ), 'updated' );
			}

			//thk_regenerate_files( true );
			//add_filter( 'shutdown', 'thk_cleanup', 99 );
			thk_regenerate_files();
			thk_cleanup();
		}
	}

	if( isset( $_GET['page'] ) && ( $_GET['page'] === 'luxe' || substr( $_GET['page'], 0, 5 ) === 'luxe_' ) ) {
		if( isset( $_POST['amp_enable'] ) || get_theme_mod( 'amp_enable', false ) === true ) {
			$amp_css_size = 0;
			if( file_exists( TPATH . DSEP . 'style-amp.min.css' ) === true ) {
				$amp_css_size += (int)filesize( TPATH . DSEP . 'style-amp.min.css' );
			}
			if( TPATH !== SPATH && file_exists( SPATH . DSEP . 'style-amp.min.css' ) === true ) {
				$amp_css_size += (int)filesize( SPATH . DSEP . 'style-amp.min.css' );
			}
			if( $amp_css_size > 50000 ) {
				add_settings_error( 'luxe-custom', 'amp-css', sprintf( __( 'Stylesheet for AMP is too long. we saw %s bytes whereas the limit is 50000 bytes.', 'luxeritas' ), $amp_css_size ), 'error' );
			}
		}
		if( isset( $_POST['amp_logo'] ) || get_theme_mod( 'amp_logo', null ) !== null ) {
			$amp_logo = '';
			if( isset( $_POST['amp_logo'] ) ) {
				$amp_logo = $_POST['amp_logo'];
			}
			else {
				$amp_logo = get_theme_mod( 'amp_logo', null );
			}
			$logo_info = thk_get_image_size( $amp_logo );
			if( ( isset( $logo_info[0] ) && (int)$logo_info[0] > 600 ) || ( isset( $logo_info[1] ) && (int)$logo_info[1] > 60 ) ) {
				add_settings_error( 'luxe-custom', 'amp-logo', __( 'The AMP logo is too large.', 'luxeritas' ) . ' (' . __( '* the image must be within 600px width, height 60px.', 'luxeritas' ) . ')', 'error' );
			}
		}
	}

	/*
	 * file permission and owner check
	 */
	add_action( 'admin_notices', function() {
		global $luxe;

		$filesystem = null;

		$files = array(
			TPATH . DSEP . 'style.min.css',
			TPATH . DSEP . 'style.async.min.css',
			TPATH . DSEP . 'js' . DSEP . 'luxe.min.js',
			TPATH . DSEP . 'js' . DSEP . 'luxe.async.min.js'
		);

		// AMP
		if( isset( $luxe['amp_enable'] ) ) {
			$files[] = TPATH . DSEP . 'style-amp.min.css';
		}

		// 子テーマ
		if( TDEL !== SDEL ) {
			if( isset( $luxe['child_css'] ) ) {
				// 子テーマ CSS
				if( $luxe['child_css_compress'] !== 'none' ) $files[] = SPATH . DSEP . 'style.min.css';
			}
			if( !isset( $luxe['child_script'] ) ) {
				// 子テーマ Javascript
				if( $luxe['child_js_compress'] !== 'none' ) $files[] = SPATH . DSEP . 'luxech.min.js';
			}
			if( isset( $luxe['amp_enable'] ) ) {
				// 子テーマ AMP
				$files[] = SPATH . DSEP . 'style-amp.min.css';
			}
		}

		// jQuery
		if( isset( $luxe['jquery_load'] ) && isset( $luxe['jquery_migrate'] ) && $luxe['jquery_migrate'] !== 'not' ) {
			if( $luxe['jquery_migrate'] === 'luxeritas' ) {
				$files[] = TPATH . DSEP . 'js' . DSEP . 'jquery.kjluxe.min.js';
			}
			elseif( $luxe['jquery_migrate'] === 'migrate' ) {
				$files[] = TPATH . DSEP . 'js' . DSEP . 'jquery.bind.min.js';
			}
		}

		foreach( $files as $val ) {
			if( file_exists( $val ) === false ) {
				if( class_exists( 'thk_filesystem' ) === false ) require( INC . 'optimize.php' );

				global $wp_filesystem;
				if( $filesystem === null ) {
					$filesystem = new thk_filesystem();
					$filesystem->init_filesystem();
				}

				$wp_filesystem->touch( $val );
				if( file_exists( $val ) === false || ( file_exists( $val ) === true && wp_is_writable( $val ) === false ) ) {
					printf(
						'<div class="error"><p>%s</p></div>',
						__( 'You do not have permission to create and save files.', 'luxeritas' ) . '<br />' .
						__( 'Please check the owner and permissions of the following file or directory.', 'luxeritas' ) . '<br />' . $val
					);
				}
				if( file_exists( $val ) === true ) $wp_filesystem->delete( $val );
			}
			elseif( file_exists( $val ) === true && wp_is_writable( $val ) === false ) {
				printf(
					'<div class="error"><p>%s</p></div>',
					__( 'You do not have permission to create and save files.', 'luxeritas' ) . '<br />' .
					__( 'Please check the owner and permissions of the following file or directory.', 'luxeritas' ) . '<br />' . $val
				);
			}
		}
	});
});

add_action( 'init', function() {
	if( isset( $_GET['page'] ) && $_GET['page'] === 'luxe' && isset( $_POST['_wpnonce'] ) && isset( $_POST['option_page'] ) ) {
		if( check_admin_referer( $_POST['option_page'] . '-options', '_wpnonce' ) ) {
			// AMP のリライトルール追加
			if( $_POST['option_page'] === 'header' ) {
				require( INC . 'rewrite-rules.php' );
				thk_add_endpoint();
			}
			// バックアップ時のヘッダー出力
			if( $_POST['option_page'] === 'backup' ) {
				$mods = get_theme_mods();
				foreach( (array)$mods as $key => $val ) {
					if(
						is_array( $val )	||
						is_numeric( $key )	||
						$key === 'custom_css_post_id'
					) unset( $mods[$key] );
				}
				$json = json_encode( $mods );
				if( $json === false || $json === 'false' ) $json = '';
				$file = 'supreme-customize.json';
				@ob_start();
				@header( 'Content-Description: File Transfer' );
				@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
				@header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
				echo $json;
				@ob_end_flush();
				//wp_send_json( $mods );
				exit;
			}
			// 外観デザインバックアップ時のヘッダー出力
			if( $_POST['option_page'] === 'backup_appearance' ) {
				require( INC . 'appearance-settings.php' );
				$mods = get_theme_mods();
				foreach( (array)$mods as $key => $val ) {
					if(
						!isset( Appearance::$design[$key] )	||
						is_array( $val )			||
						is_numeric( $key )			||
						$key === 'custom_css_post_id'
					) unset( $mods[$key] );
				}
				$json = json_encode( $mods );
				if( $json === false || $json === 'false' ) $json = '';
				$file = 'supreme-appearance.json';
				@ob_start();
				@header( 'Content-Description: File Transfer' );
				@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
				@header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
				echo $json;
				@ob_end_flush();
				//wp_send_json( $mods );
				exit;
			}
			// 子テーマバックアップ
			if( $_POST['option_page'] === 'backup_child' ) {
				if( TPATH !== SPATH && class_exists('ZipArchive') === true ) {
					thk_zip_file_download( SPATH, basename( SPATH ) . '.zip' );
				}
			}
		}
	}

	if( isset( $_GET['page'] ) && $_GET['page'] === 'luxe_sns' && isset( $_POST['_wpnonce'] ) && isset( $_POST['option_page'] ) ) {
		if( check_admin_referer( $_POST['option_page'] . '-options', '_wpnonce' ) ) {
			// SNS カウントキャッシュ CSV ダウンロード
			if( $_POST['option_page'] === 'sns_csv' ) {
				require_once( INC . 'optimize.php' );
				global $wp_filesystem;

				$filesystem = new thk_filesystem();
				if( $filesystem->init_filesystem( site_url() ) === false ) return false;

				$lst = array();
				$feed = 0;

				$wp_upload_dir = wp_upload_dir();
				$cache_dir = $wp_upload_dir['basedir'] . '/luxe-sns/';

				foreach( glob( $cache_dir. '*' ) as $val ) {
					$content = $wp_filesystem->get_contents( $val );
					$content = str_replace( "\n", ',', $content );
					$content = str_replace( home_url('/'), '/', $content );
					if( stripos( $content, ',' . 'r:' ) !== false ) {
						$tmp = explode( 'r:', $content );
						$feed = isset( $tmp[1] ) ? trim( $tmp[1], ',' ) : 0;
					}
					else {
						$content = str_replace( array( ',f:', ',g:', ',h:', ',l:', ',p:' ), ',', $content );
						$lst[] = $content;
					}
				}
				sort( $lst );

				$file = 'luxe-sns-count.csv';
				@ob_start();
				@header( 'Content-Description: File Transfer' );
				@header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ) );
				@header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
				echo "URL,Facebook,Google+,Hatena,LinkedIn,Poket,Feedly\n";
				foreach( $lst as $val ) {
					echo $val . $feed . "\n";
				}
				@ob_end_flush();
				exit;
			}

			// SNS カウントキャッシュ一括取得
			if( $_POST['option_page'] === 'sns_get' ) {
				if( isset( $_POST['sns_get'] ) ) {
					add_action( 'shutdown', function() {
						echo '<script>jQuery("#sns_get_stop").css( "display", "inline-block" );</script>';
					}, 90 );
					require( INC . 'sns-cache-all-get.php' );
					$all_get = new sns_cache_all_get();
					$all_get->sns_cache_list();
				}
			}
		}
	}
}, 11 );

/*---------------------------------------------------------------------------
 * テーマ無効化時に AMP のリライトルール削除
 *---------------------------------------------------------------------------*/
add_action( 'switch_theme', function() {
	global $wp_rewrite;

	foreach( $wp_rewrite->endpoints as $key => $val ) {
		if ( $val === 'amp' ) {
			unset( $wp_rewrite->endpoints[$key] );
		}
	}
	$wp_rewrite->flush_rules();
});

/*---------------------------------------------------------------------------
 * カスタマイズ内容の変更を DB に書き込む (サニタイズ込み)
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_customize_result_set' ) === false ):
function thk_customize_result_set( $key, $type ) {
	global $luxe_defaults;

	if( $type === 'checkbox' ) {
		if( isset( $_POST[$key] ) && $luxe_defaults[$key] != true ) {
			set_theme_mod( $key, true );
		}
		elseif( !isset( $_POST[$key] ) && $luxe_defaults[$key] != false ) {
			set_theme_mod( $key, false );
		}
		else {
			remove_theme_mod( $key );
		}
	}
	elseif( $type === 'text' || $type === 'number' || $type === 'range' || $type === 'textarea' || $type === 'radio' || $type === 'select' ) {
		$post_key = isset( $_POST[$key] ) ? $_POST[$key] : '';

		if( $type === 'range' || $type === 'number' ) {
			if( is_numeric( $post_key ) === true ) {
				$post_key = round( $post_key );
			}
			else {
				$post_key = '';
			}
		}

		if( $post_key != $luxe_defaults[$key] ) {
			if( $type === 'textarea' ) {
				set_theme_mod( $key, str_replace( "\n", '<br />', esc_attr( $post_key ) ) );
			}
			else {
				set_theme_mod( $key, esc_attr( $post_key ) );
			}
		}
		else {
			remove_theme_mod( $key );
		}
	}
}
endif;

/*---------------------------------------------------------------------------
 * value の値 (もしくは checked や selected) をチェックして HTML に挿入
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_value_check') === false ):
function thk_value_check( $key, $type, $default = null ) {
	global $luxe;
	$ret = '';

	if( $type === 'checkbox' ) {
		if( isset( $_POST[$key] ) ) {
			$ret = ' checked="checked"';
		}
		elseif( !isset( $_POST[$key] ) && isset( $_POST['action'] ) ) {
			$ret = '';
		}
		elseif( isset( $luxe[$key] ) ) {
			$ret = ' checked="checked"';
		}
	}
	elseif( $type === 'radio' || $type === 'select' ) {
		if( isset( $_POST['action'] ) ) {
			if( isset( $_POST[$key] ) && $_POST[$key] == $default ) {
				$ret = $type === 'radio' ? ' checked="checked"' : ' selected="selected"';
			}
		}
		else {
			if( isset( $luxe[$key] ) && $luxe[$key] == $default ) {
				$ret = $type === 'radio' ? ' checked="checked"' : ' selected="selected"';
			}
		}
	}
	elseif( $type === 'text' || $type === 'number' || $type === 'range' ) {
		if( isset( $_POST[$key] ) ) {
			$ret = $_POST[$key];
		}
		else {
			$ret = isset( $luxe[$key] ) ? $luxe[$key] : '';
		}
		$ret = esc_attr( $ret );
	}
	elseif( $type === 'textarea' ) {
		if( isset( $_POST[$key] ) ) {
			$ret = $_POST[$key];
		}
		else {
			$ret = isset( $luxe[$key] ) ? str_replace( '<br />', "\n", $luxe[$key] ) : '';
		}
		$ret = esc_attr( $ret );
	}
  echo $ret;
}
endif;

/*---------------------------------------------------------------------------
 * ZIP ファイルのダウンロード
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_zip_file_download' ) === false ):
function thk_zip_file_download( $path, $fname ) {
	$zip_file = '';
	//$temp = tempnam( sys_get_temp_dir(), 'luxe' );
	$temp = tempnam( get_temp_dir(), 'luxe' );

	if( file_exists( $temp ) === false ) {
		echo	'<div class="error"><p>',
			__( 'Failed to create temporary file with PHP. Check the setting of &quot;upload_tmp_dir&quot; in PHP.', 'luxeritas' ) . '<br />' . $temp,
			'</p></div>';
	}
	else {
		require_once( INC . 'optimize.php' );
		require( INC . 'thk-zip.php' );

		global $wp_filesystem;

		$filesystem = new thk_filesystem();
		if( $filesystem->init_filesystem( site_url() ) === false ) return false;

		$zip = new thk_zip_compress();
		$success = $zip->all_zip( $path, $temp );

		if( $success === true ) {
			$zip_file = $wp_filesystem->get_contents( $temp );
			$wp_filesystem->delete( $temp );

			// ファイル名に使えない文字があったら置換
			$fname = preg_replace( '/\\s/u', '_', $fname );
			$fname = str_replace( array( '\\','/',':','*','?','"','<','>','|' ), '_', $fname );

			@ob_start();
			@header( 'Content-Description: File Transfer' );
			@header( 'Content-Type: application/zip; charset=' . get_option( 'blog_charset' ) );
			@header( "Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode( $fname ) );
			echo $zip_file;
			@ob_end_flush();
			exit;
		}
		else {
			echo '<div class="error"><p>', $success, '</p></div>';
		}
	}
}
endif;

/*---------------------------------------------------------------------------
 * 管理画面で Widget などに変更が加わった時の処理
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_options_modify' ) === false ):
function thk_options_modify() {
	global $luxe;

	require( INC . 'custom-css.php' );
	require( INC . 'compress.php' );
	require( INC . 'carray.php' );

	add_filter( 'updated_option', 'thk_compress', 75 );
	add_filter( 'updated_option', 'thk_parent_css_bind', 80 );
	add_filter( 'updated_option', 'thk_child_js_comp', 80 );
	add_filter( 'updated_option', 'thk_create_inline_style', 85 );
}
endif;

/*---------------------------------------------------------------------------
 * admin_head
 *---------------------------------------------------------------------------*/
add_action( 'admin_head', function() {
	global $luxe;

	// ブログカードの挿入ボタン
	if( isset( $luxe['blogcard_enable'] ) ) {
		if( stripos( $_SERVER['REQUEST_URI'], 'wp-admin/post' ) !== false ) {
			require( INC . 'blogcard-admin-func.php' );
		}
	}

	// Web フォント CSS の存在チェック
	if( isset( $_GET['page'] ) && ( $_GET['page'] === 'luxe' || substr( $_GET['page'], 0, 5 ) === 'luxe_' ) ) {
		global $luxe;

		if( isset( $luxe['all_clear'] ) ) return;

		require_once( INC . 'web-font.php' );
		$web_font_dir = TPATH . DSEP . 'webfonts' . DSEP;

		if( isset( Web_Font::$webfont[$luxe['font_alphabet']] ) ) {
			if( file_exists( $web_font_dir . $luxe['font_alphabet'] ) === false ) {
				if( function_exists( 'add_settings_error' ) === true ) {
					add_settings_error(
						'luxe-custom', '',
						sprintf(
							__( 'Web font CSS Not Found.<br />%s It seems that CSS download of the font failed. Please reset web font.', 'luxeritas' ),
							$luxe['font_alphabet']
						)
					);
				}
			}
		}
		if( isset( Web_Font::$webfont[$luxe['font_japanese']] ) ) {
			if( file_exists( $web_font_dir . $luxe['font_japanese'] ) === false ) {
				if( function_exists( 'add_settings_error' ) === true ) {
					add_settings_error(
						'luxe-custom', '',
						sprintf(
							__( 'Web font CSS Not Found.<br />%s It seems that CSS download of the font failed. Please reset web font.', 'luxeritas' ),
							$luxe['font_japanese']
						)
					);
				}
			}
		}
	}
});

/*---------------------------------------------------------------------------
 * 管理画面用の CSS 読み込み
 *---------------------------------------------------------------------------*/
/* jquery-ui script (ブログカード用) */
add_action( 'admin_print_scripts', function() {
	wp_enqueue_script( 'thk-jquery-ui-script', get_template_directory_uri() . '/js/jquery-ui.min.js', array( 'jquery' ), false );
});

/* jquery-ui CSS (ブログカード用) */
add_action( 'admin_print_styles', function() {
	wp_register_style( 'thk-jquery-ui-style', get_template_directory_uri() . '/css/jquery-ui.min.css', false, false );
        wp_enqueue_style( 'thk-jquery-ui-style' );
});

/* メニューのアイコンを青くするだけのスタイル */
add_action( 'admin_print_styles', function() {
	wp_register_style( 'luxe-admin', TURI . '/css/admin-menu.css', false, false );
        wp_enqueue_style( 'luxe-admin' );
}, 99);

/* luxeritas のカスタマイズ画面を開いた時だけ読み込み */
if( isset( $_GET['page'] ) && ( strpos( $_GET['page'], 'luxe' ) !== false || strpos( $_GET['page'], 'luxe_edit' ) !== false ) ) {
	add_action( 'admin_print_styles', function() {
        	wp_enqueue_style( 'luxe-admin-customize', TURI . '/css/admin-customize-menu.css', array( 'luxe-admin' ), false, false );
	});
}

/* SNS カウンターのページを開いたときだけ読み込み */
if( isset( $_GET['page'] ) && $_GET['page'] === 'luxe_sns' ) {
	add_action( 'admin_print_styles', function() {
		wp_enqueue_style( 'luxe-admin-sns-view', TURI . '/css/admin-sns-view.css', array( 'luxe-admin' ), false, false );
	});
}

/* Code Mirror */
if( isset( $_GET['page'] ) && $_GET['page'] === 'luxe_edit' ) {
	add_action( 'admin_print_styles', function() {
		wp_enqueue_script( 'luxe-codemirror', TURI . '/js/codemirror/lib/codemirror.min.js', array(), false, false );
		wp_enqueue_script( 'luxe-codemirror-ui', TURI . '/js/codemirror/lib/codemirror-ui.js', array( 'luxe-codemirror' ), false, false );
		wp_enqueue_script( 'luxe-codemirror-searchcursor', TURI . '/js/codemirror/adon/search/searchcursor.js', array(), false, false );

		$code = array( 'css', 'xml', 'php', 'clike', 'htmlmixed', 'javascript' );

		foreach( $code as $val ) {
			wp_enqueue_script( 'luxe-codem-' . $val, TURI . '/js/codemirror/mode/' . $val . '/' . $val . '.js', array( 'luxe-codemirror' ), false, false );
		}

		wp_enqueue_style( 'luxe-codemirror', TURI . '/css/codemirror.css', array( 'luxe-admin' ), false, false );
		wp_enqueue_style( 'luxe-codemirror-ui', TURI . '/css/codemirror-ui.css', array( 'luxe-admin' ), false, false );
		wp_enqueue_style( 'luxe-codemirror-luxe', TURI . '/css/codemirror-luxe.css', array( 'luxe-admin' ), false, false );
	});
}

/*---------------------------------------------------------------------------
 * JSON のエラーメッセージ ( json_last_error_msg だと日本語返してくれんから )
 *---------------------------------------------------------------------------*/
if( function_exists( 'json_error_code_to_msg' ) === false ):
function json_error_code_to_msg( $code ) {
	switch( $code ) {
		case JSON_ERROR_DEPTH:
			return ' : ' . __( 'Maximum stack depth exceeded.', 'luxeritas' );
			break;
		case JSON_ERROR_STATE_MISMATCH:
			return ' : ' . __( 'Underflow or the modes mismatch.', 'luxeritas' );
			break;
		case JSON_ERROR_CTRL_CHAR:
			return ' : ' . __( 'Unexpected control character found.', 'luxeritas' );
			break;
		case JSON_ERROR_SYNTAX:
			return ' : ' . __( 'Syntax error, malformed JSON.', 'luxeritas' );
			break;
		case JSON_ERROR_UTF8:
			return ' : ' . __( 'Malformed UTF-8 characters, possibly incorrectly encoded.', 'luxeritas' );
			break;
	}
	if( version_compare( PHP_VERSION, '5.5.0', '>=' ) === true ) {
		switch( $code ) {
			case JSON_ERROR_RECURSION:
				return ' : ' . __( 'One or more recursive references in the value to be encoded.', 'luxeritas' );
				break;
			case JSON_ERROR_INF_OR_NAN:
				return ' : ' . __( 'One or more NAN or INF values in the value to be encoded.', 'luxeritas' );
				break;
			case JSON_ERROR_UNSUPPORTED_TYPE:
				return ' : ' . __( 'A value of a type that cannot be encoded was given.', 'luxeritas' );
				break;
		}
	}
	return JSON_ERROR_NONE;
}
endif;

/*---------------------------------------------------------------------------
 * add menu page -> luxe menu page
 *---------------------------------------------------------------------------*/
if( function_exists( 'luxe_menu_page' ) === false ):
function luxe_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null ) {
	$func = 'add_' . 'menu_' . 'page';
	$hookname = $func( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	return $hookname;
}
endif;

/*---------------------------------------------------------------------------
 * add submenu page -> luxe submenu page
 *---------------------------------------------------------------------------*/
if( function_exists( 'luxe_submenu_page' ) === false ):
function luxe_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
	$func = 'add_' . 'submenu_' . 'page';
	$hookname = $func( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	return $hookname;
}
endif;

/*---------------------------------------------------------------------------
 * dummy function
 *---------------------------------------------------------------------------*/
if( function_exists('d_init') === false ):
function d_init() {
	//add_theme_support( 'post-formats' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'custom-background' );
	get_post_format();
}
endif;

if( function_exists( 'd_pagination' ) === false ):
function d_pagination() {
	posts_nav_link();
	next_comments_link();
	previous_comments_link();
	paginate_comments_links();
}
endif;
