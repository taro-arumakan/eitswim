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
 * QRコード
 *---------------------------------------------------------------------------*/
if( class_exists( 'thk_qr_code' ) === false ):
class thk_qr_code extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'widget-qr', 'description' => __( 'QR Code', 'luxeritas' ) );
		parent::__construct( 'qr', '#6 ' . __( 'QR Code', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')', $widget_ops );
	}

	public function widget( $args, $instance ) {
		$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$width_height = isset( $instance['size'] ) ? (int)$instance['size'] : 250;
		$description = ( isset( $instance['description'] ) ) ? esc_attr( $instance['description'] ) : __( 'QR Code', 'luxeritas' );
		$format = '<img src="//chart.apis.google.com/chart?chs=%1$dx%1$d&amp;cht=qr&amp;chld=%2$s|2&amp;chl=%3$s" width="%1$d" height="%1$d" alt="QR Code | ' . get_bloginfo('name') . '" />';

		echo $args['before_widget'];
		if( !empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
		echo sprintf( $format, $width_height, 'L', rawurlencode( home_url('/') ) );
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$new_instance	   = wp_parse_args( (array)$new_instance, array( 'size' => 250 ) );
		$instance['size']  = (int)$new_instance['size'];
		return $instance;
	}

	public function form( $instance ) {
		$title	  = isset( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : __( 'QR Code', 'luxeritas' );
		$instance = wp_parse_args( (array)$instance, array( 'posturl' => '', 'size' => 250 ) );
		$posturl  = $instance['posturl'] ? 'checked="checked"' : '';
		$size	  = isset( $instance['size'] ) ? (int)$instance['size'] : 250;
		$description = isset( $instance['description'] ) ? esc_attr( $instance['description'] ) : __( 'QR Code', 'luxeritas' );
?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:', 'luxeritas' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
<p><label for="<?php echo $this->get_field_id('size'); ?>"><?php echo __( 'Size of QR Code', 'luxeritas' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" type="text" value="<?php echo $size; ?>" /></p>
<?php
	}
}
endif;

/*---------------------------------------------------------------------------
 * コメント一覧 (THK オリジナル)
 *---------------------------------------------------------------------------*/
if( class_exists( 'thk_recent_comments' ) === false ):
class thk_recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'thk_recent_comments', 'description' => __( 'Recent Comments', 'luxeritas' ) );
		parent::__construct( 'thk_recent_comments', '#3 ' . __( 'Recent Comments', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')', $widget_ops );
	}

	public function widget( $args, $instance ) {
		global $comments, $comment;

		$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( !empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if( empty( $number ) ) $number = 5;

		$comments = get_comments( apply_filters( 'widget_comments_args', array(
			'number' => $number,		// 表示件数
			'orderby' => 'comment_date',	// コメント日付でソート
			'order' => 'desc',		// 降順ソート
			'status' => 'approve',		// 承認されたものだけ
			'post_status' => 'publish'	// 公開済みの記事だけ
		) ) );

		echo $args['before_widget'];
		if( !empty( $title ) ) echo $args['before_title'], $title, $args['after_title'];
?>
<ul id="thk-rcomments">
<?php
		if( is_array( $comments ) === true ) {
			$title_length = '36';	// 表示する記事タイトルの最大文字数
			$auth_length  = '26';

			foreach( (array)$comments as $comment ) {
				$excerpt = $this->com_excerpt( $comment->comment_content );

				if( strlen( get_the_title( $comment->comment_post_ID ) ) >= $title_length ) {
					$post_title = mb_strimwidth( get_the_title( $comment->comment_post_ID ), 0, $title_length ) . "...";
				}
				else {
					$post_title = get_the_title( $comment->comment_post_ID );
				}

				$comment_author = empty( $comment->comment_author ) ? __( 'Anonymous', 'luxeritas' ) : $comment->comment_author;
				if( strlen( $comment->comment_author ) >= $auth_length ) {
					$comment_author = mb_strimwidth( $comment_author, 0, $auth_length ) . "...";
				}

				$suffix = get_locale() === 'ja' ? ' さん' : '';
				if( $comment->comment_author_url ) {
					$author_link = '<a href="' . $comment->comment_author_url . '"';
					if( strpos( $comment->comment_author_url, home_url() ) !== false ) {
						$author_link .= ' class="url">' . $comment_author . '</a>';
					}
					else {
						$author_link .= ' rel="external nofollow" class="url">' . $comment_author . '</a>' . $suffix;
					}
				}
				else {
					$author_link = $comment_author . $suffix;
				}
?>
<li class="recentcomments">
<div class="widget_comment_author">
<?php
				$avatar_id = ( !empty( $comment->user_id ) ) ? $comment->user_id : $comment->comment_author_email;
				echo str_replace( array( 'http:', 'https:' ), '', str_replace( "'", '"', get_avatar( $avatar_id, 32, 'mm', $comment_author ) ) );

				// コメント位置までまでスクロールしたくない場合は、
				// get_comment_link($comment->comment_ID) やめて get_permalink($comment->comment_post_ID) にする
?>
<span class="comment_date"><?php echo get_comment_date( __( 'F j, Y', 'luxeritas' ) ); ?></span>
<span class="author_link"><?php echo $author_link; ?></span>
</div>
<div class="comment_excerpt"><i class="fa fa-comment-o fa-fw"></i><?php echo $excerpt; ?></div>
<span class="comment_post"><i class="fa fa-angle-double-right fa-fw"></i><a href="<?php echo get_comment_link($comment->comment_ID); ?>"><?php echo $post_title; ?></a></span>
</li>
<?php
			}
 		}
?>
</ul>
<?php
		echo $args['after_widget'];
	}

	private function com_excerpt( $comment ) {
		$length = 60;
		$excerpt = strip_tags( trim( $comment) );
		$excerpt = str_replace( array("\r", "\n"), '', $excerpt );
		$excerpt = str_replace( "\t", '', $excerpt );
		$excerpt = mb_substr( $excerpt, 0, $length );
		$excerpt .= $length > 0 && mb_strlen( $excerpt ) >= $length ? ' ...' : '';

		return $excerpt;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']  = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = absint( $new_instance['number'] );

		return $instance;
	}

	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'The latest comments to all posts', 'luxeritas' );
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'luxeritas' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo __( 'Number of comments to show:', 'luxeritas' ); ?></label>
<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
endif;

/*---------------------------------------------------------------------------
 * 新着記事 (THK オリジナル)
 *---------------------------------------------------------------------------*/
if( class_exists( 'thk_recent_posts' ) === false ):
class thk_recent_posts extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'thk_recent_posts', 'description' => __( 'Recent posts', 'luxeritas' ) );
		parent::__construct( 'thk_recent_posts', '#2 ' . __( 'Recent posts', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')', $widget_ops );
	}

	public function widget( $args, $instance ) {
		$title = !empty( $instance['title'] ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = !empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		if( empty( $number ) ) $number = 5;

		$thumbnail = isset( $instance['thumbnail'] ) ? $instance['thumbnail'] : 0;

		echo $args['before_widget'];
		if( !empty( $title ) ) echo $args['before_title'], $title, $args['after_title'];
?>
<div id="thk-new">
<?php
		$arr = array( 'posts_per_page' => $number );
		$st_query = new WP_Query( $arr );

		if( $st_query->have_posts() === true ) {
			while( $st_query->have_posts() === true ) {
				$st_query->the_post();
?>
<div class="toc clearfix">
<?php if( empty( $thumbnail ) ): ?>
<div class="term"><a href="<?php the_permalink() ?>"><?php
				$post_thumbnail = has_post_thumbnail();
				if( !empty( $post_thumbnail ) ):	// サムネイル
					the_post_thumbnail( 'thumb100' );
				else:
?><img src="<?php echo get_template_directory_uri(); ?>/images/no-img-100x100.png" alt="No Image" title="No Image" width="100" height="100" /><?php
				endif;
?></a>
</div>
<?php endif; ?>
<div class="excerpt"<?php if( !empty( $thumbnail ) ) echo ' style="padding:0 10px"'; ?>>
<p class="new-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
<p><?php
echo apply_filters( 'thk_excerpt_no_break', 40 );
?></p>
</div>
</div>
<?php
			}
		}
		else {
?>
<p><?php echo __( 'No new posts', 'luxeritas' ); ?></p>
<?php
		}
		wp_reset_postdata();
?>
</div>
<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']	= sanitize_text_field( $new_instance['title'] );
		$instance['number']	= absint( $new_instance['number'] );
		$instance['thumbnail']	= $new_instance['thumbnail'];

		return $instance;
	}

	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Recent posts', 'luxeritas' );
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$thumbnail = isset( $instance['thumbnail'] ) ? $instance['thumbnail'] : 0;
?>
<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'luxeritas' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo __( 'Number of posts to show:', 'luxeritas' ); ?></label>
<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<p><input class="checkbox" id="<?php echo $this->get_field_id( 'thumbnail' ); ?>" name="<?php echo $this->get_field_name( 'thumbnail' ); ?>" type="checkbox" value="1" <?php checked( $thumbnail, 1 ); ?> />
<label for="<?php echo $this->get_field_id( 'thumbnail' ); ?>"><?php echo __( 'No Thumbnail', 'luxeritas' ); ?></label></p>
<?php
	}
}
endif;

