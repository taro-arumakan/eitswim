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

//---------------------------------------------------------------------------
// AMP 解除のボックス追加
//---------------------------------------------------------------------------

/* ボックス追加 */
if( get_theme_mod( 'amp_enable', false ) === true ) {
	add_action( 'admin_menu', function() {
		add_meta_box( 'thk_post_amp', __( 'Disable AMP', 'luxeritas' ), 'thk_post_amp_box', 'post', 'side', 'default' );
		add_meta_box( 'thk_post_amp', __( 'Disable AMP', 'luxeritas' ), 'thk_post_amp_box', 'page', 'side', 'default' );
	});
}

/* 投稿画面に表示するフォーム */
if( function_exists( 'thk_post_amp_box' ) === false ):
function thk_post_amp_box() {
	global $post;
	/* 既に値がある場合 */
	$thk_amp = get_post_meta( $post->ID, 'thk_amp', true );
	$thk_amp = $thk_amp === 'disable' ? 'disable' : 'enable';
?>
<div id="thk-post-amp-form">
<p class="meta-options">
<label class="selectit">
<input id="thk-amp" type="checkbox" name="thk_amp" value="<?php echo $thk_amp; ?>"<?php echo $thk_amp === 'disable' ? ' checked' : ''; ?> />
<?php echo __( 'Disable AMP on this page', 'luxeritas' ); ?>
</label>
</p>
</div>
<?php
}
endif;

/* カスタムフィールドの値をDBに書き込む */
add_action( 'save_post', function( $post_id ) {
	$amp_data = isset( $_POST['thk_amp'] ) ? $_POST['thk_amp'] : null;
	$amp_data = $amp_data ? 'disable' : null;
	//$thk_amp = get_post_meta( $post_id, 'thk_amp' );
	if( !empty( $amp_data ) ) {
		/* 保存 */
		add_post_meta( $post_id, 'thk_amp', $amp_data, true ) ;
	}
	else {
        	/* 削除 */
        	delete_post_meta( $post_id, 'thk_amp' ) ;
	}
} );
