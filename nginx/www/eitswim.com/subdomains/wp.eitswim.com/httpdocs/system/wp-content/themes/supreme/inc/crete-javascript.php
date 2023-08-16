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

class create_Javascript {
	private $_tdel   = null;
	private $_js_dir = null;
	private $_depend = array();

	public function __construct() {
		$this->_tdel   = pdel( get_template_directory_uri() );
		$this->_js_dir = TPATH . DSEP . 'js' . DSEP;

		// Javascript の依存チェック用配列
		$this->_depend = array(
			'lazyload' => $this->_js_dir . 'jquery.lazyload.min.js',
			'sscroll'  => $this->_js_dir . 'jquery.smoothScroll.min.js',
			'stickykit'=> $this->_js_dir . 'jquery.sticky-kit.min.js',
			'autosize' => $this->_js_dir . 'autosize.min.js',
		);
		foreach( $this->_depend as $key => $val ) {
			if( file_exists( $val ) === false ) unset( $this->_depend[$key] );
		}
	}

	/*
	------------------------------------
	 非同期 CSS の読み込み
	------------------------------------ */
	public function create_css_load_script( $url, $media = null ) {
		$ret = '';

		if( file_exists( TPATH . DSEP . 'style.async.min.css' ) === true && filesize( TPATH . DSEP . 'style.async.min.css' ) <= 0 ) {
			return $ret;
		}

		$ret .= <<< SCRIPT
(function(){
	var n = document.createElement('link');
	n.async = true;
	n.defer = true;

SCRIPT;
		if( $media !== null ) $ret .= "n.media = " . $media . "';";

		$ret .= <<< SCRIPT
	n.rel  = 'stylesheet';
	n.href = '{$url}?v={$_SERVER['REQUEST_TIME']}';
	if( document.getElementsByTagName('head')[0] !== null ) {
		document.getElementsByTagName('head')[0].appendChild( n );
	}
})(document);

SCRIPT;
		return $ret;
	}

	/*---------------------------------------------------------------------------
	 * Lazy Load Options
	 *---------------------------------------------------------------------------*/
	public function create_lazy_load_script() {
		global $luxe;
		$ret = '';

		if( !isset( $luxe['jquery_load'] ) || !isset( $this->_depend['lazyload'] ) ) return '';

		$ret .= <<< SCRIPT
jQuery(document).ready(function($) {
	$(function() {
		var lazy = document.querySelectorAll("[data-lazy]");
		$(lazy).lazyload({
		threshold: {$luxe['lazyload_threshold']},

SCRIPT;

		if( $luxe['lazyload_effect'] !== 'show' ) {
			$ret .= 'effect: "' . $luxe['lazyload_effect'] . '",';
			$ret .= 'effect_speed: ' . $luxe['lazyload_effectspeed'] . ',';
		}
		if( $luxe['lazyload_event'] !== 'scroll' ) {
			$ret .= 'event: "' . $luxe['lazyload_event'] . '",';
		}

		$ret .= <<< SCRIPT
			load: function() {
				//$(this).css( 'background', 'none' );
				this.style.background='none';
			},
		});
	});
});

SCRIPT;
		return $ret;
	}

	/*
	------------------------------------
	 いろいろ
	------------------------------------ */
	public function create_luxe_various_script( $is_preview = false ) {
		global $luxe;

		$ret = '';
		$broken = false;
		$home = THK_HOME_URL;
		$side_1_width = isset( $luxe['side_1_width'] ) ? $luxe['side_1_width'] : 366;

		$ca = new carray();

		$imp = $ca->thk_hex_imp_style();
		$imp_close = $ca->thk_hex_imp_style_close();

		if(
			stripos( $imp, '!;' ) === false ||
			stripos( $imp_close, '!' ) === false
		) {
			$broken = true;
		}
		else {
			$imp = str_replace( '!;', $imp_close, $imp );
		}

		require_once( INC . 'const.php' );
		require_once( INC . 'colors.php' );

		$conf = new defConfig();
		$colors_class = new thk_colors();

		$defaults = $conf->default_variables();
		$default_colors = $conf->over_all_default_colors();
		unset( $conf );

		$bg_color = isset( $luxe['body_bg_color'] ) ? $luxe['body_bg_color'] : $default_colors[$luxe['overall_image']]['contbg'];
		$inverse = $colors_class->get_text_color_matches_background( $bg_color );

		$rgb = $colors_class->colorcode_2_rgb( $inverse );

		$brap_rgba = 'background: rgba(' . $rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'] . ', .5 )';

