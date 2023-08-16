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

if( class_exists( 'getSnsCount' ) === false ):
class getSnsCount {
	private $_ret = '!';
	private $_args = array(
		'timeout'	=> 10,
		'compress'	=> true,
		'sslverify'	=> false
	);

	public function __construct() {
	}

	/* facebook count */
	public function facebookCount( $url ) {
		/*
		//$share = wp_remote_get( 'https://graph.facebook.com/?id=' . $url . '', $_args );
		$share = wp_remote_get( 'http://api.facebook.com/method/fql.query?format=json&query=select+total%5Fcount+from+link%5Fstat+where+url%3D%22'. urlencode($url). '%22', $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				//$this->_ret = @json_decode( $share['body'] )->shares - 0;
				$jsn = @json_decode( $share['body'], true );
				$this->_ret = isset( $jsn[0]['total_count'] ) ? $jsn[0]['total_count'] : 0;
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		*/
		$appid = get_theme_mod( 'sns_fb_appid', null );

		$share = wp_remote_get( 'http://www.facebook.com/plugins/like.php?href=' . $url . '&layout=button_count&action=like&show_faces=false&share=false&appId=' . $appid, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				// パターンその1
				preg_match( '/class="pluginCountTextDisconnected">(\d+)<\/span>/imx', $share['body'], $match );
				if( isset( $match[1] ) ) {
					$this->_ret = $match[1];
				}
				else {
					// パターンその2
					preg_match( '/>(\d+)<\/span><\/div><\/button>/imx', $share['body'], $match );
					$this->_ret = isset( $match[1] ) ? $match[1] : '!';
				}
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* google plus count */
	public function googleCount( $url ) {
		$share = wp_remote_get( 'https://apis.google.com/_/+1/fastbutton?url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 ) {
				if( is_array( $share ) ) preg_match( '/\[2,([0-9.]+),\[/', $share['body'], $sahre_cnt );
				if( !isset( $sahre_cnt[1] ) ) {
					$this->_ret = 0;
				}
				else {
					$this->_ret = $sahre_cnt[1];
				}
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* hatena count */
	public function hatenaCount( $url ) {
		$share = wp_remote_get( 'http://api.b.st-hatena.com/entry.count?url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && !empty( $share['body'] ) ) {
				$this->_ret = $share['body'];
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
			else {
				$this->_ret = 0;
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* linkedin count */
	public function linkedinCount( $url ) {
		$share = wp_remote_get( 'https://www.linkedin.com/countserv/count/share?format=json&url=' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				$this->_ret = @json_decode( $share['body'] )->count;
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* pocket count */
	public function pocketCount( $url ) {
		$share = wp_remote_get( 'https://widgets.getpocket.com/v1/button?v=1&count=horizontal&url=' . $url .'&src=https', $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 ) {
				if( is_array( $share ) ) preg_match( '/<em id="cnt">([0-9.]+)<\/em>/i', $share['body'], $sahre_cnt );
				if( !isset( $sahre_cnt[1] ) ) {
					$this->_ret = 0;
				}
				else {
					$this->_ret = $sahre_cnt[1];
				}
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}

	/* feedly count */
	public function feedlyCount( $url ) {
		$share = wp_remote_get( 'http://cloud.feedly.com/v3/feeds/feed%2F' . $url, $this->_args );
		if( !is_wp_error( $share ) ) {
			if( $share['response']['code'] === 200 && isset( $share['body'] ) ) {
				$this->_ret = @json_decode( $share['body'] )->subscribers;
				if( empty( $this->_ret ) ) $this->_ret = 0;
			}
			elseif( $share['response']['code'] !== 200 ) {
				return $share['response']['message'];
			}
		}
		if( !is_numeric( $this->_ret ) ) $this->_ret = '!';
		return $this->_ret;
	}
}
endif;