/*---------------------------------------------------------------------------
 * SNS フォローボタン
 *---------------------------------------------------------------------------*/
if( class_exists( 'thk_follow_button' ) === false ):
class thk_follow_button extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'thk_follow_button', 'description' => __( 'SNS Follow Button', 'luxeritas' ) );
		parent::__construct( 'thk_follow_button', '#4 ' . __( 'SNS Follow Button', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')', $widget_ops );
	}

	public function widget( $args, $instance ) {
		$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if( !empty( $title ) ) echo $args['before_title'], $title, $args['after_title'];

		if(
			isset( $instance['twitter'] )	||
			isset( $instance['facebook'] )	||
			isset( $instance['hatena'] )	||
			isset( $instance['google'] )	||
			isset( $instance['youtube'] )	||
			isset( $instance['line'] )	||
			isset( $instance['rss'] )	||
			isset( $instance['feedly'] )
		) {
?>
<div id="thk-follow">
<ul>
<?php
	if( isset( $instance['twitter'] ) ):
?><li><span class="snsf twitter"><a href="//twitter.com/<?php echo isset($instance['twitter_id']) ? rawurlencode( rawurldecode( $instance['twitter_id'] ) ) : ''; ?>" target="blank" title="Twitter" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="fa fa-twitter"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">Twitter</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['facebook'] ) ):
?><li><span class="snsf facebook"><a href="//www.facebook.com/<?php echo isset($instance['facebook_id']) ? rawurlencode( rawurldecode( $instance['facebook_id'] ) ) : ''; ?>" target="blank" title="Facebook" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="fa fa-facebook"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">Facebook</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['hatena'] ) ):
?><li><span class="snsf hatena"><a href="//b.hatena.ne.jp/<?php echo isset($instance['hatena_id']) ? rawurlencode( rawurldecode( $instance['hatena_id'] ) ) : ''; ?>" target="blank" title="<?php echo __( 'Hatena Bookmark', 'luxeritas' ); ?>" rel="nofollow" itemprop="sameAs url">B!<?php if( $instance['icon'] ) echo '<span class="fname">Hatena</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['google'] ) ):
?><li><span class="snsf google"><a href="//plus.google.com/<?php echo isset($instance['google_id']) ? rawurlencode( rawurldecode( $instance['google_id'] ) ) : ''; ?>" target="blank" title="Google+" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="fa fa-google-plus"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">Google+</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['youtube'] ) ):
		$youtube_id = '';
		$youtube_type = 'channel/';
		if( !empty( $instance['youtube_c_id'] ) ) {
			$youtube_id = rawurlencode( rawurldecode( $instance['youtube_c_id'] ) );
		}
		elseif( !empty( $instance['youtube_id'] ) ) {
			$youtube_id = rawurlencode( rawurldecode( $instance['youtube_id'] ) );
			$youtube_type = 'user/';
		}
?><li><span class="snsf youtube"><a href="//www.youtube.com/<?php echo $youtube_type, $youtube_id; ?>" target="blank" title="YouTube" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="fa fa-youtube"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">YouTube</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['line'] ) ):
?><li><span class="snsf line"><a href="//line.naver.jp/ti/p/<?php echo isset($instance['line_id']) ? rawurlencode( rawurldecode( $instance['line_id'] ) ) : ''; ?>" target="blank" title="LINE" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="fa ico-line"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">LINE</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['rss'] ) ):
?><li><span class="snsf rss"><a href="<?php echo get_bloginfo('rss2_url'); ?>" target="_blank" title="RSS" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="fa fa-rss"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">RSS</span>'; ?></a></span></li>
<?php
	endif;
	if( isset( $instance['feedly'] ) ):
?><li><span class="snsf feedly"><a href="//feedly.com/index.html#subscription/feed/<?php echo rawurlencode( get_bloginfo('rss2_url') ); ?>" target="blank" title="Feedly" rel="nofollow" itemprop="sameAs url">&nbsp;<i class="ico-feedly"></i>&nbsp;<?php if( $instance['icon'] ) echo '<span class="fname">Feedly</span>'; ?></a></span></li>
<?php
	endif;
?></ul>
<div class="clearfix"></div>
</div>
<?php
		}
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']	= sanitize_text_field( $new_instance['title'] );
		$instance['icon']	= $new_instance['icon'];

		$sns_list = array(
			'twitter',
			'facebook',
			'hatena',
			'google',
			'youtube_c',
			'youtube',
			'line',
		);

		foreach( $sns_list as $val ) {
			$instance[$val]	= $new_instance[$val];
			$instance[$val . '_id']	= $new_instance[$val . '_id'];
		}

		$instance['rss']	= $new_instance['rss'];
		$instance['feedly']	= $new_instance['feedly'];

		return $instance;
	}

	public function form( $instance ) {
		$title		= isset( $instance['title'] )		? esc_attr( $instance['title'] )	: __( 'Follow me!', 'luxeritas' );
		$twitter_id	= isset( $instance['twitter_id'] )	? esc_attr( $instance['twitter_id'] )	: '';
		$facebook_id	= isset( $instance['facebook_id'] )	? esc_attr( $instance['facebook_id'] )	: '';
		$hatena_id	= isset( $instance['hatena_id'] )	? esc_attr( $instance['hatena_id'] )	: '';
		$google_id	= isset( $instance['google_id'] )	? esc_attr( $instance['google_id'] )	: '';
		$youtube_c_id	= isset( $instance['youtube_c_id'] )	? esc_attr( $instance['youtube_c_id'] )	: '';
		$youtube_id	= isset( $instance['youtube_id'] )	? esc_attr( $instance['youtube_id'] )	: '';
		$line_id	= isset( $instance['line_id'] )		? esc_attr( $instance['line_id'] )	: '';

		$sns_list = array(
			'twitter'	=> array(
				'Twitter',
				$twitter_id,
				'Twitter ID ( http://twitter.com/XXXXX )'
			),
			'facebook'	=> array(
				'Facebook',
				$facebook_id,
				'Facebook ID ( http://www.facebook.com/XXXXX )'
			),
			'hatena'	=> array(
				__( 'Hatena Bookmark', 'luxeritas' ),
				$hatena_id,
				'Hatena ID ( http://b.hatena.ne.jp/XXXXX )'
			),
			'google'	=> array(
				'Google+',
				$google_id,
				'Google+ ID ( http://plus.google.com/XXXXX )'
			),
			'youtube'	=> array(
				'YouTube',
				array( $youtube_c_id, $youtube_id ),
				array(
					'YouTube ID ( http://www.youtube.com/channel/XXXXX )',
					'YouTube old ID ( http://www.youtube.com/user/XXXXX )'
				)
			),
			'line'		=> array(
				'LINE',
				$line_id,
				'LINE ID ( http://line.naver.jp/ti/p/XXXXX )'
			),
		);

		if( empty( $instance ) ) {
			$instance['rss']	= 1;
			$instance['feedly']	= 1;
		}

?>
<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'luxeritas' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

<p><?php echo __( 'Button appearance:', 'luxeritas' ); ?><br />
<input name="<?php echo $this->get_field_name( 'icon' ); ?>" type="radio" value="0" <?php echo !isset( $instance['icon'] ) || !$instance['icon'] ? 'checked="checked"' : ''; ?> />
<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php echo __( 'Icon only', 'luxeritas' ); ?></label><br />
<input name="<?php echo $this->get_field_name( 'icon' ); ?>" type="radio" value="1"  <?php echo isset( $instance['icon'] ) && $instance['icon'] ? 'checked="checked"' : ''; ?> />
<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php echo __( 'Icon + SNS name', 'luxeritas' ); ?></label>
</p>
<?php
		$p_style = 'background:#f5f5f5; padding:3% 5%';
		$i_style = 'width:90%';

		foreach( $sns_list as $key => $val ) {
?>
<p><input class="checkbox" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="checkbox" value="1" <?php checked( isset( $instance[$key] ) ? $instance[$key] : 0 ); ?> />
<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $val[0], ' ', __( 'follow button display', 'luxeritas' ); ?></label></p>
<?php
			if( $key === 'youtube' ) {
?>
<p style="<?php echo $p_style; ?>"><?php echo $val[2][0]; ?>:<br />
<input class="widefat" style="<?php echo $i_style; ?>" id="<?php echo $this->get_field_id( $key . '_c_id' ); ?>" name="<?php echo $this->get_field_name( $key . '_c_id' ); ?>" type="text" value="<?php echo $val[1][0]; ?>" /></p>
<p style="<?php echo $p_style; ?>"><?php echo $val[2][1]; ?>:<br />
<input class="widefat" style="<?php echo $i_style; ?>" id="<?php echo $this->get_field_id( $key . '_id' ); ?>" name="<?php echo $this->get_field_name( $key . '_id' ); ?>" type="text" value="<?php echo $val[1][1]; ?>" /></p>
<?php
			}
			else {
?>
<p style="<?php echo $p_style; ?>"><?php echo $val[2]; ?>:<br />
<input class="widefat" style="<?php echo $i_style; ?>" id="<?php echo $this->get_field_id( $key . '_id' ); ?>" name="<?php echo $this->get_field_name( $key . '_id' ); ?>" type="text" value="<?php echo $val[1]; ?>" /></p>
<?php
			}
		}
?>
<p><input class="checkbox" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="checkbox" value="1" <?php checked( isset( $instance['rss'] ) ? $instance['rss'] : 0 ); ?> />
<label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php echo sprintf( __( '%s button display', 'luxeritas' ), 'RSS' ); ?></label></p>

<p><input class="checkbox" id="<?php echo $this->get_field_id( 'feedly' ); ?>" name="<?php echo $this->get_field_name( 'feedly' ); ?>" type="checkbox" value="1" <?php checked( isset( $instance['feedly'] ) ? $instance['feedly'] : 0 ); ?> />
<label for="<?php echo $this->get_field_id( 'feedly' ); ?>"><?php echo sprintf( __( '%s button display', 'luxeritas' ), 'Feedly' ); ?></label></p>
<?php
	}
}
endif;

