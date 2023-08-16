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
// 記事投稿(編集)画面に更新レベルのボックス追加
//---------------------------------------------------------------------------

/* ボックス追加 */
add_action( 'admin_menu', function() {
	add_meta_box( 'update_level', __( 'Update method', 'luxeritas' ), 'post_update_level_box', 'post', 'side', 'default' );
	add_meta_box( 'update_level', __( 'Update method', 'luxeritas' ), 'post_update_level_box', 'page', 'side', 'default' );
} );

/* スタイル追加 */
add_action( 'admin_print_styles', function() {
	wp_register_style( 'thk_post_update_style', get_template_directory_uri() . '/css/post-update.css', false, false );
        wp_enqueue_style( 'thk_post_update_style' );
} );

/* メインフォーム */
if( function_exists( 'post_update_level_box' ) === false ):
function post_update_level_box() {
	global $post;
?>
<div style="padding-top: 5px; overflow: hidden;">
<div style="padding:5px 0"><input name="update_level" type="radio" value="high" checked="checked" /><?php echo  __( 'Normal update', 'luxeritas' ); ?></div>
<div style="padding: 5px 0"><input name="update_level" type="radio" value="low" /><?php echo  __( 'Quick fix (No change to modify date)', 'luxeritas' ); ?></div>
<div style="padding: 5px 0"><input name="update_level" type="radio" value="del" /><?php echo  __( 'Erase modify date (Publish date and modify date becomes the same)', 'luxeritas' ); ?></div>
<div style="padding: 5px 0; margin-bottom: 10px"><input id="update_level_edit" name="update_level" type="radio" value="edit" /><?php echo  __( 'Set the modify date manually', 'luxeritas' ); ?></div>
<?php
	if( get_the_modified_date( 'c' ) ) {
		$stamp = __( 'Modified on:', 'luxeritas' ) . ' <span style="font-weight:bold">' . get_the_modified_date( __( 'M j, Y @ H:i', 'luxeritas') ) . '</span>';
	}
	else {
		$stamp = __( 'Modified on:', 'luxeritas' ) . ' <span style="font-weight:bold">' . __( 'Not updated', 'luxeritas' ) .'</span>';
	}
	$date = date_i18n( get_option('date_format') . ' @ ' . get_option('time_format'), strtotime( $post->post_modified ) );
?>
<span class="modtime"><?php printf( $stamp, $date ); ?></span>
<div id="timestamp_mod_div" onkeydown="document.getElementById('update_level_edit').checked=true" onclick="document.getElementById('update_level_edit').checked=true">
<?php thk_time_mod_form(); ?>
</div>
</div>
<?php
}
endif;

/* 更新日時変更の入力フォーム */
if( function_exists( 'thk_time_mod_form' ) === false ):
function thk_time_mod_form() {
	global $wp_locale, $post;

	$tab_index = 0;
	$tab_index_attribute = '';
	if ( (int) $tab_index > 0 ) {
		$tab_index_attribute = ' tabindex="' . $tab_index . '"';
	}

	$jj_mod = mysql2date( 'd', $post->post_modified, false );
	$mm_mod = mysql2date( 'm', $post->post_modified, false );
	$aa_mod = mysql2date( 'Y', $post->post_modified, false );
	$hh_mod = mysql2date( 'H', $post->post_modified, false );
	$mn_mod = mysql2date( 'i', $post->post_modified, false );
	$ss_mod = mysql2date( 's', $post->post_modified, false );

	$year = '<label for="aa_mod" class="screen-reader-text">' . __( 'Year', 'luxeritas' ) .
		'</label><input type="text" id="aa_mod" name="aa_mod" value="' .
		$aa_mod . '" size="4" maxlength="4"' . $tab_index_attribute . ' autocomplete="off" />' . __( 'Year', 'luxeritas' );

	$month = '<label for="mm_mod" class="screen-reader-text">' . __( 'Month', 'luxeritas' ) .
		'</label><select id="mm_mod" name="mm_mod"' . $tab_index_attribute . ">\n";
	for( $i = 1; $i < 13; $i = $i +1 ) {
		$monthnum = zeroise($i, 2);
		$month .= "\t\t\t" . '<option value="' . $monthnum . '" ' . selected( $monthnum, $mm_mod, false ) . '>';
		$month .= $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
		$month .= "</option>\n";
	}
	$month .= '</select>';

	$day = '<label for="jj_mod" class="screen-reader-text">' . __( 'Day', 'luxeritas' ) .
		'</label><input type="text" id="jj_mod" name="jj_mod" value="' .
		$jj_mod . '" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />' . __( 'Day', 'luxeritas' );
	$hour = '<label for="hh_mod" class="screen-reader-text">' . __( 'Hour', 'luxeritas' ) .
		'</label><input type="text" id="hh_mod" name="hh_mod" value="' . $hh_mod .
		'" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';
	$minute = '<label for="mn_mod" class="screen-reader-text">' . __( 'Minute', 'luxeritas' ) .
		'</label><input type="text" id="mn_mod" name="mn_mod" value="' . $mn_mod .
		'" size="2" maxlength="2"' . $tab_index_attribute . ' autocomplete="off" />';

	printf( '%1$s %2$s %3$s @ %4$s : %5$s', $year, $month, $day, $hour, $minute );
	echo '<input type="hidden" id="ss_mod" name="ss_mod" value="' . $ss_mod . '" />';
}
endif;

/* 「修正のみ」は更新しない。それ以外は、それぞれの更新日時に変更する */
add_action( 'wp_insert_post_data', function( $data, $postarr ) {
	$mydata = isset( $_POST['update_level'] ) ? $_POST['update_level'] : null;

	if( $mydata === 'low' ){
		unset( $data['post_modified'] );
		unset( $data['post_modified_gmt'] );
	}
	elseif( $mydata === 'edit' ) {
		$aa_mod = $_POST['aa_mod'] <= 0 ? date('Y') : $_POST['aa_mod'];
		$mm_mod = $_POST['mm_mod'] <= 0 ? date('n') : $_POST['mm_mod'];
		$jj_mod = $_POST['jj_mod'] > 31 ? 31 : $_POST['jj_mod'];
		$jj_mod = $jj_mod <= 0 ? date('j') : $jj_mod;
		$hh_mod = $_POST['hh_mod'] > 23 ? $_POST['hh_mod'] -24 : $_POST['hh_mod'];
		$mn_mod = $_POST['mn_mod'] > 59 ? $_POST['mn_mod'] -60 : $_POST['mn_mod'];
		$ss_mod = $_POST['ss_mod'] > 59 ? $_POST['ss_mod'] -60 : $_POST['ss_mod'];
		$modified_date = sprintf( '%04d-%02d-%02d %02d:%02d:%02d', $aa_mod, $mm_mod, $jj_mod, $hh_mod, $mn_mod, $ss_mod );
		if ( ! wp_checkdate( $mm_mod, $jj_mod, $aa_mod, $modified_date ) ) {
			unset( $data['post_modified'] );
			unset( $data['post_modified_gmt'] );
			return $data;
		}
		$data['post_modified'] = $modified_date;
		$data['post_modified_gmt'] = get_gmt_from_date( $modified_date );
	}
	elseif( $mydata === 'del' ) {
		$data['post_modified'] = $data['post_date'];
	}
	return $data;
}, 10, 2 );
