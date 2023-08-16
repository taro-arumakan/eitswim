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
 * og:image / twitter:image のボックス追加
 *---------------------------------------------------------------------------*/

/* ボックス追加 */
if( get_theme_mod( 'facebook_ogp_enable', true ) === true || get_theme_mod( 'twitter_card_enable', true ) === true ) {
	add_action( 'admin_menu', function() {
		add_meta_box( 'og_image', __( '&quot;og:image / twitter:image&quot;', 'luxeritas' ) , 'og_image_meta_box', 'post', 'normal', 'high' );
		add_meta_box( 'og_image', __( '&quot;og:image / twitter:image&quot;', 'luxeritas' ), 'og_image_meta_box', 'page', 'normal', 'high' );
	});
}

/* 投稿画面に表示するフォーム */
if( function_exists( 'og_image_meta_box' ) === false ):
function og_image_meta_box() {
	global $post;
	/* 既に値がある場合、その値をフォームに出力 */
	$image = '';
	$og_image = get_post_meta( $post->ID, 'og_img', true );
	$post_thumbnail = has_post_thumbnail();
	$cont = $post->post_content;
	$preg = '/<img.*?src=(["\'])(.+?)\1.*?>/i';

	if( !empty( $og_image ) ) {
		$image = get_post_meta( $post->ID, 'og_img', true );
	}
	elseif( !empty( $post_thumbnail ) ) {
		$img_id = get_post_thumbnail_id();
		$img_arr = wp_get_attachment_image_src( $img_id, 'full');
		$image = $img_arr[0];
	}
	elseif( preg_match( $preg, $cont, $img_url ) ) {
		$image = $img_url[2];
	}
?>
<script>thkImageSelector('og-img', 'og:image / twitter:image');</script>
<div id="og-img-form">
<input id="og-img" type="hidden" name="og_img" value="<?php echo $image; ?>" />
<input id="og-img-set" type="button" class="button" value="<?php echo __( 'Set image', 'luxeritas' ); ?>" />
<input id="og-img-del" type="button" class="button" value="<?php echo __( 'Delete image', 'luxeritas' ); ?>" />
</div>
<?php
	if( !empty( $image ) ) {
?>
<div id="og-img-view"><img src="<?php echo $image; ?>" /></div>
<?php
	}
	else {
?>
<div id="og-img-view"></div>
<?php
	}
}
endif;

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
	$og_data = isset( $_POST['og_img'] ) && !empty( $_POST['og_img'] ) ? $_POST['og_img'] : null;
	$og_img = get_post_meta( $post_id, 'og_img' );
	if( !empty( $og_data ) && empty( $og_img ) ) {
		/* 保存 */
		add_post_meta( $post_id, 'og_img', $og_data, true ) ;
	}
	elseif( !empty( $og_data ) && $og_data !== $og_img ) {
        	/* 更新 */
        	update_post_meta( $post_id, 'og_img', $og_data ) ;
	}
	else {
        	/* 削除 */
        	delete_post_meta( $post_id, 'og_img' ) ;
	}
} );

/* Javascript */
add_action( 'admin_print_scripts', function() {
	wp_enqueue_media();
	wp_enqueue_script( 'thk-img-selector-script', get_template_directory_uri() . '/js/thk-img-selector.js', array( 'media-views' ), false );
	wp_localize_script( 'media-views', '_thkImageViewsL10n', array( 'setImage' => __( 'Set image', 'luxeritas' ) ) );

} );

/* CSS */
add_action( 'admin_print_styles', function() {
	wp_register_style( 'thk-img-selector-style', get_template_directory_uri() . '/css/thk-img-selector.css', false, false );
        wp_enqueue_style( 'thk-img-selector-style' );
} );
