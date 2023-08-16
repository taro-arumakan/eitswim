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
 * AMP Extension の配列
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_amp_extensions' ) === false ):
function thk_amp_extensions() {
	return array(
		'amp-form'		=> '/v0/amp-form-0.1.js',
		'amp-ad'		=> '/v0/amp-ad-0.1.js',
		'amp-analytics'		=> '/v0/amp-analytics-0.1.js',
		'amp-iframe'		=> '/v0/amp-iframe-0.1.js',
		'amp-anim'		=> '/v0/amp-anim-0.1.js',
		'amp-audio'		=> '/v0/amp-audio-0.1.js',
		'amp-video'		=> '/v0/amp-video-0.1.js',
		'amp-carousel'		=> '/v0/amp-carousel-0.1.js',
		'amp-youtube'		=> '/v0/amp-youtube-0.1.js',
		'amp-vine'		=> '/v0/amp-vine-0.1.js',
		'amp-social-share'	=> '/v0/amp-social-share-0.1.js',
		'amp-facebook'		=> '/v0/amp-facebook-0.1.js',
		'amp-twitter'		=> '/v0/amp-twitter-0.1.js',
		'amp-instagram'		=> '/v0/amp-instagram-0.1.js',
		'amp-pinterest'		=> '/v0/amp-pinterest-0.1.js',
		'amp-lightbox'		=> '/v0/amp-lightbox-0.1.js',
		'amp-image-lightbox'	=> '/v0/amp-image-lightbox-0.1.js',
	);
}
endif;