/*---------------------------------------------------------------------------
 * RSS / Feedly 購読ボタン
 *---------------------------------------------------------------------------*/
if( class_exists( 'thk_rss_feedly' ) === false ):
class thk_rss_feedly extends WP_Widget {
	public function __construct() {
		$widget_ops = array( 'classname' => 'thk_rss_feedly', 'description' => __( 'RSS / Feedly Button', 'luxeritas' ) );
		parent::__construct( 'thk_rss_feedly', '#5 ' . __( 'RSS / Feedly Button', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')', $widget_ops );
	}

	public function widget( $args, $instance ) {
		$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];
		if( !empty( $title ) ) echo $args['before_title'], $title, $args['after_title'];
?>
<div id="thk-rss-feedly">
<ul>
<li><a href="<?php echo get_bloginfo('rss2_url'); ?>" class="icon-rss-button" target="_blank" title="RSS" rel="nofollow"><i class="fa fa-rss"></i><span>RSS</span></a></li>
<li><a href="//feedly.com/index.html#subscription/feed/<?php echo rawurlencode( get_bloginfo('rss2_url') ); ?>" class="icon-feedly-button" target="blank" title="feedly" rel="nofollow"><i class="ico-feedly"></i><span>Feedly</span></a></li>
</ul>
<div class="clearfix"></div>
</div>
<?php
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? sanitize_text_field( $instance['title'] ) : __( 'Subscribe to this blog', 'luxeritas' );
?>
<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'luxeritas' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
<?php
	}
}
endif;

