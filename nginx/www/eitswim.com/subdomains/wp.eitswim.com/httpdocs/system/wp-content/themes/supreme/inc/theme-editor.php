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
global $wp_filesystem;

$filesystem = new thk_filesystem();
if( $filesystem->init_filesystem( site_url() ) === false ) return false;

$file = '';
$title = '';
$title_sub = '';
$content = '';
$error = false;
$allowed_files = array();

$files = array(
	'edit_style' => array(
		'style.css',
		__( 'Stylesheet', 'luxeritas' ),
		'<span class="blue small">' . __( '* Deleting the original comment may malfunction to have the theme not work as the child theme.', 'luxeritas' ) . '</span>'
	),
	'edit_script' => array(
		'luxech.js',
		'Javascript',
		''
	),
	'edit_header' => array(
		'add-header.php',
		'Head ' . __(  'tag', 'luxeritas' ),
		'<span class="blue small">' . __( '* Write here the Sytle or Javascript which wants to be placed in the head tag.', 'luxeritas' ) . '</span>'
	),
	'edit_footer' => array(
		'add-footer.php',
		__( 'Footer', 'luxeritas' ),
		'<span class="blue small">' . __( '* Write here the Sytle or Javascript which wants to be placed in the footer.', 'luxeritas' ) . '</span>'
	),
	'edit_analytics' => array(
		'add-analytics.php',
		__( 'Footer', 'luxeritas' ),
		'<span class="blue small">' . __( '* Recommended to add Google Analytics codes in here.', 'luxeritas' ) . '</span>'
	),
	'edit_functions' => array(
		'functions.php',
		__( 'Child Theme Functions', 'luxeritas' ),
		'<span class="blue small">' . __( '* Miss writing the functions.php may lead to malfunctin of Wordpress, so be careful!!!', 'luxeritas' ) . '</span>'
	),
	'edit_amp' => array(
		'style-amp.css',
		__( 'Stylesheet for AMP', 'luxeritas' ),
		''
	),
);

$theme = wp_get_theme( get_stylesheet() );

if( current_user_can('edit_themes') === false ) {
	wp_die('<p>' . __( 'You do not have sufficient permissions to edit templates for this site.', 'luxeritas' ) . '</p>');
}
if( $theme->exists() === false ) {
	wp_die( __( 'The requested theme does not exist.', 'luxeritas' ) );
}
if ( $theme->errors() && 'theme_no_stylesheet' == $theme->errors()->get_error_code() ) {
	wp_die( __( 'The requested theme does not exist.', 'luxeritas' ) . ' ' . $theme->errors()->get_error_message() );
}

if( TPATH !== SPATH ) {
	if( !isset( $_GET['active'] ) ) {
		$file = SPATH . DSEP . $files['edit_style'][0];
		$title = $files['edit_style'][1];
		$msg = $files['edit_style'][2];
	}
	else {
		foreach( $files as $key => $val ) {
			if( $_GET['active'] === $key ) {
				$file = SPATH . DSEP . $val[0];
				$title = $val[1];
				$msg = $val[2];
			}
		}
	}

	if( is_file( $file ) === true && file_exists( $file ) === true ) {
		$content = thk_convert( $wp_filesystem->get_contents( $file ) );
		$content = esc_textarea( $content );
	}
	else {
		$error = true;
	}

	$title_sub = str_replace( SPATH . DSEP, '', $file );
}
else {
	$title = '<span class="red">' . __( 'The theme selected is not the child theme, but the parent theme', 'luxeritas' ) . '</span>';
	$title_sub = __( 'This feature can only be used when the child theme is selected.', 'luxeritas' );
}
?>
<h3><?php echo $title, '&nbsp;:<span class="normal">&nbsp;&nbsp;', $title_sub; ?></span></h3>
<?php echo !empty( $msg ) ? '<p>' . $msg . '</p>' : ''; ?>
<p class="edit-file-name"><?php echo __( 'File editing', 'luxeritas' ); ?>:&nbsp;&nbsp;<?php echo esc_textarea( thk_convert( str_replace( '/', DSEP, str_replace( dirname( WP_CONTENT_DIR ), '', $file ) ) ) ); ?></p>
<?php
if( $theme->errors() ) {
	if( !get_settings_errors( 'luxe-custom' ) ) {
		echo '<div class="error"><p><strong>' . __( 'This theme is broken.', 'luxeritas' ) . '</strong> ' . $theme->errors()->get_error_message() . '</p></div>';
	}
}
if( $error ) {
	echo '<div class="error"><p>' . __( 'Oops, no such file exists! Double check the name and try again, merci.', 'luxeritas' ) . '</p></div>';
}
else {
?>
<div class="codemirror-wrap">
<textarea cols="180" rows="30" name="newcontent" id="editor" class="luxe-edit" aria-describedby="newcontent-description">
<?php echo $content; ?>
</textarea>
</div>
<?php
}

$codem_mode = '';
if( isset( $_GET['active'] ) ) {
	if( $_GET['active'] === 'edit_script' ) {
		$codem_mode = 'javascript';
	}
	elseif( $_GET['active'] === 'edit_functions' || $_GET['active'] === 'edit_header' || $_GET['active'] === 'edit_footer' ) {
		$codem_mode = 'php';
	}
	else {
		$codem_mode = 'css';
	}
}
else {
	$codem_mode = 'css';
}
?>
<script>
jQuery.ajax({
	success: function(){
		var editor = document.getElementById('editor');
		var uiOptions = {
			imagePath: '<?php echo TURI; ?>/images/codemirror',
			buttons : ['save','undo','redo','jump'],
			searchMode: 'inline',
			saveCallback : function(){ jQuery('#luxe-customize').submit(); }
		}
		var codeMirrorOptions = {
			mode: '<?php echo $codem_mode; ?>',
			lineNumbers: true,
			indentUnit: 8,
			tabSize: 8,
			enterMode: 'keep',
			lineWrapping: true,
			onChange: function(){
				editor.save();
			}
		}
		new CodeMirrorUI(editor,uiOptions,codeMirrorOptions);

		jQuery(document).ready(function($) {
			$(':button[value="Find"]').addClass('search-button');
			$(':button[value="Find"]').attr({
				title: '<?php echo __( 'Search', 'luxeritas' ); ?>',
				type: 'button'
			});
			$(':button[value="Replace"]').addClass('replace-button');
			$(':button[value="Replace"]').attr({
				title: '<?php echo __( 'Replace', 'luxeritas' ); ?>',
				type: 'button'
			});
			$('a.save').attr({title: '<?php echo __( 'Svae', 'luxeritas' ); ?>'});
			$('a.undo').attr({title: '<?php echo __( 'Undo', 'luxeritas' ); ?>'});
			$('a.redo').attr({title: '<?php echo __( 'Redo', 'luxeritas' ); ?>'});
			$('a.jump').attr({title: '<?php echo __( 'Jump', 'luxeritas' ); ?>'});
			$(function(){
				$('label[title="Regular Expressions"]').each(function(){
					var txt = $(this).html();
					$(this).html(
						txt.replace(/RegEx/,"<?php echo __( 'RegEx', 'luxeritas' ); ?>")
					);
				});
				$('label[title="Replace All"]').each(function(){
					var txt = $(this).html();
					$(this).html(
						txt.replace(/All/,"<?php echo __( 'Replace All', 'luxeritas' ); ?>")
					);
				});
			});
		});
	}
});
</script>