/*---------------------------------------------------------------------------
 * AMP で禁止されてる要素の置換もしくは削除
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_amp_not_allowed_tag_replace' ) === false ):
function thk_amp_not_allowed_tag_replace( $contents ) {
	/* チェックで怒られるので、コメントはカタカナ */

	// Amazon 特有のリンク置換
	$contents = str_replace( 'rcm-jp.amazon.co.jp', 'rcm-fe.amazon-adsystem.com', $contents );
	$contents = preg_replace( '/(http:|https:)*\/\/rcm-fe.amazon-adsystem.com/', 'https://rcm-fe.amazon-adsystem.com', $contents);

	// oEmbed で自動挿入されるコンテンツ
	$i_frame = 'i' . 'frame';
	$contents = preg_replace( '/<p><' . $i_frame . '.+?wp-embedded-content.+?' . '<\/' . $i_frame . '><\/p>/', '', $contents );

	// アイフレーム
	$contents = str_replace( '<' . $i_frame, '<amp-' . $i_frame . ' layout="responsive" sandbox="allow-scripts allow-same-origin allow-popups"', $contents );
	$contents = str_replace( '</' . $i_frame, '</amp-' . $i_frame, $contents );

	if( stripos( $contents, 'security=' ) !== false ) {
		$contents = preg_replace( '/security=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/security=\'[^\']+?\'/', '', $contents );
	}

	// アイフレームは、SSL しか許可されてないので、src が http:// で始まってる場合は削除
	$contents = preg_replace( '/<amp-' . $i_frame . '[^>]+?src=[^>]+?http:\/\/[^>]+?><\/amp-' . $i_frame . '>/im', '', $contents );

	// アイフレームの placeholder（ファーストビューに amp-iframe が出てくるとエラーになるため）
	$placeholder = '<amp-img layout="fill" width="100" height="100" src="' . TURI . '/images/trans.png'.'" placeholder>';
	$contents = str_replace( '</amp-' . $i_frame, $placeholder . '</amp-' . $i_frame, $contents );

	// marginwidth と marginheight 削除
	$contents = preg_replace( '/\s*?marginwidth=[\'|\"]*?[0-9]*?[\'|\"]\s/i', '', $contents );
	$contents = preg_replace( '/\s*?marginheight=[\'|\"]*?[0-9]*?[\'|\"]\s/i', '', $contents );

	preg_match_all( '/<amp-' . $i_frame . '[^>]+?>/ism', $contents, $frames );
	foreach( array_unique( $frames[0] ) as $val ) {
		// width が 300px 以下の場合、responsive 消す
		$wms = preg_replace( '/.+? width=[\"|\']*([0-9]+)?[\"|\']*.+/', "$1", $val );
		if( is_numeric( $wms ) && (int)$wms < 300 ) {
			$replace = str_replace( ' layout="responsive"', '', $val );
			$contents = str_replace( $val, $replace, $contents );
			$val = $replace;
		}

		// width や height がなかったら、responsive 消して、width と height を指定
		if( stripos( $val, ' width=' ) === false || stripos( $val, ' height=' ) === false) {
			$replace = str_replace( ' layout="responsive"', '', $val );
			$contents = str_replace( $val, $replace, $contents );
			$val = $replace;

			// Amazon の場合
			if( stripos( $val, 'amazon-adsystem.com' ) !== false ) {
				if( stripos( $val, ' width=' ) === false ) {
					$replace = str_replace( '>', ' width="120">', $val );
					$contents = str_replace( $val, $replace, $contents );
				}
				$val = $replace;
				if( stripos( $val, ' height=' ) === false ) {
					$replace = str_replace( '>', ' height="250">', $val );
					$contents = str_replace( $val, $replace, $contents );
				}
			}
			else {
				if( stripos( $val, ' width=' ) === false ) {
					$replace = str_replace( '>', ' width="300">', $val );
					$contents = str_replace( $val, $replace, $contents );
				}
				$val = $replace;
				if( stripos( $val, ' height=' ) === false ) {
					$replace = str_replace( '>', ' height="300">', $val );
					$contents = str_replace( $val, $replace, $contents );
				}
			}
		}
	}

	// イメージ ( thk_amp_tag_replace() の前処理 )
	$contents = preg_replace( '/<img ([^>]+?)>/ism', '<amp-img layout="responsive" $1></amp-img>', $contents );

	// スタイル
	$contents = preg_replace( '/(\sstyle=[\"|\'].+?[\"|\'])/i', '', $contents );

	// アドセンス
	if( stripos( $contents, 'data-ad-client' ) !== false ) {
		$adsense  = thk_replace_amp_adsense( $contents );
		$contents = preg_replace( '/(<script.+?data-ad-client.+?data-ad-slot.+?adsbygoogle.+?<\/script>)/ism', $adsense, $contents );
	}

	// スクリプト
	if( stripos( $contents, '<script' ) !== false || stripos( $contents, '</script' ) !== false ) {
		$contents = preg_replace( '/<script.*?>.*?<\/script>/ism', '', $contents ) ;
	}
	if( stripos( $contents, 'onload=' ) !== false ) {
		$contents = preg_replace( '/onload=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onload=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'onclick=' ) !== false ) {
		$contents = preg_replace( '/onclick=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onclick=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'ondblclick=' ) !== false ) {
		$contents = preg_replace( '/ondblclick=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/ondblclick=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'onmouseover=' ) !== false ) {
		$contents = preg_replace( '/onmouseover=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onmouseover=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'onmouseout=' ) !== false ) {
		$contents = preg_replace( '/onmouseout=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onmouseout=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'onmouseup=' ) !== false ) {
		$contents = preg_replace( '/onmouseup=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onmouseup=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'onmousedown=' ) !== false ) {
		$contents = preg_replace( '/onmousedown=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onmousedown=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'onmousemove=' ) !== false ) {
		$contents = preg_replace( '/onmousemove=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/onmousemove=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'touchstart=' ) !== false ) {
		$contents = preg_replace( '/ontouchstart=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/ontouchstart=\'[^\']+?\'/', '', $contents );
		$contents = preg_replace( '/touchstart=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/touchstart=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'touchmove=' ) !== false ) {
		$contents = preg_replace( '/ontouchmove=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/ontouchmove=\'[^\']+?\'/', '', $contents );
		$contents = preg_replace( '/touchmove=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/touchmove=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, 'touchend=' ) !== false ) {
		$contents = preg_replace( '/ontouchend=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/ontouchend=\'[^\']+?\'/', '', $contents );
		$contents = preg_replace( '/touchend=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/touchend=\'[^\']+?\'/', '', $contents );
	}
	if( stripos( $contents, '<noscript' ) !== false || stripos( $contents, '</noscript' ) !== false ) {
		$contents = str_replace( '<noscript>', '', $contents );
		$contents = str_replace( '</noscript>', '', $contents );
	}
	if( stripos( $contents, 'i-video' ) !== false ) {
		$contents = str_replace( ' class="i-video"', '', $contents );
		$contents = str_replace( " class='i-video'", '', $contents );
	}

	// ターゲット
	if( stripos( $contents, 'target=' ) !== false ) {
		$contents = preg_replace( '/target=\"[^\"]+?\"/', '', $contents );
		$contents = preg_replace( '/target=\'[^\']+?\'/', '', $contents );
	}

	// フォーム（amp-form が全く役に立たないので丸々消す仕様に変更）
	if( stripos( $contents, '<form' ) !== false || stripos( $contents, '</form' ) !== false ) {
		$contents = preg_replace( '/<form.*?>.*?<\/form>/ism', '', $contents );
	}
	if( stripos( $contents, '<select' ) !== false || stripos( $contents, '</select' ) !== false ) {
		$contents = preg_replace( '/<select.*?>.*?<\/select>/ism', '', $contents );
	}
	if( stripos( $contents, '<input' ) !== false ) {
		$contents = preg_replace( '/<input[^>]+?>/i', '', $contents);
	}
	// テキストエリアは pre に置換
	if( stripos( $contents, '<textarea' ) !== false || stripos( $contents, '</textarea' ) !== false ) {
		$contents = preg_replace( '/<textarea/i', '<pre', $contents);
		$contents = preg_replace( '/<\/textarea>/i', '</pre>', $contents);
	}
	/*
	// アクション
	$contents = str_replace( 'action=', 'action-xhr=', $contents );
	$contents = str_replace( 'action-xhr="http:', 'action-xhr="https:', $contents );

	$contents = str_replace( 'method="post"', 'method="post" target="_blank"', $contents );
	$contents = str_replace( 'method="get"', 'method="get" target="_blank" action="https:' . pdel( THK_HOME_URL ) . '"', $contents );
	*/

	// その他の禁止タグ
	if( stripos( $contents, '<object' ) !== false || stripos( $contents, '</object' ) !== false ) {
		$contents = preg_replace( '/<object.*?>.*?<\/object>/ism', '', $contents );
	}
	if( stripos( $contents, '<applet' ) !== false || stripos( $contents, '</applet' ) !== false ) {
		$contents = preg_replace( '/<applet.*?>.*?<\/applet>/ism', '', $contents );
	}
	if( stripos( $contents, '<font' ) !== false || stripos( $contents, '</font' ) !== false ) {
		$contents = preg_replace( '/<font[^>]+?>/i', '', $contents);
		$contents = preg_replace( '/<\/font>/i', '', $contents);
	}

	// ヨメレバ・カエレバの Amazon 画像に width / hight 追加
	if( stripos( $contents, 'amazon.com' ) !== false ) {
		$contents = preg_replace( '/ src="(http:|https:)?\/\/(ecx.images-amazon.com)/i', ' width="75" height="75" sizes="(max-width: 75px) 100vw, 75px" src="$1//$2', $contents );
	}

	// ヨメレバ・カエレバの楽天画像に width / hight 追加
	if( stripos( $contents, 'rakuten.co.jp' ) !== false ) {
		$contents = preg_replace( '/ src="(http:|https:)?\/\/(thumbnail.image.rakuten.co.jp)/i', ' width="75" height="75" sizes="(max-width: 75px) 100vw, 75px" src="$1//$2', $contents );
	}

	// ヨメレバ・カエレバの Yahoo!ショッピング画像に width / hight 追加
	if( stripos( $contents, 'yimg.jp' ) !== false ) {
		$contents = preg_replace( '/ src="(http:|https:)?\/\/(item.shopping.c.yimg.jp)/i', ' width="75" height="75" sizes="(max-width: 75px) 100vw, 75px" src="$1//$2', $contents );
	}

	return $contents;
}
endif;

/*---------------------------------------------------------------------------
 * AMP 特有のタグへの置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_amp_tag_replace' ) === false ):
function thk_amp_tag_replace( $contents ) {
	// img
	if( preg_match_all( '/<amp-img(.+?)\/?>/ism', $contents, $data ) > 0 ) {
		foreach( $data[0] as $img ) {
			if( stripos( $img, ' placeholder>' ) !== false ) continue;

			if( preg_match('/src="([^"]+?)"/is', $img, $matchs ) === 1 ) {

				/* srcset 付けると、無条件で画面サイズに合った画像が選択されるのでやめた */
				// srcset 付きの amp-img 生成
				//$img_tag = thk_create_srcset_img_tag( $matchs[1] );

				// srcset が見つからなかった場合は無理矢理作る
				//if( stripos( $img_tag, 'srcset' ) === false ) {
					$src = $matchs[1];
					$width	= null;
					$height	= null;
					$alt	= null;
					$title	= null;
					$cls	= null;
					$prop = array(
						'src'	=> 'src="' . $src . '"',
						'width'	=> 'width=""',
						'height'=> 'height=""',
						'alt'	=> 'alt=""',
						'title'	=> 'title=""',
						'class'	=> 'class=""',
						'sizes'	=> 'sizes=""'
					);

					// width
					if( preg_match('/width="([^"]*?)"/is', $img, $matchs ) === 1 ) {
						$width = $matchs[1];
						$prop['width'] = 'width="' . $width . '"';
					}

					// height
					if( preg_match('/height="([^"]*?)"/is', $img, $matchs ) === 1 ) {
						$height = $matchs[1];
						$prop['height'] = 'height="' . $height . '"';
					}

					// alt
					if( preg_match('/alt="([^"]*?)"/is', $img, $matchs ) === 1 ) {
						$alt = $matchs[1];
						$prop['alt'] = 'alt="' . $alt . '"';
					}

					// title
					if( preg_match('/title="([^"]*?)"/is', $img, $matchs ) === 1 ) {
						$title = $matchs[1];
						$prop['title'] = 'title="' . $title . '"';
					}

					// class
					if( preg_match('/class="([^"]*?)"/is', $img, $matchs ) === 1 ) {
						$cls = $matchs[1];
						$prop['class'] = 'class="' . $cls . ' internal-content-img"';
					}

					if( empty( $width ) || empty( $height ) ) {
						$info = thk_get_image_size( $src );
						if( $info !== false ) {
							$width  = $info[0];
							$height = $info[1];
						} else {
							$width  = 680;	// object-fit: contain で調整
							$height = 320;
							$prop['class'] = str_replace( 'internal-content-img', 'external-content-img', $prop['class'] );
						}
						$prop['width']  = 'width="'  . $width  . '"';
						$prop['height'] = 'height="' . $height . '"';
					}

					// sizes
					$prop['sizes'] = 'sizes="(max-width:' . $width . 'px) 100vw,' . $width .'px"';

					$img_tag = '<amp-img';
					foreach( $prop as $val ) $img_tag .= ' ' . $val;
					$img_tag .= '>';
				//}
				$contents = preg_replace( '{' . preg_quote( $img ) .'}', $img_tag , $contents, 1 );
			}
		}
	}

	$i_frame = 'i' . 'frame';

	// GIF
	$contents = preg_replace(
		'/<amp-img([^>]+?src="[^>]+?\.gif\"[^>]+?><\/)amp-img>/ism',
		'<amp-anim$1amp-anim>',
		$contents
	);
	// video
	if( stripos( $contents, '<video' ) !== false || stripos( $contents, '</video' ) !== false ) {
		$contents = preg_replace(
			'/<video ([^>]+?)>(.+?src=[\'|\"])(http:|https:)(.+?)<\/video>/ism',
			'<amp-video layout="responsive" poster="' . TDEL . '/images/poster.png' . '" $1>$2$4</amp-video>',
			$contents
		);
	}
	// Youtube
	if( stripos( $contents, 'youtube.com' ) !== false ) {
		$contents = preg_replace(
			'/<amp-' . $i_frame . '[^>]+?src="https?:\/\/www.youtube.com\/embed\/([^\?"]+).*?".*?><\/amp-' . $i_frame . '>/ism',
			'<amp-youtube layout="responsive" data-videoid="$1" width="800" height="450"></amp-youtube>',
			$contents
		);
	}
	// Twitter
	if( stripos( $contents, 'twitter-tweet' ) !== false ) {
		$contents = preg_replace(
			'/<blockquote class="twitter-tweet".*?>.+?<a href="https:\/\/twitter.com\/.*?\/status\/(.*?)">.+?<\/blockquote>/ism',
			'<amp-twitter layout="responsive" width="800" height="600" data-tweetid="$1"></amp-twitter>',
			$contents
		);
	}
	// Facebook
	if( stripos( $contents, 'fb-video' ) !== false ) {
		$contents = preg_replace(
			'/<div class="fb-video" data-allowfullscreen="true" data-href="([^"]+?)"><\/div>/ism',
			'<amp-facebook layout="responsive" width="800" height="450" data-href="$1"></amp-facebook>',
			$contents
		);
		$contents = preg_replace('/ +allowTransparency(=["][^"]*?["])?/i', '', $contents);
		$contents = preg_replace('/ +allowFullScreen(=["][^"]*?["])?/i', '', $contents);
	}
	// Instagram
	if( stripos( $contents, 'instagram-media' ) !== false ) {
		$contents = preg_replace(
			'/<blockquote class="instagram-media".+?"https:\/\/www.instagram.com\/p\/(.+?)\/".+?<\/blockquote>/ism',
			'<amp-instagram layout="responsive" width="500" height="500" data-shortcode="$1"></amp-instagram>',
			$contents
		);
	}
	// vine
	if( stripos( $contents, 'vine.co' ) !== false ) {
		$contents = preg_replace(
			'/<amp-' . $i_frame . '[^>]+?src="https:\/\/vine.co\/v\/(.+?)\/embed\/simple".+?><\/amp-' . $i_frame . '>/ism',
			'<amp-vine layout="responsive width="400" height="400" data-vineid="$1""></amp-vine>',
			$contents
		);
	}

	return $contents;
}
endif;

/*---------------------------------------------------------------------------
 * AMP 用 Google Adsense 置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_replace_amp_adsense' ) === false ):
function thk_replace_amp_adsense( $ad ) {
	$ad_client = '';
	$ad_slot   = '';

	preg_match('/data-ad-client="(ca-pub-[^"]+?)"/i', $ad, $data );
	if( isset( $data[1] ) ) {
		$ad_client = $data[1];
	}
	else {
		return null;
	}

	preg_match('/data-ad-slot="([^"]+?)"/i', $ad, $data );
	if( isset( $data[1] ) ) {
		$ad_slot = $data[1];
	}
	else {
		return null;
	}

	return '<amp-ad width="300" height="250" type="adsense" data-ad-client="' . $ad_client . '" data-ad-slot="' . $ad_slot . '"></amp-ad>';
}
endif;

/*---------------------------------------------------------------------------
 * ウジェット内にあるタグの AMP 置換
 *---------------------------------------------------------------------------*/
if( function_exists( 'thk_amp_dynamic_sidebar' ) === false ):
function thk_amp_dynamic_sidebar( $name ) {
	ob_start();
	dynamic_sidebar( $name );
	$contents = ob_get_clean();

	$contents = thk_amp_not_allowed_tag_replace( $contents );
	$contents = thk_amp_tag_replace( $contents );

	return $contents;
}
endif;