/*---------------------------------------------------------------------------
 * Adsense Widget (主にアドセンスだが、なんでも OK)
 *---------------------------------------------------------------------------*/
if( class_exists( 'thk_adsense_widget' ) === false ):
class thk_adsense_widget extends WP_Widget {
	private $_pages		= array();
	private $_adsense	= array();
	private $_label		= array();

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'thk_adsense_widget',
			'description' => __( 'It&#x27;s mainly for adsense, but you can write whatever you like text, HTML etc.', 'luxeritas' ) .
					 __( 'This widget can be switched between &quot;show / hide&quot; on the specified page.', 'luxeritas' )
		);
		parent::__construct( 'thk_adsense_widget', '#1 ' . __( 'Adsense Widget', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')', $widget_ops );

		if( is_admin() === true ) {
			$this->_pages = array(
				'preview'   =>	__( 'Preview page', 'luxeritas' ),
				'customize' =>	__( 'Customize preview page', 'luxeritas' ),
				'is404'     =>	__( '404 Not Found page', 'luxeritas' ),
				'search'    =>	__( 'Search result page', 'luxeritas' ),
				'archive'   =>	__( 'Archive', 'luxeritas' ),
				'category'  =>	__( 'Category', 'luxeritas' ),
				'page'      =>	__( 'Static page', 'luxeritas' ),
				'single'    =>	__( 'Post page', 'luxeritas' ),
				'top'       =>	__( 'Top page', 'luxeritas' ),
				'specified' =>	__( 'Specified page', 'luxeritas' ) . ' ( ' . __( 'Specify post IDs separated by commas or line feeds', 'luxeritas' ) . ' )'
			);
			$this->_adsense = array(
				'none'		=>	__( 'Not specified', 'luxeritas' ),
				'ad-250-250'	=>	'250x250px ( Square )',
				'ad-300-250'	=>	'300x250px ( Rectangle )',
				'ad-336-280'	=>	'336x280px ( Rectangle big )',
				'ad-120-600'	=>	'120x600px ( Skyscraper )',
				'ad-160-600'	=>	'160x600px ( Wide skyscraper )',
				'ad-300-600'	=>	'300x600px ( Large skyscraper )',
				'ad-468-60'	=>	'468x60px  ( Banner )',
				'ad-728-90'	=>	'728x90px  ( Big banner )',
				'ad-970-90'	=>	'970x90px  ( Large big banner )',
				'ad-970-250'	=>	'970x250px ( Billboard )',
				'ad-320-100'	=>	'320x100px ( Mobile banner )',
				'ad-336-280-col-2'	=>	'336x280px ( Rectangle big ' . __( 'Dual Ad horizon', 'luxeritas' ) . ' )',
				'ad-336-280-row-2'	=>	'336x280px ( Rectangle big ' . __( 'Dual Ad vertical', 'luxeritas' ) . ' )',
				'ad-300-250-col-2'	=>	'300x250px ( Rectangle ' . __( 'Dual Ad horizon', 'luxeritas' ) . ' )',
				'ad-300-250-row-2'	=>	'300x250px ( Rectangle ' . __( 'Dual Ad vertical', 'luxeritas' ) . ' )',
			);
			$this->_label = array(
				'none'			=>	__( 'None', 'luxeritas' ),
				'sponsored-links'	=>	__( 'Sponsored Links', 'luxeritas' ),
				'advertisement'		=>	__( 'Advertisement', 'luxeritas' ),
			);
		}
	}

	public function widget( $args, $instance ) {
		$is_front_page = is_front_page();
		$is_category   = is_category();
		if(
			( isset( $instance['preview'] )   && is_preview() === true ) ||
			( isset( $instance['customize'] ) && is_customize_preview() === true ) ||
			( isset( $instance['is404'] )     && is_404() === true ) ||
			( isset( $instance['search'] )    && is_search() === true ) ||
			( isset( $instance['archive'] )   && is_archive() === true && $is_category === false ) ||
			( isset( $instance['category'] )  && $is_category === true ) ||
			( isset( $instance['page'] )      && is_page() === true && $is_front_page === false ) ||
			( isset( $instance['single'] )    && is_single() === true ) ||
			( isset( $instance['top'] )       && ( is_home() === true || $is_front_page === true ) )
		) {
			return;
		}

		if( isset( $instance['specified'] ) && !empty( $instance['ids'] ) ) {
			$specifieds = array();
			$arr = explode( ',', $instance['ids'] );

			foreach( (array)$arr as $value ) {
				$specifieds = array_merge( $specifieds, explode( "\n", $value ) );
			}

			foreach( (array)$specifieds as $val ) {
				if( (int)$val === get_the_ID() ) {
					return;
				}
			}
		}

		$title = ( !empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$headline = '';
		$about = '';
		$label = '';
		$args['before_widget'] = str_replace( 'thk_adsense_', 'thk_ps_', $args['before_widget'] );

		if( isset( $instance['center'] ) ) {
			$args['before_widget'] = str_replace( 'class="widget ', 'class="widget al-c ', $args['before_widget'] );
		}
		if( isset( $instance['wpadblock'] ) ) {
			$args['before_widget'] = str_replace( '<div', '<div itemscope itemtype="https://schema.org/WPAdBlock"', $args['before_widget'] );
			$headline = ' itemprop="headline name"';
			$about = ' itemprop="about"';
		}

		echo $args['before_widget'];

		if( isset( $instance['aside'] ) ) {
			echo '<aside>';
		}
		if( !empty( $title ) ) echo $args['before_title'], $title, $args['after_title'];

		$wrap = '<div class="ps-wrap">';
		if( isset( $instance['center'] ) ) {
			$wrap = '<div class="ps-wrap al-c">';
		}

		if( isset( $instance['label'] ) && $instance['label'] !== 'none' ) {
			$center = isset( $instance['label-c'] ) ? ' al-c' : '';

			if( $instance['label'] === 'advertisement' ) {
				$label = '<p' . $headline . ' class="ps-label' . $center . '">' . __( 'Advertisement', 'luxeritas' ) . '</p>';
			}
			else {
				$label = '<p' . $headline . ' class="ps-label' . $center . '">' . __( 'Sponsored Links', 'luxeritas' ) . '</p>';
			}
		}

		if( isset( $instance['adsense'] ) )  {
			if( $instance['adsense'] === 'ad-250-250' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-250-250', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget rectangle ps-250-250"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-300-250' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-300-250', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget rectangle ps-300-250"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-336-280' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-336-280', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget rectangle ps-336-280"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-120-600' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="vertical"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-120-600', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-120-600"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-160-600' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="vertical"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-160-600', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-160-600"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-300-600' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="vertical"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-300-600', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-300-600"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-468-60' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="horizontal"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-468-60', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-468-60"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-728-90' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="horizontal"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-728-90', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-728-90"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-970-90' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="horizontal"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-970-90', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-970-90"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-970-250' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="horizontal"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-970-250', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-970-250"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-320-100' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="horizontal"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-320-100', $wrap ), $label;
				?><div<?php echo $about; ?> class="ps-widget ps-320-100"><?php echo $instance['text']; ?></div><?php
			}
			elseif( $instance['adsense'] === 'ad-336-280-col-2' ) {
				if( !empty( $about ) ) echo '<div' . $about . '>';
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-col', $wrap ), $label;
				?><div class="ps-widget rectangle-1-col ps-336-280"><?php echo $instance['text']; ?></div>
				<div class="ps-widget rectangle-2-col ps-336-280"><?php echo $instance['text']; ?></div><?php
				if( !empty( $about ) ) echo '</div>';
			}
			elseif( $instance['adsense'] === 'ad-336-280-row-2' ) {
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				if( !empty( $about ) ) echo '<div' . $about . '>';
				echo str_replace( 'ps-wrap', 'ps-wrap ps-336-280', $wrap ), $label;
				?><div class="ps-widget rectangle-1-row ps-336-280"><?php echo $instance['text']; ?></div><br />
				<div class="ps-widget rectangle-2-row ps-336-280"><?php echo $instance['text']; ?></div><?php
				if( !empty( $about ) ) echo '</div>';
			}
			elseif( $instance['adsense'] === 'ad-300-250-col-2' ) {
				if( !empty( $about ) ) echo '<div' . $about . '>';
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-col', $wrap ), $label;
				?><div class="ps-widget rectangle-1-col ps-300-250"><?php echo $instance['text']; ?></div>
				<div class="ps-widget rectangle-2-col ps-300-250"><?php echo $instance['text']; ?></div><?php
				if( !empty( $about ) ) echo '</div>';
			}
			elseif( $instance['adsense'] === 'ad-300-250-row-2' ) {
				if( !empty( $about ) ) echo '<div' . $about . '>';
				$instance['text'] = preg_replace( '/data-ad-format=[\"|\'][a-z]+?[\"|\']/', 'data-ad-format="rectangle"', $instance['text'] );
				echo str_replace( 'ps-wrap', 'ps-wrap ps-300-250', $wrap ), $label;
				?><div class="ps-widget rectangle-1-row ps-300-250"><?php echo $instance['text']; ?></div><br />
				<div class="ps-widget rectangle-2-row ps-300-250"><?php echo $instance['text']; ?></div><?php
				if( !empty( $about ) ) echo '</div>';
			}
			else {
				echo $wrap, $label;
				?><div<?php echo $about; ?> class="ps-widget"><?php echo $instance['text']; ?></div><?php
			}
		}
		echo '</div>';

		if( isset( $instance['aside'] ) ) {
			echo '</aside>';
		}
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']	= isset( $new_instance['title'] )	? sanitize_text_field( $new_instance['title'] ) : null;
		$instance['title']	= sanitize_text_field( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = isset( $new_instance['text'] )	? $new_instance['text']		: null;
		} else {
			$instance['text'] = isset( $new_instance['text'] )	? wp_kses_post( $new_instance['text'] ) : null;
		}
		$instance['adsense']	= isset( $new_instance['adsense'] )	? $new_instance['adsense']	: null;
		$instance['center']	= isset( $new_instance['center'] )	? $new_instance['center']	: null;
		$instance['label']	= isset( $new_instance['label'] )	? $new_instance['label']	: null;
		$instance['label-c']	= isset( $new_instance['label-c'] )	? $new_instance['label-c']	: null;
		$instance['wpadblock']	= isset( $new_instance['wpadblock'] )	? $new_instance['wpadblock']	: null;
		$instance['aside']	= isset( $new_instance['aside'] )	? $new_instance['aside']	: null;
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['ids'] = isset( $new_instance['ids'] )	? $new_instance['ids']	: null;
		} else {
			$instance['ids'] = isset( $new_instance['ids'] )	? wp_kses_post( $new_instance['ids'] ) : null;
		}

		foreach( $this->_pages as $key => $val ) {
			$instance[$key]  = isset( $new_instance[$key] )		? $new_instance[$key]	: null;
		}

		return $instance;
	}

	public function form( $instance ) {
		$title  = isset( $instance['title'] )	? sanitize_text_field( $instance['title'] ) : '';
		$text   = isset( $instance['text'] )	? $instance['text']	: '';
		$adsense= isset( $instance['adsense'] ) ? $instance['adsense']	: 'none';
		$label	= isset( $instance['label'] )	? $instance['label']	: 'none';
		$ids	= isset( $instance['ids'] )	? sanitize_text_field( $instance['ids'] )   : '';

		if( empty( $instance ) ) {
			$instance['preview']	= 1;
			$instance['customize']	= 1;
			$instance['is404']	= 1;
			$instance['specified']	= 1;
		}
?>
<p><label style="font-weight:bold" for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:', 'luxeritas' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<p><label style="font-weight:bold" for="<?php echo $this->get_field_id('text'); ?>"><?php echo __( 'Content:', 'luxeritas' ); ?></label>
<textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
<p><?php echo __( '* Google Adsense please paste responsive ads.', 'luxeritas' ); ?></p>

<hr style="margin:20px 0 0 0" />
<p style="font-weight:bold"><?php echo __( 'Adsense Size', 'luxeritas' ); ?>:</p>
<?php
		foreach( $this->_adsense as $key => $val ) {
?>
<p style="margin:5px 0"><input class="radio" id="<?php echo $this->get_field_name('adsense'); ?>" name="<?php echo $this->get_field_name('adsense'); ?>" type="radio" value="<?php echo $key; ?>" <?php checked( $key === $adsense ? 1 : 0 ); ?> />
<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $val; ?></label></p>
<?php
			if( $key === 'ad-320-100' ) {
				echo '<hr style="margin:10px 0 0 0; border-top:none; border-bottom:1px dotted #ddd;" />';
			}
		}
?>
<p style="margin:15px 0"><input class="checkbox" id="<?php echo $this->get_field_name('center'); ?>" name="<?php echo $this->get_field_name('center'); ?>" type="checkbox" value="1" <?php checked( isset( $instance['center'] ) ? $instance['center'] : 0 ); ?> />
<label for="<?php echo $this->get_field_id('center'); ?>"><?php echo __( 'Align center', 'luxeritas' ); ?></label></p>

<hr style="margin:20px 0 0 0" />
<p style="font-weight:bold"><?php echo __( 'Label', 'luxeritas' ); ?>:</p>
<?php
		foreach( $this->_label as $key => $val ) {
?>
<p style="margin:5px 0"><input class="radio" id="<?php echo $this->get_field_name('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" type="radio" value="<?php echo $key; ?>" <?php checked( $key === $label ? 1 : 0 ); ?> />
<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $val; ?></label></p>
<?php
		}
?>
<p style="margin:15px 0"><input class="checkbox" id="<?php echo $this->get_field_name('label-c'); ?>" name="<?php echo $this->get_field_name('label-c'); ?>" type="checkbox" value="1" <?php checked( isset( $instance['label-c'] ) ? $instance['label-c'] : 0 ); ?> />
<label for="<?php echo $this->get_field_id('label-c'); ?>"><?php echo __( 'Center the label', 'luxeritas' ); ?></label></p>

<hr style="margin:20px 0 0 0" />
<p style="font-weight:bold"><?php echo __( 'Structured data', 'luxeritas' ); ?>:</p>
<p style="margin:5px 0"><input class="checkbox" id="<?php echo $this->get_field_name('wpadblock'); ?>" name="<?php echo $this->get_field_name('wpadblock'); ?>" type="checkbox" value="1" <?php checked( isset( $instance['wpadblock'] ) ? $instance['wpadblock'] : 0 ); ?> />
<label for="<?php echo $this->get_field_id('wpadblock'); ?>"><?php echo __( 'Add structured data representing advertisements', 'luxeritas' ); ?></label></p>
<p style="margin:5px 0"><input class="checkbox" id="<?php echo $this->get_field_name('aside'); ?>" name="<?php echo $this->get_field_name('aside'); ?>" type="checkbox" value="1" <?php checked( isset( $instance['aside'] ) ? $instance['aside'] : 0 ); ?> />
<label for="<?php echo $this->get_field_id('aside'); ?>"><?php echo __( 'Surrounded by &lt;aside&gt; tag', 'luxeritas' ); ?></label></p>

<hr style="margin:20px 0 0 0" />
<p style="font-weight:bold"><?php echo __( 'Pages not to display this widget:', 'luxeritas' ); ?></p>
<?php
		foreach( $this->_pages as $key => $val ) {
?>
<p style="margin:5px 0"><input class="checkbox" id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" type="checkbox" value="1" <?php checked( isset( $instance[$key] ) ? $instance[$key] : 0 ); ?> />
<label for="<?php echo $this->get_field_id( $key ); ?>"><?php echo $val; ?></label></p>
<?php
		}
?>
<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('ids'); ?>" name="<?php echo $this->get_field_name('ids'); ?>"><?php echo $ids; ?></textarea>
<div style="margin-bottom:30px;"></div>
<?php
	}
}
endif;

