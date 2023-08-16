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

add_action( 'media_buttons', function() {
	$label = __( 'Blog Card', 'luxeritas' );
?>
<a href="#" id="thk-blogcard-action" class="button" title="<?php echo $label; ?>"><span class="thk-blog-card-icon"></span><?php echo $label; ?></a>
<?php
}, 20 );

add_action( 'admin_footer', function() {
?>
<!-- #dialog-form  -->
<div id="thk-blogcard-form" title="<?php echo __( 'Insert Blog Card', 'luxeritas' ); ?>">
    <form>
        <table id="form1">
            <tr>
                <td>URL</td>
                <td><input type="text" id="thk-blogcard-url" name="thk-blogcard-url" value="" size="30" /></td>
            </tr>   
            <tr>
                <td><?php echo __( 'Link Text', 'luxeritas' ); ?></td>
                <td><input type="text" id="thk-blogcard-str" name="thk-blogcard-str" value="" size="30" /></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="checkbox" id="thk-blogcard-target" name="thk-blogcard-target" value=""><?php echo __( 'Open link in a new tab', 'luxeritas' ); ?></td>
            </tr>
        </table>
    </form>
</div>

<script>
jQuery(function($) {
	var bc = '#thk-blogcard-'
	,   fm = $(bc + 'form');

	fm.dialog({
		autoOpen: false,
		height: 230,
		width: 370,
		modal: true,
		buttons: {  // ダイアログに表示するボタンと処理
			"<?php echo __( 'Add Link', 'luxeritas' ); ?>": function() {
				var mce = null
				,   url = $(bc + 'url').val()
				,   str = $(bc + 'str').val()
				,   tgt = $(bc + 'target').prop('checked')
				,   insert = '<a href="' + url + '" data-blogcard="1">' + str + '</a>';

				if( window.tinyMCE ) mce = tinyMCE;

				if( tgt == true ) insert = '<a href="' + url + '" data-blogcard="1" target="_blank">' + str + '</a>';

				if( url == '' || url == null ) return false;

				if( mce !== null && mce.activeEditor && !( mce.activeEditor.isHidden() ) ) {
					// TinyMCE がオープンならそれを使う
					tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, insert )
				} else if(window.parent.QTags) {
					// なければ QTag で挿入
					QTags.insertContent( insert );
				}
				$(this).dialog('close');
				// ダイアログを閉じたらフォーカスを移す
				setTimeout( function(){ $('iframe').eq(0).focus(); }, 0 );
				setTimeout( function(){ $('textarea').eq(0).focus(); }, 0 );
			},
			"<?php echo __( 'Cancel', 'luxeritas' ); ?>": function() {
				$(this).dialog('close');
				// ダイアログを閉じたらフォーカスを移す
				setTimeout( function(){ $('iframe').eq(0).focus(); }, 0 );
				setTimeout( function(){ $('textarea').eq(0).focus(); }, 0 );
			}
		},
	});

	// ブログカードボタンがクリックされたらダイアログを表示
	$(bc + 'action').click( function() {
		fm.dialog('open');
		fm.find(bc + 'url').val('');
		fm.find(bc + 'str').val(''); 
		fm.find(bc + 'target').removeAttr('checked'); 

		// ボタンの色を WordPress の管理画面の配色に合わせた色に変更する
		if( typeof $('.button-primary')[0] !== 'undefined' ) {
	        	var bp = $('.button-primary')
			,   ub = $('.ui-dialog-buttonset .ui-button')
	        	,   csspa = {
				'color': bp.css('color'),
				'background': bp.css('background'),
				'box-shadow': bp.css('box-shadow'),
				'border-color': bp.css('border-color')
			};
			$.each( csspa, function( i, elm ) {
				ub.css( i, elm );
			});
			ub.hover(
				function() {
					$(this).css('opacity', '.85');
				},
				function() {
					$(this).css('opacity', '');
				}
			);
		}
		return false;
	});

	// オーバーレイがクリックされたらダイアログを閉じる
	$(document).on( 'click', '.ui-widget-overlay', function() {
		fm.dialog('close');
		setTimeout( function(){ $('iframe').eq(0).focus(); }, 0 );
		setTimeout( function(){ $('textarea').eq(0).focus(); }, 0 );
	}); 
});
</script>
<?php
});