		$ret .= <<< SCRIPT
jQuery(document).ready(function($) {
	var tbn = $('#page-top')		// トップに戻るボタン
	,   scleve = ('scroll', function() {	// スクロール監視
		var scltop = window.pageYOffset;

		//スクロールが500に達したらボタン表示
		if( scltop > 500 ) {
			tbn.fadeIn();
		} else {
			tbn.fadeOut();
		}

		// モバイル用グローバルナビの監視
		if( document.getElementById('ovlay') !== null ) {
			var select = document.documentElement
			,   laynav = document.querySelector('#layer #nav');

			if( laynav !== null ) {
				select = laynav;
			} else if( document.getElementById('sform') !== null && $('#sform').css('display') === 'block' ) {
				return;
			} else if( document.getElementById('side') !== null ) {
				select = document.getElementById('side');
			} var tnp1 = $('#layer').get( 0 ).offsetTop + select.clientHeight
			,     tnp2 = $('#layer').get( 0 ).offsetTop - document.documentElement.clientHeight;

			if( scltop > tnp1 || scltop < tnp2 ) {
				remove_ovlay();
			}
		}
	});

	function listen_scroll( e ) {
		// スクロールイベント登録
		if( typeof window.addEventListener !== 'undefined' ) {
			window.addEventListener( 'scroll', e, false );
		} else if( typeof window.attachEvent !== 'undefined' ) {
			window.attachEvent( 'onscroll', e );
		}
	} function detach_scroll( e ) {
		// スクロールイベント解除
		if( typeof window.removeEventListener !== 'undefined' ) {
			window.removeEventListener( 'scroll', e, false );
		} else if( typeof window.detachEvent !== 'undefined' ) {
			window.detachEvent( 'onscroll', e );
		}
	}

	listen_scroll( scleve );

	//トップに戻るでトップに戻る
	tbn.click(function() {
		$('html, body').animate( {
			scrollTop: 0
		}, 500 );
		return false;
	});

	/* 以下 グローバルナビ */
	$('#nav li').hover( function() {
		var t = $('>ul', this);
		t.css( 'display', 'table');
		t.css( 'width', t.outerWidth() + 'px');
		t.css( 'display', 'none');
		//t.stop().slideDown(300);
		t.stop().toggle(300);
	}, function() {
		//$('>ul', this).stop().slideUp(300);
		$('>ul', this).stop().toggle(300);
	});

	function remove_ovlay() {
		var a = [
			'sidebar',
			'sform',
			'ovlay',
			'ovlay-style'
		];

		a.forEach( function( val ) {
			if( document.getElementById(val) !== null ) {
				if( val === 'sidebar' || val === 'sform' ) {
					document.getElementById(val).removeAttribute('style');
				} else {
					//document.getElementById(val).remove();
					$('#' + val).remove();
				}
			}
		}); document.body.removeAttribute('style');
	}

SCRIPT;

		if( isset( $luxe['global_navi_visible'] ) ) {
			if( $luxe['global_navi_mobile_type'] !== 'luxury' ) {
				$ret .= <<< SCRIPT

	// モバイルメニュー (メニューオンリー版)
	var nav = $('#nav')
	,   men = $('.menu ul')
	,   mob = $('.mobile-nav')
	,   navid = document.getElementById('nav');

	mob.click(function() {
		var scltop = 0;

		if( document.getElementById('bwrap') !== null ) {
			remove_ovlay();
		} scltop = window.pageYOffset;

		$('body').append(
			'<div id=\"ovlay\">' +
			'<div id=\"bwrap\"></div>' +
			'<div id=\"close\"><i class=\"fa fa-times\"></i></div>' +
			'<div id=\"layer\" style=\"\"><div id=\"nav\">' + ( navid !== null ? navid.innerHTML : '' ) + '</div>' +
			'</div>' );
		var s = document.createElement('style');
		s.id = 'ovlay-style';
		s.innerText =
			'#bwrap{height:' + document.body.clientHeight + 'px;{$brap_rgba};}' +
			'#layer{top:' + scltop + 'px;}'

SCRIPT;
				if( $luxe['global_navi_open_close'] === 'individual' ) {
					$ret .= <<< SCRIPT
		+
		'#layer li[class*=\"children\"] li a::before{content:\"-\";}' +
		'#layer li[class*=\"children\"] a::before,' +
		'#layer li li[class*=\"children\"] > a::before{content:\"\\\\f196\";}' +
		'#layer li li[class*=\"children\"] li a::before{content:\"\\\\0b7\";}'

SCRIPT;
				}
				else {
					$ret .= <<< SCRIPT
		+
		'#layer li[class*=\"children\"] a{padding-left:20px;}' +
		'#layer li ul > li[class*=\"children\"] > a{padding-left:35px;}'

SCRIPT;
				}

				$ret .= <<< SCRIPT
		;
		document.getElementsByTagName('head')[0].appendChild( s );

		$('#layer ul').show();
		$('#layer .mobile-nav').hide();

SCRIPT;
				if( $luxe['global_navi_open_close'] === 'individual' ) {
					$ret .= <<< SCRIPT

		$('#layer ul ul').hide();
		$('#layer ul li[class*=\"children\"] > a').click( function(e) {
			var tgt = $(e.target).parent('li')
			,   tga = tgt.attr('class').match(/item-[0-9]+/)
			,   tgc = tgt.children('ul');

			tgc.toggle( 400, 'linear' );

			if( document.getElementById(tga + '-minus') !== null ) {
				//document.getElementById(tga + '-minus').remove();
				$('#' + tga + '-minus').remove();
			} else {
				$('#ovlay').append(
					'<div id=\"' + tga + '-minus\"><style>' +
					'#layer li[class$=\"' + tga + '\"] > a::before,' +
					'#layer li[class*=\"' + tga + ' \"] > a::before,' +
					'#layer li li[class$=\"' + tga + '\"] > a::before,' +
					'#layer li li[class*=\"' + tga + ' \"] > a::before{content:\"\\\\f147\";}' +
					'</style></div>'
				);
			} e.preventDefault();
		});

SCRIPT;
				}
				$ret .= <<< SCRIPT
		$('#layer').animate( {
			'marginTop' : '0'
		}, 500 );

		$('#bwrap, #close').click( function() {
			$('#layer').animate( {
				//'marginTop' : '-' + document.documentElement.clientHeight + 'px'
				'marginTop' : '-' + document.getElementById('layer').offsetHeight + 'px'
			}, 500);

			setTimeout(function(){
				remove_ovlay();
			}, 550 );
		});

	}).css('cursor','pointer');

SCRIPT;
			}
			else {
				$ret .= <<< SCRIPT

	function no_scroll() {  // スクロール禁止
		// PC
		var sclev = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';
		$(window).on( sclev, function(e) {
			e.preventDefault();
		});
		// スマホ
		$(window).on( 'touchmove.noScroll', function(e) {
			e.preventDefault();
		});
	} function go_scroll() { // スクロール復活 
		// PC
		var sclev = 'onwheel' in document ? 'wheel' : 'onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll';
		$(window).off(sclev);
		// スマホ
		$(window).off('.noScroll');
	}

	// モバイルメニュー ( Luxury 版 )
	var nav = $('#nav')
	,   mom = $('.mob-menu')
	,   mos = $('.mob-side')
	,   prv = $('.mob-prev')
	,   nxt = $('.mob-next')
	,   srh = $('.mob-search')
	,   men = $('.menu ul')
	,   mob = $('.mobile-nav')
	,   prvid = document.getElementById('data-prev')
	,   nxtid = document.getElementById('data-next')
	,   navid = document.getElementById('nav')
	,   mobmn = 'style=\"margin-top:-' + document.documentElement.clientHeight + 'px;\"><div id=\"nav\">' + ( navid !== null ? navid.innerHTML : '' ) + '</div>' +
			'<style>#layer #nav{top:0;}#layer #nav-bottom{border:0;}</style>'
	,   sdbar = ''
	,   sform = '>';

	if( document.getElementById('sidebar') !== null ) {
		sdbar = 'style=\"height:' + document.getElementById('sidebar').offsetHeight + 'px;width:1px\">' +
			'<style>#sidebar{overflow:hidden;background:#fff;padding:1px;border: 3px solid #ddd;border-radius:5px;}#side,div[id*=\"side-\"]{margin:0;padding:0;}</style>'
	}

	// モバイルメニューの動き
	mom.click(function(){
		mobile_menu( 'mom', mobmn );
	}).css('cursor','pointer');

	mos.click(function(){
		mobile_menu( 'mos', sdbar );
	}).css('cursor','pointer');

	srh.click(function(){
		mobile_menu( 'srh', sform );
	}).css('cursor','pointer');

	if( prvid !== null ) {
		prv.click(function(){
			//location.href = $('#data-prev').attr('data-prev');
			location.href = prvid.getAttribute('data-prev');
		}).css('cursor','pointer');
	} else {
		prv.css('opacity', '.4').css('cursor', 'not-allowed');
	} if( nxtid !== null ) {
		nxt.click(function(){
			//location.href = $('#data-next').attr('data-next');
			location.href = nxtid.getAttribute('data-next');
		}).css('cursor','pointer');
	} else {
		nxt.css('opacity', '.4').css('cursor', 'not-allowed');
	} function mobile_menu( cpoint, layer ) {

		if( document.getElementById('bwrap') !== null ) {
			remove_ovlay();
		} var scltop = window.pageYOffset;

		$('body').append(
			'<div id=\"ovlay\">' +
			'<div id=\"bwrap\"></div>' +
			'<div id=\"close\"><i class=\"fa fa-times\"></i></div>' +
			'<div id=\"layer\" ' + layer + '</div>' +
			'</div>' );
		var s = document.createElement('style');
		s.id = 'ovlay-style';
		s.innerText =
			'#bwrap{height:' + document.body.clientHeight + 'px;{$brap_rgba};}' +
			'#layer{top:' + scltop + 'px;}'

SCRIPT;
				if( $luxe['global_navi_open_close'] === 'individual' ) {
					$ret .= <<< SCRIPT
		+
		'#layer li[class*=\"children\"] li a::before{content:\"-\";}' +
		'#layer li[class*=\"children\"] a::before,' +
		'#layer li li[class*=\"children\"] > a::before{content:\"\\\\f196\";}' +
		'#layer li li[class*=\"children\"] li a::before{content:\"\\\\0b7\";}'

SCRIPT;
				}
				else {
					$ret .= <<< SCRIPT
		+
		'#layer li[class*=\"children\"] a{padding-left:20px;}' +
		'#layer li ul > li[class*=\"children\"] > a{padding-left:35px;}'

SCRIPT;
				}

				$ret .= <<< SCRIPT
		;
		document.getElementsByTagName('head')[0].appendChild( s );

		$('#layer ul').show();
		$('#layer .mobile-nav').hide();

		if( cpoint === 'mos') {
			var winwh  = document.documentElement.clientWidth
			,   width  = {$side_1_width}
			,   sarray = {
				'width'    : width + 'px',
				'position' : 'absolute',
				'right'    : winwh + 'px',
				'top'      : window.pageYOffset + 'px',
				'z-index'  : '1100'
			};

			if( width > winwh ) {
				width = winwh - 6;
			} Object.keys( sarray ).forEach( function( index ) {
				var val = this[index];
				$('#sidebar').css( index, val );
			}, sarray );
		}

SCRIPT;
			if( $luxe['global_navi_open_close'] === 'individual' ) {
				$ret .= <<< SCRIPT
		$('#layer ul ul').hide();
		$('#layer ul li[class*=\"children\"] > a').click( function(e) {
			var tgt = $(e.target).parent('li')
			,   tga = tgt.attr('class').match(/item-[0-9]+/)
			,   tgc = tgt.children('ul');

			tgc.toggle( 400, 'linear' );

			if( document.getElementById(tga + '-minus') !== null ) {
				//document.getElementById(tga + '-minus').remove();
				$('#' + tga + '-minus').remove();
			} else {
				$('#ovlay').append(
					'<div id=\"' + tga + '-minus\"><style>' +
					'#layer li[class$=\"' + tga + '\"] > a::before,' +
					'#layer li[class*=\"' + tga + ' \"] > a::before,' +
					'#layer li li[class$=\"' + tga + '\"] > a::before,' +
					'#layer li li[class*=\"' + tga + ' \"] > a::before{content:\"\\\\f147\";}' +
					'</style></div>'
				);
			} e.preventDefault();
		});

SCRIPT;
			}
			$ret .= <<< SCRIPT
		var lay = $('#layer');

		if( cpoint === 'mom' ) {
			lay.animate( {
				'marginTop' : '0'
			}, 500 );
		} else if( cpoint === 'mos' ) {
			$('#sidebar').animate( {
				'right' : '3px'
			}, 500 );
		} else if( cpoint === 'srh' ) {
			$('html, body').scrollTop( 0 );
			no_scroll();
			$('html, body').css('overflow', 'hidden');
			$('#sform').css( 'top', '-100%' );
			$('#sform').show();
			$('#sform').animate( {
				'top' : '80px'
			}, 500 );

			setTimeout(function() {
				$('#sform .search-field').focus();
				$('#sform .search-field').click();
			}, 200 );
		} $('#bwrap, #close').click( function(e) {
			if( cpoint === 'mom') {
				lay.animate( {
					//'marginTop' : '-' + document.documentElement.clientHeight + 'px'
					'marginTop' : '-' + document.getElementById('layer').offsetHeight + 'px'
				}, 500);
			} else if( cpoint === 'mos') {
				$('#sidebar').animate( {
					'marginRight' : '-' + document.documentElement.clientWidth + 'px'
				}, 500 );
			} else if( cpoint === 'srh') {
				$('#sform').animate( {
					'bottom' : '-200%'
				}, 500 );
			} setTimeout(function() {
				if( cpoint === 'srh' ) {
					go_scroll();
					$('html, body').css('overflow', 'auto');
					$('body, html').scrollTop( scltop );
				} remove_ovlay();
			}, 550 );
		});
	}

SCRIPT;
			}
		}

		/* スムーズスクロール */
		if( isset( $this->_depend['sscroll'] ) ) {
			$ret .= "$('a').smoothScroll();\n";
		}

		/* サイドバーの追従スクロール */
		if( isset( $this->_depend['stickykit'] ) ) {
			$offset = '';
			$stick_init_y = 0;

			if( isset( $luxe['head_band_visible'] ) && isset( $luxe['head_band_fixed'] ) ) {
				// 帯メニュー固定時の高さをプラス
				$offset .= "offset += $('.band').height();";
			}

			if( isset( $luxe['global_navi_sticky'] ) && $luxe['global_navi_sticky'] !== 'none' && $luxe['global_navi_sticky'] !== 'smart' ) {
				// グローバルナビ固定時の高さをプラス
				$offset .= "offset += document.getElementById('nav').offsetHeight;";
			}

			$stick_init_y = isset( $luxe['global_navi_scroll_up_sticky'] ) ? 0 : 'offset';

			$ret .= <<< SCRIPT
	var offset  = 0
	,   stkwch  = false
	,   skeep   = $('#side-scroll')
	,   sHeight = 0;

	{$offset}

	if( document.getElementById('wpadminbar') !== null ) {
		offset += document.getElementById('wpadminbar').offsetHeight;
	} function stick_primary( top ) {
		if( skeep.css('max-width') !== '32767px' ) {
			//skeep.stick_in_parent({parent:'#primary',offset_top:top,spacer:0,inner_scrolling:0,recalc_every:1});
			skeep.stick_in_parent({parent:'#primary',offset_top:top,spacer:0,inner_scrolling:0});
		}
	} stick_primary( {$stick_init_y} );

	// 非同期系のブログパーツがあった場合に追従領域がフッターを突き抜けてしまうのでその予防策
	// ＆ 追従領域がコンテンツより下にあった場合にフッターまでスクロールできない現象の対策
	function stick_watch() {
		var i		// setInterval
		,   s		// 現在の #side の高さ
		,   j = 0;	// インターバルのカウンター

		if( document.getElementById('sidebar') !== null ) {
			i = setInterval( function() {
				if( skeep.css('max-width') !== '32767px' ) {
					if( document.getElementById('side') !== null ) {
						if( typeof document.getElementById('side').children[0] !== 'undefined' ) {
							// #side aside の高さ（こっち優先）
							s = document.getElementById('side').children[0].offsetHeight
						} else {
							// #side の高さ
							s = document.getElementById('side').offsetHeight;
						}
					}

					if( s >= sHeight ) {
						sHeight = s;
						document.getElementById('sidebar').style.minHeight=s + 'px';
						stick_primary( {$stick_init_y} );
						//skeep.trigger('sticky_kit:recalc');
					}

					++j;
					if( j >= 100 ) {
						clearInterval( i ); // PC 表示の時に30秒間だけ監視( 300ms * 100 )
					}
				}
			}, 300);
		}
	} if( skeep.css('max-width') !== '32767px' ) {
		stick_watch();
	} var skptim = null	// リサイズイベント負荷軽減用
	,     skprsz = ('resize', function() {
		if( document.getElementById('sidebar') !== null ) {
			if( skptim === null ) {
				skptim = setTimeout( function() {
					sHeight = 0;
					document.getElementById('sidebar').style.minHeight='';
					if( skeep.css('max-width') !== '32767px' ) {
						stick_watch();
					} else {
						skeep.trigger('sticky_kit:detach');
					}
					skptim = null;
				}, 100 );
			}
		}
	});

	// リサイズイベント登録
	if( typeof window.addEventListener !== 'undefined' ) {
		window.addEventListener( 'resize', skprsz, false );
	} else if( typeof window.attachEvent !== 'undefined' ) {
		window.attachEvent( 'onresize', skprsz );
	}

SCRIPT;
		}

		/* グローバルナビTOP固定 */
		if(
			isset( $this->_depend['stickykit'] ) &&
			isset( $luxe['global_navi_sticky'] ) && $luxe['global_navi_sticky'] !== 'none'
		) {
			$nav_sticky = <<< NAV_STICKY
	top = 0;
	if( document.getElementById('wpadminbar') !== null ) {
		top += document.getElementById('wpadminbar').offsetHeight;
	}

NAV_STICKY;
			if( isset( $luxe['head_band_visible'] ) && isset( $luxe['head_band_fixed'] ) ) {
				// 帯メニュー固定時の高さをプラス
				$nav_sticky .= "top += $('.band').height();";
			}

			$nav_sticky .= <<< NAV_STICKY
	thk_unpin( nav );
	mnav = $('.mobile-nav').css('display');
	e = document.getElementById('nav');
	r = ( e !== null ? e.getBoundingClientRect() : '' );
	hidflg = r.top + window.pageYOffset;	// #nav のY座標 (この位置からナビ固定)
	navhid = top - ( e !== null ? e.offsetHeight : 0 ) - 1; // グローバルナビの高さ分マイナス(リサイズイベント用)

NAV_STICKY;

			// グローバルナビを上スクロールの時だけ固定する場合
			if( isset( $luxe['global_navi_scroll_up_sticky'] ) ) {
				$nav_sticky .= <<< NAV_STICKY
	hidflg += ( e !== null ? e.offsetHeight : 0 );	// 上スクロールの時だけ固定する場合は、#nav の bottom 部分を Y座標にする

	if( window.pageYOffset > hidflg ) {
		skeep.trigger('sticky_kit:detach');
		stick_primary( top );
		thk_pin( nav, navhid, '' );
		nav.addClass('pinf');	// pin first の略。最初の一発目の position:fixed 挿入時に上スクロール判定されるために不自然な動きになるのを防ぐ
	}

	stkeve = ('scroll', function(){
		if( resz === false ) {
			if( stktim === null ) {
				stktim = setTimeout( function() {
					p = $('.pin')[0];
					navhid = top - document.getElementById('nav').offsetHeight - 1; // ナビの高さ分マイナス(スクロールイベント用)

					if( window.pageYOffset <= hidflg ) {
						thk_unpin( nav );
					} else if( typeof p === 'undefined' && window.pageYOffset > hidflg ) {
						skeep.trigger('sticky_kit:detach');
						stick_primary( top );
						thk_pin( nav, navhid, '' );
						nav.addClass('pinf');
					} else if( typeof p !== 'undefined' ) {
						var sdscrl = $('#side-scroll')
						,   sdstop = sdscrl.css('top')
						,   difpos = nowpos - window.pageYOffset;

						nowpos = window.pageYOffset;

						if( difpos > 10 ) { // スクロールアップ時にナビ表示
							if( nav.css('top') !== top + 'px' ) {
								if( typeof $('.pinf')[0] === 'undefined' ) {
									thk_pin( nav, top );
									// 追従スクロールの高さ調整
									if( typeof sdscrl[0] !== 'undefined' ) {
										if( sdstop === top + 'px' && skeep.css('max-width') !== '32767px' ) {
											// ナビの transition .25s の後に実行
											skeep.animate({ 'top' : offset + 'px' }, 250 );
											skeep.trigger('sticky_kit:recalc');
											/*
											setTimeout( function(){
												skeep.trigger('sticky_kit:detach');
												stick_primary( offset );
											}, 250 );
											*/
										}
									}
								}
								else {
									nav.removeClass('pinf');
								}
							}
						} else if( difpos < -60 ) { // スクロールダウンでナビを画面上に隠す
							if( nav.css('top') !== navhid + 'px' ) { // !== navhid だとカクッとなるので条件厳しく
								thk_pin( nav, navhid );
								// 追従スクロールの高さ調整
								if( typeof sdscrl[0] !== 'undefined' ) {
									if( sdstop !==  top + 'px' && skeep.css('max-width') !== '32767px' ) {
										skeep.animate({ 'top' : top + 'px' }, 250 );
										/*
										skeep.trigger('sticky_kit:detach');
										stick_primary( top );
										*/
									}
								}
							}
						}
					} else if( typeof p === 'undefined' ) {
						if( nav.css('top') !== navhid + 'px' ) {
							if( skeep.css('max-width') !== '32767px' ) {
								skeep.animate({ 'top' : top + 'px' }, 250 );
								/*
								skeep.trigger('sticky_kit:detach');
								stick_primary( top );
								*/
							}
							thk_pin( nav, navhid );
						}
					}
					stktim = null;
				}, stkint );
			}
		}
	});

NAV_STICKY;
			}
			// グローバルナビを常に固定する場合
			else {
				$nav_sticky .= <<< NAV_STICKY
	if( window.pageYOffset > hidflg ) {
		thk_pin( nav, top, '' );
	}

	if( resz === false ) {
		stkeve = ('scroll', function(){
			if( stktim === null ) {
				stktim = setTimeout( function() {
					p = $('.pin')[0];

					if( window.pageYOffset <= hidflg ) {
						thk_unpin( nav );
					} else if( typeof p === 'undefined' ) {
						thk_pin( nav, top, '' );
					}

					stktim = null;
				}, 100 );
			}
		});
	}

NAV_STICKY;
			}

			$block_if_else = "
				{$nav_sticky};
				if( mob.css( 'display' ) !== 'none' ) {
					listen_scroll( stkeve );
				} else {
					thk_unpin( nav );
					detach_scroll( stkeve );
				}
			\n";

			$stick = '';

			if( $luxe['global_navi_sticky'] === 'smart' ) {
				$stick .= $block_if_else;
			} elseif( $luxe['global_navi_sticky'] === 'pc' ) {
				$stick .= str_replace( "!== 'none'", "=== 'none'", $block_if_else );
			} else {
				$stick .= $nav_sticky . 'listen_scroll( stkeve );';
			}

			$ret .= <<< SCRIPT
	var e, r, p
	,   top = 0
	,   navhid = 0
	,   mnav
	,   hidflg
	,   resz = false	// リサイズイベントかどうかの判別
	,   nowpos = 0		// スクロール位置
	,   stktim = null	// スクロールイベント負荷軽減用
	,   stkint = 250	// インターバル(PC では少し速く:100、モバイルでは少し遅く:250)
	,   stkeve;

	function thk_nav_stick() {
		{$stick}
	}

	thk_nav_stick();

	$(window).resize( function() {
		resz = true;
		if( skeep.css('max-width') !== '32767px' ) {
			stkint = 100;
		} else {
			stkint = 250;
		}
		thk_nav_stick();
		resz = false;
	});

	function thk_pin( o, sp, trs, cls ) {
		if( typeof trs === 'undefined' ) trs = 'all .25s ease-in-out';
		if( typeof cls === 'undefined' ) cls = 'pin';
		o.css({
			'transition': trs,
			'top': sp + 'px',
			'position': 'fixed',
			'width': o.width() + 'px'
		});
		o.addClass( cls );
	} function thk_unpin( o ) {
		/* o.css({ 'transition': '', 'top': sp + '', 'position': '' }); */
		o.removeAttr('style');
		o.removeClass('pin');
	}

SCRIPT;
		}

		if( $luxe['gallery_type'] === 'tosrus' && $is_preview === false ) {
			/* Tosrus */
			$ret .= <<< SCRIPT
	$("a[data-rel^=tosrus]").tosrus({
		caption : {
			add : true,
			attributes : ["data-title","title", "alt", "rel"]
		},
		pagination : {
			add : true,
		},
		infinite : true,
		wrapper : {
			onClick: "close"
		}
	});

SCRIPT;
		}

		if( $luxe['gallery_type'] === 'lightcase' && $is_preview === false ) {
			/* Lightcase */
			$ret .= "$('a[data-rel^=lightcase]').lightcase();\n";
		}

		if( $luxe['gallery_type'] === 'fluidbox' && $is_preview === false ) {
			/* Fluidbox */
			$ret .= "$(function () {;\n";
			$ret .= "$('.post a[data-fluidbox]').fluidbox();;\n";
			$ret .= "});;\n";
		}

		if( isset( $luxe['head_band_search'] ) ) {
			/* 帯メニュー検索ボックスのサイズと色の変更 */
			$ret .= <<< SCRIPT
	var subm = $('#head-search button[type=submit]')
	,   text = $('#head-search input[type=text]')
	,   menu = $('.band-menu ul');

	if( text.css('display') != 'block' ) {
		text.click( function() {
			subm.css('color','#bbb');
			menu.css('right','210px');
			text.css('width','200px');
			text.css('color','#000');
			text.css('background-color','rgba(255, 255, 255, 1.0)');
			text.prop('placeholder','');

		});
		text.blur( function() {
			subm.removeAttr('style');
			menu.removeAttr('style');
			text.removeAttr('style');
			text.prop('placeholder','Search …');
		});
	}

SCRIPT;
		}

		//if( $luxe['awesome_load'] !== 'none' ) {
		if( get_theme_mod('awesome_load') !== 'none' ) {
			/* placeholder にアイコンフォントを直接書くと、Nu Checker で Warning 出るので、jQuery で置換 */
			$before = 'placeholder=\"';
			$after  = 'placeholder=\"&#xf002; ';
			$ret .= "if( typeof $('#search label')[0] !== 'undefined' ) {;\n";
			$ret .= "$('.search-field').replaceWith($('#search label').html().replace('{$before}','{$after}'));\n";
			$ret .= "};\n";
		}

		if( isset( $luxe['autocomplete'] ) ) {
			/* 検索ボックスのオートコンプリート (Google Autocomplete) */
			$ret .= <<< SCRIPT
	var checkReady1 = function(callback) {
		if( window.jQuery ) {
			callback(jQuery);
		} else {
			window.setTimeout( function() {
				checkReady1(callback);
			}, 100 );
		}
	};

	checkReady1( function($) {
		jQuery( function($) {
			$('.search-field, .head-search-field').autocomplete({
				source: function(request, response){
					$.ajax({
						url: "//www.google.com/complete/search",
						data: {
							hl: 'ja',
							ie: 'utf_8',
							oe: 'utf_8',
							client: 'firefox', // For XML: use toolbar, For JSON: use firefox, For JSONP: use firefox
							q: request.term
						},
						dataType: "jsonp",
						type: "GET",
						success: function(data) {
							response(data[1]);
						}
					});
				},
				delay: 300
			});
		});
	});

SCRIPT;
		}

		if( isset( $this->_depend['autosize'] ) ) {
			/* コメント欄の textarea 自動伸縮 */
			$ret .= "autosize($('textarea#comment'));\n";
		}

		$site = array();
		$wt = $ca->thk_id();
		$wt_selecter  = "$('#" . $wt . "')";
		$wta_selecter = "$('#" . $wt . " a')";
		$foot_prefix  = '#wp-';
		$wt_array  = $ca->thk_hex_array();
		$wt_txt  = array();
		$ins_func = $ca->ins_luxe();
		$csstext_array = $ca->csstext_imp();
		$site_array = $ca->thk_site_name();

		$css_txt  = 'cssText';
		$wt_txt[] = THK_COPY;

		if( strlen( $wt ) === 3 ) {
			if( $wt[2] !== 'k' )     $broken = true;
			elseif( $wt[1] !== 'h' ) $broken = true;
			elseif( $wt[0] !== 't' ) $broken = true;
		}
		else {
			$broken = true;
		}

		foreach( $wt_array as $val ) $wt_txt[] = $ca->hex_2_bin( $val );
		if(
			( is_array( $wt_txt ) && count( $wt_txt ) >= 5  ) && (
				stripos( $wt_txt[0], 'http' )  === false ||
				stripos( $wt_txt[1], 'style' ) === false ||
				stripos( $wt_txt[2], 'luxeritas' ) === false
			)
		) $broken = true;

		foreach( $site_array as $val ) $site[] = $ca->hex_2_bin( $val );
		if( is_array( $site ) && count( $site ) >= 4 && stripos( $site[0], 'luxeritas' ) === false ) {
			$broken = true;
		}

		foreach( $csstext_array as $key => $val ) {
			$csstext[] = $ca->hex_2_bin( $val );
			if( stripos( $csstext[$key], '!;' ) === false ) {
				$broken = true;
			}
			else {
				$csstext[$key] = str_replace( '!;', $imp_close, $csstext[$key] );
			}
		}

		$ret .= <<< SCRIPT
	var cint = false
	,   c = thk_get_yuv()
	,   i = '{$csstext[0]}'
	,   b = '{$csstext[1]}'
	,   l = '{$csstext[2]}color:' + c[0] + '{$imp_close}'
	,   s = document.createElement('style');

	(function fcheck() {
		var href = window.location.href
		,   luxe  = {$wt_selecter}
		,   luxea = {$wta_selecter}
		,   fe = document.getElementById('{$site[2]}')
		,   ft = ( fe !== null ? fe.children : '' )
		,   ff = false
		,   fc = false
		,   fi = 0;

		for( fi = 0; fi < ft.length; fi++ ){
			if( ft[fi].tagName.toLowerCase() !== '{$site[2]}' ) ft[fi].parentNode.removeChild(ft[fi]);
		} fe = document.getElementsByTagName('{$site[2]}'); ft = ( typeof fe[0] !== 'undefined' ? fe[0].children : '' );
		for( fi = 0; fi < ft.length; fi++ ){
			if( ft[fi].id.toLowerCase() !== '{$site[4]}' && ft[fi].id.toLowerCase() !== '{$site[3]}' ) ft[fi].parentNode.removeChild(ft[fi]);
		} ft = document.body.children;
		for( fi = 0; fi < ft.length; fi++ ) {
			if( ft[fi].id.toLowerCase() === '{$site[2]}' ) fc = true; continue;
		} if( fc === true ) {
			for( fi = 0; fi < ft.length; fi++ ) {
				if( ft[fi].id.toLowerCase() === '{$site[2]}' ) {
					ff = true; continue;
				} if( ff === true ) {
					if( '#' + ft[fi].id.toLowerCase() !== '{$foot_prefix}{$site[2]}' ) ft[fi].parentNode.removeChild(ft[fi]);
					if( '#' + ft[fi].id.toLowerCase() === '{$foot_prefix}{$site[2]}' ) break;
				}
			}
		} else {
			for( fi = 0; fi < ft.length; fi++ ) {
				if( ft[fi].className.toLowerCase() === 'container' ) {
					ff = true; continue;
				} if( ff === true ) {
					if( '#' + ft[fi].id.toLowerCase() !== '{$foot_prefix}{$site[2]}' ) ft[fi].parentNode.removeChild(ft[fi]);
					if( '#' + ft[fi].id.toLowerCase() === '{$foot_prefix}{$site[2]}' ) break;
				}
			}
		} if( href.indexOf("/embed/") < 1 ){
			setInterval( function() {
				if( document.getElementById('{$wt}') !== null ) {
					var luxhtml = document.getElementById('{$wt}').innerHTML;
					if( luxhtml.indexOf('{$site[0]}') != -1 && luxhtml.indexOf('{$site[1]}') != -1 ) {
						if( document.getElementById('{$wt}').parentNode.getAttribute('id') === '{$site[3]}' ) {
							luxe.css({'{$css_txt}': b + l });
							luxea.css({'{$css_txt}': i + l });
						} else {
							{$ins_func};
						}
					} else {
						{$ins_func};
					}
				} else {
					{$ins_func};
				} if( document.getElementById('{$site[2]}') === null || document.getElementsByTagName('{$site[2]}').length <= 0 || $('#{$site[2]}').css('display') == 'none' || $('{$site[2]}').css('display') == 'none' ) {
					{$ins_func};
				}
			}, 1000 );
		}
	 }()); function {$ins_func} {
		if( cint === false ) {
			var txt = '{$wt_txt[1]}';
			if( document.getElementById('{$site[3]}') !== null ) {
				var rep = document.getElementById('{$site[3]}').innerHTML;
				txt = txt.replace('><', '>' + rep + '<');
				//document.getElementById('{$site[3]}').remove();
				$('#{$site[3]}').remove();
			} $('body').append( txt + b  + l + '{$wt_txt[2]}{$wt_txt[0]}{$wt_txt[3]}' + i  + l + '{$wt_txt[4]}' );
			cint = true;
		}
	} function thk_get_yuv() {
		var yuv  = 255
		,   chek = null
		,   rgba = ''
		,   flg1 = 'rgba(0, 0, 0, 0)'
		,   flg2 = 'transparent'
		,   flg3 = 'none'
		,   bcss = 'background-color'
		,   bgc0 = $('body').css(bcss)
		,   bgc1 = $('#{$site[2]}').css(bcss)
		,   bgc2 = $('{$site[2]}').css(bcss)
		,   bgc3 = $('#{$site[3]}').css(bcss);

		if( bgc3 != flg1 && bgc3 != flg2 && bgc3 != flg3 ) {
			chek = bgc3;
		} else if( bgc2 != flg1 && bgc2 != flg2 && bgc2 != flg3 ) {
			chek = bgc2;
		} else if( bgc1 != flg1 && bgc1 != flg2 && bgc1 != flg3 ) {
			chek = bgc1;
		} else {
			chek = bgc0;
		} if( chek != flg1 && chek != flg2 && chek != flg3 ) {
			if( typeof( chek ) != 'undefined' ){
				rgba = chek.split(',');
			}
		} else {
			rgba = ['255','255','255','0'];
		} if( rgba.length >= 3 ) {
			rgba[0] = rgba[0].replace( /rgba\(/g, "" ).replace( /rgb\(/g, "" );
			rgba[1] = rgba[1].replace( / /g, "" );
			rgba[2] = rgba[2].replace( /\)/g, "" ).replace( / /g, "" );
			yuv = 0.299 * rgba[0] + 0.587 * rgba[1] + 0.114 * rgba[2];
		}

		return yuv >= 128 ? ['black', 'white'] : ['white', 'black'];
	}
	s.id = '{$wt}c';
	s.innerText = '{$imp}color:' + c[0] + '{$imp_close}}';
	document.getElementsByTagName('head')[0].appendChild( s );
});

/* IE8以下、Firefox2 以下で getElementsByClassName 使えない時用 */
if( typeof( document.getElementsByClassName ) == 'undefined' ){
	document.getElementsByClassName = function(t){
		var elems = new Array();
		if( document.all ) {
			var allelem = document.all;
		} else {
			var allelem = document.getElementsByTagName("*");
		} for( var i = j = 0, l = allelem.length; i < l; i++ ) {
			var names = allelem[i].className.split( /\s/ );
			for( var k = names.length - 1; k >= 0; k-- ) {
				if( names[k] === t ) {
					elems[j] = allelem[i];
					j++;
					break;
				}
			}
		}
		return elems;
	};
}

SCRIPT;

		if( $broken !== false ) {
			if( is_admin() === true ) {
				add_settings_error( 'luxe-custom', '', __( 'This theme is broken.', 'luxeritas' ) );
				return false;
			}
			else {
				wp_die( __( 'This theme is broken.', 'luxeritas' ) );
			}
		}
		return $ret;
	}