/*---------------------------------------------------------------------------
 * カテゴリ・アーカイブウィジェットのaタグをリストの内側にする
 *---------------------------------------------------------------------------*/
if( function_exists('thk_list_categories_archives') === false ):
function thk_list_categories_archives( $out ) {
	$out = str_replace( '&nbsp;', ' ', $out );
	$out = str_replace( "\t", '', $out);
	$out = str_replace( "'", '"', $out );
	//$out = preg_replace( '/>\s*?<\//', '></', $out );
	//$out = str_replace( "\n</li>", "</li>\n", $out );
	$out = preg_replace( '/<\/a> \(([0-9]*)\)/', ' <span class="count_view">(${1})</span></a>', $out );
	return $out;
}
endif;

/*---------------------------------------------------------------------------
 * ウィジェットの WAF 対策
 *---------------------------------------------------------------------------*/
if( is_admin() === true && !empty( $_POST ) ) {
	if( current_user_can( 'edit_theme_options' ) === true ) {
		$widget_name = '';
		$referer = wp_get_raw_referer();
		if( stripos( (string)$referer, 'wp-admin/widgets.php' ) !== false ) {
			switch( true ) {
				case isset( $_POST['widget-thk_adsense_widget'] ):
					$widget_name = 'widget-thk_adsense_widget';
					break;
				case isset( $_POST['widget-text'] ):
					$widget_name = 'widget-text';
					break;
				default:
					break;
			}
		}
		if( !empty( $widget_name ) ) {
			// $_POST を暗号化
			add_action( 'after_setup_theme', function() use( $widget_name ) {
				if( isset( $_POST[$widget_name] ) ) {
					$func = 'base' . '64' . '_encode';
					$_POST['f'] = $func( $widget_name );
					$_POST['w'] = $func( serialize( $_POST[$widget_name] ) );
					$_POST['i'] = $func( serialize( $_POST['widget-id'] ) );
					$_POST['b'] = $func( serialize( $_POST['id_base'] ) );
					unset(
						$_POST[$widget_name],
						$_POST['widget-id'],
						$_POST['id_base']
					);
				}
			});
			// $_POST を復号化
			add_action( 'update_option', function() use( $widget_name ) {
				if( isset( $_POST['f'] ) && isset( $_POST['w'] ) && isset( $_POST['i'] ) && isset( $_POST['b'] ) ) {
					$func = 'base' . '64' . '_decode';
					if( $func( $_POST['f'] ) === $widget_name ) {
						$_POST[$widget_name] = unserialize( $func( $_POST['w'] ) );
						$_POST['widget-id'] = unserialize( $func( $_POST['i'] ) );
						$_POST['id_base'] = unserialize( $func( $_POST['b'] ) );
					}
				}
			});
		}
		unset( $widget_name, $referer );
	}
}

