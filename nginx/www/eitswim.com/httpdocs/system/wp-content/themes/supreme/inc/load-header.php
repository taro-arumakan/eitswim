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
 * wp_head に追加するヘッダー (CSS や Javascrpt の追加など)
 *---------------------------------------------------------------------------*/
add_action( 'wp_head', function() {
	global $luxe;
	require_once( INC . 'web-font.php' );

	$_is_singular = is_singular();

	if( $_is_singular === true && isset( $luxe['amp_enable'] ) && !isset( $luxe['amp'] ) ) {
		$amplink = thk_get_amp_permalink( get_queried_object_id() );
?>
<link rel="amphtml" href="<?php echo esc_url( $amplink ); ?>">
<?php
	}

	if( isset( $luxe['canonical_enable'] ) ) {
		if( $_is_singular === true ) {
			rel_canonical();
		}
		else {
			thk_rel_canonical();
		}
	}

	wp_shortlink_wp_head();

	if( isset( $luxe['next_prev_enable'] ) ) {
		thk_rel_next_prev();
	}

  thk_gtag_js();

  ?>
<?php if(false):?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php endif;?>
<?php
	if( isset( $luxe['author_visible'] ) && $_is_singular === true ) {
		if( $luxe['author_page_type'] === 'auth' ) {
			$auth = get_users();
?>
<?php if(false):?>
<link rel="author" href="<?php echo get_author_posts_url( $auth[0]->ID ); ?>" />
<?php endif;?>
<?php
		}
		else {
?>
<?php if(false):?>
<link rel="author" href="<?php echo isset( $luxe['thk_author_url'] ) ? $luxe['thk_author_url'] : THK_HOME_URL; ?>" />
<?php endif;?>
<?php
		}
	}

	// RSS Feed
	if( isset( $luxe['rss_feed_enable'] ) ) {
?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<?php
	}
	// Atom Feed
	if( isset( $luxe['atom_feed_enable'] ) ) {
?>
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<?php
	}

	if( !isset( $luxe['amp'] ) ) {
		// Preload Font files
		thk_preload_web_font( $luxe['font_alphabet'] );
		thk_preload_web_font( $luxe['font_japanese'] );

		if( $luxe['awesome_load'] !== 'none' ) {
			$fonts_path = ( TDEL !== SDEL && $luxe['child_css_compress'] === 'bind' ) ? SDEL : TDEL;
?>
<?php if(false):?>
<link rel="preload" as="font" type="font/woff2" href="<?php echo $fonts_path; ?>/fonts/fontawesome-webfont.woff2" crossorigin />
<link rel="preload" as="font" type="font/woff" href="<?php echo $fonts_path; ?>/fonts/icomoon/fonts/icomoon.woff" crossorigin />
<?php endif;?>
<?php
		}
	}

if(false):
	// Site Icon
	if( has_site_icon() === false ) {
		// favicon.ico
		if( file_exists( SPATH . DSEP . 'images' . DSEP . 'favicon.ico' ) ) {
?>
<link rel="icon" href="<?php echo SURI; ?>/images/favicon.ico" />
<?php
		}
		else {
?>
<link rel="icon" href="<?php echo TURI; ?>/images/favicon.ico" />
<?php
		}

		// Apple Touch icon
		if( file_exists( SPATH . DSEP . 'images' . DSEP . 'apple-touch-icon-precomposed.png' ) ) {
?>
<link rel="apple-touch-icon-precomposed" href="<?php echo SURI; ?>/images/apple-touch-icon-precomposed.png" />
<?php
		}
		else {
?>
<link rel="apple-touch-icon-precomposed" href="<?php echo TURI; ?>/images/apple-touch-icon-precomposed.png" />
<?php
		}
	}
endif;

	// Amp 用 ＆ カスタマイズプレビュー用の Web font
	if( isset( $luxe['amp'] ) || is_customize_preview() === true ) {
		if( file_exists( TPATH . DSEP . 'webfonts' . DSEP . $luxe['font_alphabet'] ) ) {
?>
<link rel="stylesheet" href="<?php echo Web_Font::$alphabet[$luxe['font_alphabet']][1]; ?>" />
<?php
		}
		if( file_exists( TPATH . DSEP . 'webfonts' . DSEP . $luxe['font_japanese'] ) ) {
?>
<link rel="stylesheet" href="<?php echo Web_Font::$japanese[$luxe['font_japanese']][1]; ?>" />
<?php
		}
	}

	if( is_customize_preview() === true ) {
		/* 子テーマの CSS (プレビュー) */
		if( isset( $luxe['child_css'] ) && TDEL !== SDEL ) {
			wp_enqueue_style( 'luxech', SDEL . '/style.css?v=' . $_SERVER['REQUEST_TIME'], false, array(), 'all' );
		}
	}
	else {
		/* 子テーマの CSS (実体) */
		if( isset( $luxe['child_css'] ) && TDEL !== SDEL && !isset( $luxe['amp'] ) ) {
			// 依存関係
			$deps = false;
			if( isset( $luxe['plugin_css_compress'] ) && $luxe['plugin_css_compress'] !== 'none' ) {
				$deps = array( 'plugin-styles' );
			}

			// 子テーマ圧縮してる場合
			if( $luxe['child_css_compress'] !== 'none' ) {
				if(
					file_exists( SPATH . DSEP . 'style.min.css' ) === true && filesize( SPATH . DSEP . 'style.min.css' ) !== 0 &&
					file_exists( TPATH . DSEP . 'style.min.css' ) === true && filesize( TPATH . DSEP . 'style.min.css' ) !== 0
				) {
					wp_enqueue_style( 'luxech', SDEL . '/style.min.css' . '?v=' . $_SERVER['REQUEST_TIME'], $deps, array(), 'all' );
					if( isset( $luxe['css_to_style'] ) ) {
						wp_add_inline_style( 'luxech', thk_direct_style( SPATH . DSEP . 'style.replace.min.css' ) );
					}
				}
				else {
					if( $luxe['child_css_compress'] === 'bind' ) {
						thk_load_customize_preview();
					}
					wp_enqueue_style( 'luxech', SDEL . '/style.css?v=' . $_SERVER['REQUEST_TIME'], $deps, array(), 'all' );
				}
			}
			// 子テーマ圧縮してない
			else {
				if( file_exists( SPATH . DSEP . 'style.css' ) === true ) {
					wp_enqueue_style( 'luxech', SDEL . '/style.css?v=' . $_SERVER['REQUEST_TIME'], $deps, array(), 'all' );
					if( isset( $luxe['css_to_style'] ) ) {
						wp_add_inline_style( 'luxech', thk_direct_style( SPATH . DSEP . 'style.replace.min.css' ) );
					}
				}
			}
		}
	}

	/* テンプレートごとに違うカラム数にしてる場合の 3カラム CSS
	 * (親子 CSS 非結合時は子テーマより先に読み込ませる -> load_styles.php で処理 )
	 */
	if( $luxe['child_css_compress'] === 'bind' && !isset( $luxe['amp'] ) ) {
		if( $luxe['column_default'] === false ) {
			if( $luxe['column_style'] === '1column' ) {
				wp_enqueue_style( 'luxe1', TDEL . '/style.1col.min.css?v=' . $_SERVER['REQUEST_TIME'], false, array(), 'all' );
				wp_add_inline_style( 'luxe1', thk_direct_style( TPATH . DSEP . 'style.1col.min.css' ) );
			}
			if( $luxe['column_style'] === '2column' ) {
				wp_enqueue_style( 'luxe2', TDEL . '/style.2col.min.css?v=' . $_SERVER['REQUEST_TIME'], false, array(), 'all' );
				wp_add_inline_style( 'luxe2', thk_direct_style( TPATH . DSEP . 'style.2col.min.css' ) );
			}
			if( $luxe['column_style'] === '3column' ) {
				wp_enqueue_style( 'luxe3', TDEL . '/style.3col.min.css?v=' . $_SERVER['REQUEST_TIME'], false, array(), 'all' );
				wp_add_inline_style( 'luxe3', thk_direct_style( TPATH . DSEP . 'style.3col.min.css' ) );
			}
		}
	}

	// サイトマップ用インラインスタイル
	if( is_page_template( 'pages/sitemap.php' ) === true ) {
		if( isset( $luxe['child_css'] ) && TDEL !== SDEL ) {
			wp_add_inline_style( 'luxech', thk_sitemap_inline_style() );
		}
		else {
			wp_add_inline_style( 'luxe', thk_sitemap_inline_style() );
		}
	}

	// 検索結果のハイライト用インラインスタイル
	if( is_search() === true && isset( $luxe['search_highlight'] ) ) {
		if( isset( $luxe['child_css'] ) && TDEL !== SDEL ) {
			wp_add_inline_style( 'luxech', thk_search_highlight_inline_style() );
		}
		else {
			wp_add_inline_style( 'luxe', thk_search_highlight_inline_style() );
		}
	}

	// WordPress の管理バーが見えてる場合 600px 以下でも固定表示させる
	if( is_admin_bar_showing() === true ) {
		echo '<style>#wpadminbar{position:fixed!important}</style>';
	}

	//  条件によっては、hentry を削除
	if( $_is_singular === true ) {
		/* 以下の条件の時に hentry を削除する
		   ・ カスタマイズで hentry 削除にチェックがついてる
		   ・ 投稿日時・更新日時の両方が非表示
		   ・ 投稿者名が非表示
		   ・ $post->post_author が空っぽ ( 通常はありえないけどプラグインではあり得る )
		   ・ get_the_modified_date が空っぽ ( 通常はありえないけどプラグインではあり得る )
		 */
		global $post;
		$pdat = get_the_date();
		$mdat = get_the_modified_date();
		$auth = get_userdata( $post->post_author );

		if(
			isset( $luxe['remove_hentry_class'] ) || !isset( $luxe['author_visible'] ) || empty( $mdat ) || empty( $auth ) ||
			(	!isset( $luxe['post_date_visible'] ) && !isset( $luxe['mod_date_visible'] ) &&
				!isset( $luxe['post_date_u_visible'] ) && !isset( $luxe['mod_date_u_visible'] )
			)

		) {
			add_filter( 'post_class', 'thk_remove_hentry' );
		}
	}

	// Amp 用のスクリプトとスタイルを挿入
	if( isset( $luxe['amp'] ) ) {
		//global $wp_scripts, $wp_styles;
		//foreach( $wp_scripts->registered as $val ) wp_dequeue_script( $val->handle );
		//foreach( $wp_styles->registered as $val ) wp_dequeue_style( $val->handle );

		// Amp 用のスタイルとスクリプト挿入
		$bootstrap  = 'maxcdn' . '.bootstrapcdn' . '.com';
		$ampproject = 'cdn' . '.ampproject' . '.org';
?>
<link rel="stylesheet" href="https://<?php echo $bootstrap; ?>/font-awesome/4.7.0/css/font-awesome.min.css" />
<script async src="https://<?php echo $ampproject; ?>/v0.js"></script>
<?php
$amp_extensions = thk_amp_extensions();

foreach( $amp_extensions as $key => $val ) {
	if( isset( $luxe[$key] ) ) {
?>
<script async custom-element="<?php echo $key; ?>" src="https://<?php echo $ampproject, $val; ?>"></script>
<?php
	}
}
unset( $amp_extensions );
?>
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
<noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
<script type="application/ld+json">
<?php
$logo = '';
if( isset( $luxe['amp_logo'] ) ) {
	$logo = $luxe['amp_logo'];
}
else {
	$logo = TURI !== SURI ? SURI : TURI;
	$logo .= '/images/amp-site-logo.png';
}
$logo_info = thk_get_image_size( $logo );
$logo_w = 600;
$logo_h = 60;

if( is_array( $logo_info ) === true ) {
	$logo_w = $logo_info[0];
	$logo_h = $logo_info[1];

	if( $logo_info[0] >= 600 ) {
		$logo_w = 600;
		$logo_h = round( $logo_w * $logo_info[1] / $logo_info[0] );
	}
	if( $logo_h >= 60 ) {
		$logo_h = 60;
		$logo_w = round( $logo_h * $logo_info[0] / $logo_info[1] );
	}
}

$thumb = '';
$thumb_info = false;
$thumb_w = 696;
$thumb_h = 696;

if( isset( $luxe['thumbnail_visible'] ) ) {
	$thumb_id  = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src( $thumb_id, true );
	$thumb = $thumb_url[0];
}
if( empty( $thumb ) ) {
	$no_img_png = 'no-img.png';
	$thumb = TURI . '/images/no-img.png';
}
else {
	$thumb_info = thk_get_image_size( $thumb );
}

if( is_array( $thumb_info ) === true ) {
	$thumb_w = $thumb_info[0];
	$thumb_h = $thumb_info[1];

	if( $thumb_info[0] < 696 ) {
		$thumb_w = 696;
		$thumb_h = round( $thumb_w * $thumb_info[1] / $thumb_info[0] );
	}
}

$publisher = 'Organization';
if( isset( $luxe['site_name_type'] ) ) {
	if( $luxe['site_name_type'] === 'Organization' && isset( $luxe['organization_type'] ) ) {
		$publisher = $luxe['organization_type'];
	}
}
?>
{
	"@context": "https://schema.org",
	"@type": "Article",
	"mainEntityOfPage":{
		"@type":"WebPage",
		"@id":"<?php the_permalink(); ?>"
	},
	"headline": "<?php the_title();?>",
	"image": {
		"@type": "ImageObject",
		"url": "<?php echo $thumb; ?>",
		"width": <?php echo $thumb_w; ?>,
		"height": <?php echo $thumb_h, "\n"; ?>
	},
	"datePublished": "<?php the_time('Y/m/d') ?>",
	"dateModified": "<?php the_modified_date('Y/m/d') ?>",
	"author": {
		"@type": "Person",
		"name": "<?php $auth = get_users(); the_author_meta( 'display_name', $auth[0]->ID ); ?>"
	},
	"publisher": {
		"@type": "<?php echo $publisher; ?>",
		"name": "<?php echo THK_SITENAME; ?>",
		"description": "<?php echo isset( $luxe['header_catchphrase_change'] ) ? $luxe['header_catchphrase_change'] : THK_DESCRIPTION; ?>",
		"logo": {
			"@type": "ImageObject",
			"url": "<?php echo $logo; ?>",
			"width": <?php echo $logo_w; ?>,
			"height": <?php echo $logo_h, "\n"; ?>
		}
	},
	"description": "<?php echo apply_filters( 'thk_create_description', '' ); ?>"
}
</script>
<?php
		wp_enqueue_style( 'luxe-amp', TDEL . '/style-amp.css', false, array(), 'screen' );
		wp_add_inline_style( 'luxe-amp', thk_direct_style( TPATH . DSEP . 'style-amp.min.css' ) );

		if( isset( $luxe['child_css'] ) && TDEL !== SDEL ) {
			wp_enqueue_style( 'luxech-amp', SDEL . '/style-amp.css', false, array(), 'screen' );
			wp_add_inline_style( 'luxech-amp', thk_direct_style( SPATH . DSEP . 'style-amp.min.css' ) );
		}
	}
}, 6 );