	/*
	------------------------------------
	 SNS のカウント数読み込み
	------------------------------------ */
	public function create_sns_count_script( $is_preview = false ) {
		global $luxe;
		$ret = '';
		$ajaxurl = admin_url( 'admin-ajax.php' );

		$ret .= <<< SCRIPT
function get_sns_count( id, sns_class ) {
	var ele = document.getElementsByClassName("sns-count-true")
	,   permalink = ele[0].getAttribute("data-luxe-permalink")
	,   query = window.location.search;
	jQuery.ajax({
		type: 'GET',
		url: '{$ajaxurl}',
		data: {action:'thk_sns_real', sns:id, url:permalink},
		dataType: 'text',
		async: true,
		cache: false,
		timeout: 10000,
		success: function( data ) {
			if( isFinite( data ) && data !== '' ) {
				jQuery( sns_class ).text( data );
			} else if( typeof( data ) === 'string' && data !== '' ) {
				var str = data.slice( 0, 11 );
				jQuery( sns_class ).text( str );
			} else if( query.indexOf('preview=true') !== -1 ) {
				jQuery( sns_class ).text( 0 );
			} else {
				jQuery( sns_class ).text( '!' );
			}
		},
		error: function() {
			jQuery( sns_class ).text( '!' );
		}
	});
}

window.onload = function(){
	if( document.getElementsByClassName("sns-count-true").length > 0 && document.getElementsByClassName("sns-cache-true").length < 1 ) {
		var LUXE_SNS_LIST = {
		        'f': '.facebook-count',
		        'g': '.google-count',
		        'h': '.hatena-count',
		        'l': '.linkedin-count',
		        'p': '.pocket-count'
		};
		Object.keys( LUXE_SNS_LIST ).forEach( function( index ) {
			var val = this[index];
			get_sns_count( index, val );
		}, LUXE_SNS_LIST );
	}

	if( document.getElementsByClassName("feed-count-true").length > 0 && document.getElementsByClassName("feed-cache-true").length < 1 ) {
		get_sns_count( 'r', '.feedly-count' );
	}
}

SCRIPT;
		return $ret;
	}
}
