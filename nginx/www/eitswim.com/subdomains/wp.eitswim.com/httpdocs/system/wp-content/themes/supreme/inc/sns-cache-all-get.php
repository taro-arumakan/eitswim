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

class sns_cache_all_get {
	private $_sns;

	public function __construct() {
	}

	public function sns_cache_list() {
		$args = array(
			'posts_per_page'	=> -1,		// 全件
			'post_type'		=> array( 'post', 'page'),
			'orderby'		=> 'ID',
			'order'			=> 'ASC',
			'post_status'		=> 'publish'
		);

		$query = new WP_Query( $args );
		$this->_sns = new sns_cache();

		$post_count = $query->post_count + 1; // トップページの分 + 1
		$progress = round( 100 / $post_count, 8 );

		if( $query->have_posts() ) {
			// 取得処理開始メッセージ
			add_action( 'shutdown', function() {
?>
<script>
var snsLog = jQuery('#get-sns-log');
var snsItm = jQuery('#post-items');
var snsPrg = jQuery('#progress');
snsLog.append( '<?php echo __( 'Processing started.', 'luxeritas' ), "\\n---\\n"; ?>' );</script>
<?php
				$this->flash();
			}, 96 );

			$i = 1;
			$p = $progress;
			$url = '';
			$complete = array();

			// トップページ取得
			add_action( 'shutdown', function() use( $i, $p, $post_count ) {
				$url = home_url('/');
				$this->_sns->touch_sns_count_cache( esc_url( $url ) );
				$this->_sns->create_sns_cache( esc_url( $url ) );
				$this->_sns->create_feedly_cache(); // Feedly
?>
<script>
snsItm.text( '<?php echo $i, " / ", $post_count; ?>' );
snsPrg.css( 'width', '<?php echo $p; ?>%' );
snsLog.append( '<?php echo $url, "\\n"; ?>' );
snsLog.scrollTop( snsLog[0].scrollHeight );
</script>
<?php
				$this->flash();
				sleep( 1 );
			}, 97 );

			// 投稿・固定ページ取得
			while( $query->have_posts() ) {
				$query->the_post();
				$url = get_permalink();

				$p += $progress;
				++$i;

				if( isset( $complete[$url] ) ) continue;

				add_action( 'shutdown', function() use( $url, $i, $p, $post_count ) {
					$limit_func = 'set_' . 'time_' . 'limit';
					$limit_func(0);

					$this->_sns->touch_sns_count_cache( esc_url( $url ) );
					$this->_sns->create_sns_cache( esc_url( $url ) );
?>
<script>
snsItm.text( '<?php echo $i, " / ", $post_count; ?>' );
snsPrg.css( 'width', '<?php echo $p; ?>%' );
snsLog.append( '<?php echo $url, "\\n"; ?>' );
snsLog.scrollTop( snsLog[0].scrollHeight );
</script>
<?php
					$this->flash();
					sleep( 1 );
				}, 98 );

				$complete[$url] = true;
			}

			// 取得処理完了メッセージ
			add_action( 'shutdown', function() use( $post_count ) {
?>
<script>
snsItm.text( '<?php echo $post_count, " / ", $post_count; ?>' );
snsPrg.css( 'width', '100%' );
snsPrg.css( 'background', '#b3d39b' );
snsLog.append( '<?php echo "---\\n", __( 'Processing is completed', 'luxeritas' ), "\\n"; ?>' );
snsLog.scrollTop( snsLog[0].scrollHeight );
</script>
<?php
			}, 99 );
		}
	}

	private function flash() {
		if( ob_get_level() < 1 || ob_get_length() === false ) ob_start();
		if( ob_get_length() !== false ) {
		       	ob_flush();
		       	flush();
			ob_end_flush();
		}
	}
}