if( function_exists('custom_widgets_init') === false ):
/*---------------------------------------------------------------------------
 * widgets init
 *---------------------------------------------------------------------------*/
add_action( 'widgets_init', function() {
	// recentcomments のインライン消す (style.css に一応書いとく)
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

	register_sidebars( 1, array(
		'id'		=> 'side-h3',
		'name'		=> __( 'General-purpose sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H3' ) . ')',
		'description'	=> sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h3' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3 class="side-title">',
		'after_title'	=> '</h3>'
	));
	register_sidebars( 1, array(
		'id'		=> 'side-h4',
		'name'		=> __( 'General-purpose sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H4' ) . ')',
		'description'	=> sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h4' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="side-title">',
		'after_title'	=> '</h4>'
	));
	register_sidebars( 1, array(
		'id'		=> 'side-top-h3',
		'name'		=> __( 'Front page sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H3' ) . ')',
		'description'	=> __( 'Front page dedicated.', 'luxeritas' ) . sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h3' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3 class="side-title">',
		'after_title'	=> '</h3>'
	));
	register_sidebars( 1, array(
		'id'		=> 'side-top-h4',
		'name'		=> __( 'Front page sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H4' ) . ')',
		'description'	=> __( 'Front page dedicated.', 'luxeritas' ) . sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h4' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="side-title">',
		'after_title'	=> '</h4>'
	));
	register_sidebars( 1, array(
		'id'		=> 'side-no-top-h3',
		'name'		=> __( 'Other than the front page sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H3' ) . ')',
		'description'	=> __( 'Pages other than the front page.', 'luxeritas' ) . sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h3' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3 class="side-title">',
		'after_title'	=> '</h3>'
	));
	register_sidebars( 1, array(
		'id'		=> 'side-no-top-h4',
		'name'		=> __( 'Other than the front page sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H4' ) . ')',
		'description'	=> __( 'Pages other than the front page.', 'luxeritas' ) . sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h4' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="side-title">',
		'after_title'	=> '</h4>'
	));
	register_sidebars( 1, array(
		'id'		=> 'side-scroll',
		'name'		=> __( 'Scroll follow sidebar (H4 type)', 'luxeritas' ),
		'description'	=> __( 'Widget to follow the scroll. The title is only h4.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="side-title">',
		'after_title'	=> '</h4>'
	));

	register_sidebars( 1, array(
		'id'		=> 'side-amp',
		'name'		=> __( 'AMP sidebar (H4 type)', 'luxeritas' ),
		'description'	=> __( 'AMP dedicated. The title is only h4.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="side-title">',
		'after_title'	=> '</h4>'
	));

	register_sidebars( 1, array(
		'id'		=> 'head-under',
		'name'		=> __( 'Under Header Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget just below the header.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget head-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="head-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'head-under-amp',
		'name'		=> __( 'Under Header Widget', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget just below the header.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget head-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="head-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-title-upper',
		'name'		=> __( 'Above Post Title Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget above the post title.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget post-title-upper %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="post-title-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-title-upper-amp',
		'name'		=> __( 'Above Post Title Widget', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget above the post title.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget post-title-upper %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="post-title-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-title-under',
		'name'		=> __( 'Under Post Title Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget under the post title.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget post-title-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="post-title-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-title-under-amp',
		'name'		=> __( 'Under Post Title Widget', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget under the post title.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget post-title-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="post-title-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-h2-upper',
		'name'		=> __( 'Above the first H2 tag in the post', 'luxeritas' ),
		'description'	=> __( 'Place the widget above first H2 tag in the post.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget post-h2-title %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="post-h2-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-h2-upper-amp',
		'name'		=> __( 'Above the first H2 tag in the post', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget above first H2 tag in the post.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget post-h2-title %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="post-h2-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'related-upper',
		'name'		=> __( 'Above Related Posts Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget above the related posts.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget related-upper %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="related-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'related-upper-amp',
		'name'		=> __( 'Above Related Posts Widget', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget above the related posts.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget related-upper %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="related-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'related-under',
		'name'		=> __( 'Under Related Posts Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget under the related posts.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget related-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="related-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'related-under-amp',
		'name'		=> __( 'Under Related Posts Widget', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget under the related posts.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget related-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="related-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'posts-list-upper',
		'name'		=> __( 'Above Posts List Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget above the posts list.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-list-upper %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-list-upper-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'posts-list-middle',
		'name'		=> __( 'Middle of Posts List Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget middle of the posts list.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-list-middle %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-list-middle-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'posts-list-under',
		'name'		=> __( 'Under Posts List Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget under the posts list.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-list-under %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-list-under-title">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'footer-left',
		'name'		=> __( 'Footer left', 'luxeritas' ) . ' (' . __( 'Title H4', 'luxeritas' ) . ')',
		'description'	=> __( 'When the sidebar is offscreen, it will be not shown.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="footer-left-title">',
		'after_title'	=> '</h4>'
	));

	register_sidebars( 1, array(
		'id'		=> 'footer-center',
		'name'		=> __( 'Footer center', 'luxeritas' ) . ' (' . __( 'Title H4', 'luxeritas' ) . ')',
		'description'	=> __( 'When the sidebar is offscreen, it will be not shown.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="footer-center-title">',
		'after_title'	=> '</h4>'
	));

	register_sidebars( 1, array(
		'id'		=> 'footer-right',
		'name'		=> __( 'Footer right', 'luxeritas' ) . ' (' . __( 'Title H4', 'luxeritas' ) . ')',
		'description'	=> __( 'When the sidebar is offscreen, it will be not shown.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="footer-right-title">',
		'after_title'	=> '</h4>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-under-1',
		'name'		=> __( 'Under Post Widget', 'luxeritas' ),
		'description'	=> __( 'Place the widget under the post.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-under-1 %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-under-1">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-under-1-amp',
		'name'		=> __( 'Under Post Widget', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget under the post.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-under-1 %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-under-1">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-under-2',
		'name'		=> __( 'Further below &quot;Under Post Widget&quot;', 'luxeritas' ),
		'description'	=> __( 'Place the widget further below &quot;Under Post Widget&quot;.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-under-2 %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-under-2">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'post-under-2-amp',
		'name'		=> __( 'Further below &quot;Under Post Widget&quot;', 'luxeritas' ) . '( ' . __( 'for AMP', 'luxeritas' ) . ' )',
		'description'	=> __( 'Place the widget further below &quot;Under Post Widget&quot;.', 'luxeritas' ),
		'before_widget'	=> '<div id="%1$s" class="widget posts-under-2 %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<p class="posts-under-2-amp">',
		'after_title'	=> '</p>'
	));

	register_sidebars( 1, array(
		'id'		=> 'col3-h3',
		'name'		=> __( '3 Column Left Sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H3' ) . ')',
		'description'	=> sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h3' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h3 class="side-title">',
		'after_title'	=> '</h3>'
	));
	register_sidebars( 1, array(
		'id'		=> 'col3-h4',
		'name'		=> __( '3 Column Left Sidebar', 'luxeritas' ) . ' (' . sprintf( __( 'title %s type', 'luxeritas' ), 'H4' ) . ')',
		'description'	=> sprintf( __( 'If you want the title to have %s tag.', 'luxeritas' ), 'h4' ),
		'before_widget'	=> '<div id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</div>',
		'before_title'	=> '<h4 class="side-title">',
		'after_title'	=> '</h4>'
	));

	register_widget( 'thk_adsense_widget' );
	register_widget( 'thk_qr_code' );
	register_widget( 'thk_recent_comments' );
	register_widget( 'thk_recent_posts' );
	register_widget( 'thk_follow_button' );
	register_widget( 'thk_rss_feedly' );
} );
endif;
