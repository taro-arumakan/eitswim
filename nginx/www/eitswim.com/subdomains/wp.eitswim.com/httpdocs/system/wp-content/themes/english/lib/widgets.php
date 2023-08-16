<?php
/**
 * サイドバー、ウィジェットの関数
 * Created by PhpStorm.
 * User: elc
 * Date: 2017/07/08
 */

/**
 * Register widget area.
 * ここで ウィジェット画面に表示する項目を設定する
 */
if( function_exists('custom_widgets_init') === false ):
  function custom_widgets_init() {
    //$name = get_template();
    register_sidebar( array(
      'name'          => esc_html__( 'メインスライダー設定', 'custom' ),
      'id'            => 'main_visual',
      'description'   => esc_html__( 'スライダー画像登録', 'custom' ),
      'before_widget' => '',
      'after_widget'  => '',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );

    /**
    register_sidebar( array(
      'name'          => esc_html__( 'BLOG記事用', 'custom' ),
      'id'            => 'sidebar_single',
      'description'   => esc_html__( 'BLOG記事右カラム', 'custom' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
      'name'          => esc_html__( '検索結果用', 'custom' ),
      'id'            => 'sidebar_search',
      'description'   => esc_html__( '検索結果右カラム', 'custom' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
      'name'          => esc_html__( '法務系ページ用', 'custom' ),
      'id'            => 'sidebar_page',
      'description'   => esc_html__( '法務系ページ右カラム', 'custom' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
      'name'          => esc_html__( '記事バナー', 'custom' ),
      'id'            => 'sidebar_banner1',
      'description'   => esc_html__( '記事ページ用バナー', 'custom' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );
     **/

  }
  add_action( 'widgets_init', 'custom_widgets_init' );
endif;

/**
 * 以下 カスタムWidget用
 */
if( function_exists('custom_widget') === false ):
  function custom_widget() {

    /**
     * タグ一覧用
     * Class custom_Widget_Side_Menu
     */
    class custom_Widget_Side_Menu extends WP_Widget {

      function __construct() {
        parent::WP_Widget(false, $name = 'Tag Cloud');
      }

      function widget( $args, $instance ) {
        $title = $instance['title'];
        if(empty($title)) $title = 'Tags';

        echo <<< EOS
        <!--widget tags -->
            <div class="widget  widget-tags">
              <h4 class="widget-title">{$title}</h4>
            <div class="tags">
EOS;
        $tags = get_tags();
        if ($tags) {
          foreach($tags as $tag) {
            $link = get_tag_link($tag->term_id);
            echo <<< EOS
              <a href="{$link}">{$tag->name}</a>
EOS;
          }
        }
        echo <<< EOS
            </div>
          </div><!--end: widget tags -->
EOS;
      }

      // widget form :ウィジェットの設定画面
      function form($instance) {
        if ($instance) {
          $title = esc_attr($instance['title']);
        } else {
          $title = '';
        }
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>">
            <?php _e('タイトル'.':'); ?>
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                 name="<?php echo $this->get_field_name('title'); ?>"
                 type="text"
                 value="<?php echo $title; ?>" />
        </p>
        <?php
      }

      // widget update :データベースに登録する値の処理
      function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
      }

    }
    register_widget('custom_Widget_Side_Menu');

    /**
     * カテゴリー一覧用
     * Class custom_Widget_categories
     */
    class custom_Widget_categories extends WP_Widget {

      function __construct() {
        parent::WP_Widget(false, $name = 'Category');
      }

      function widget( $args, $instance ) {
        $title = $instance['title'];
        if(empty($title)) $title = 'Category';

        echo <<< EOS
            <!--widget categories-->
            <div class="widget clearfix widget-archive">
              <h4 class="widget-title">{$title}</h4>
              <ul class="list list-lines">
EOS;
        $args = array(
          'orderby' => 'order',
          'order' => 'ASC',
        );
        $cat_all = get_categories($args);
        foreach($cat_all as $value) {
          $name = esc_html($value->name);
          $link = str_replace('/category/','/',get_category_link($value));
          echo <<< EOS
                <li><a title="" href="{$link}">{$name}</a></li>
EOS;
        }

        echo <<< EOS
              </ul>
            </div><!--end: widget categories-->
EOS;
      }

      // widget form :ウィジェットの設定画面
      function form($instance) {
        if ($instance) {
          $title = esc_attr($instance['title']);
        } else {
          $title = '';
        }
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>">
            <?php _e('タイトル'.':'); ?>
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                 name="<?php echo $this->get_field_name('title'); ?>"
                 type="text"
                 value="<?php echo $title; ?>" />
        </p>
        <?php
      }

      // widget update :データベースに登録する値の処理
      function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
      }

    }
    register_widget('custom_Widget_categories');

    /**
     * Recent Post
     * Class custom_Widget_recent_post
     */
    class custom_Widget_recent_post extends WP_Widget {

      function __construct() {
        parent::WP_Widget(false, $name = 'Recent Post');
      }

      function widget( $args, $instance ) {
        ?>
        <?php
        $title = $instance['title'];
        if(empty($title)) $title = 'Recent Post';
        $count = $instance['count'];
        if(empty($count)) $count = 5;
        ?>
        <div class="widget clearfix widget-blog-articles">
          <h4 class="widget-title"><?php echo $title;?></h4>
          <ul class="list-posts list-medium">

            <?php
            global $post;
            $primary_term = get_primary_term();
            if(!empty($primary_term)) {
              $cat_id   = $primary_term->term_id;
              $cat_name = $primary_term->name;
              $cat_slug = $primary_term->slug;
              $cat_link = get_category_link( $cat_id );
              $args = array(
                'post_type' => 'post',    //投稿タイプの指定
                //'category' => $cat_id,
                //'post__not_in' => array($post->ID),
                'orderby' => 'date',
                'posts_per_page' => $count,
                'order' => 'DESC',
              );
            } else {
              $args = array(
                'post_type' => 'post',    //投稿タイプの指定
                //'post__not_in' => array($post->ID),
                'orderby' => 'date',
                'posts_per_page' => $count,
                'order' => 'DESC',
              );
            }

            $posts = get_posts( $args );
            if( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
              <?php
              $categories = get_the_category($post->ID);
              $image = catch_that_image($post->ID, 525, 350);
              if(empty($image)) {
                $image = SDEL.'/images/noimage.png';
              }
              ?>

              <li><small><?php echo get_post_time('F j, Y', $post->ID);?></small> <a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></li>

            <?php endforeach; ?>
            <?php endif;
            wp_reset_postdata(); //クエリのリセット ?>
          </ul>
        </div><!--End: Blog articles-->
        <?php
      }

      // widget form :ウィジェットの設定画面
      function form($instance) {
        if ($instance) {
          $title = esc_attr($instance['title']);
          $count = esc_attr($instance['count']);
        } else {
          $title = '';
          $count = '';
        }
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>">
            <?php _e('タイトル'.':'); ?>
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                 name="<?php echo $this->get_field_name('title'); ?>"
                 type="text"
                 value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('count'); ?>">
            <?php _e('表示件数'.':'); ?>
          </label>
          <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>"
                 name="<?php echo $this->get_field_name('count'); ?>"
                 type="text"
                 value="<?php echo $count; ?>" />
        </p>
        <?php
      }

      // widget update :データベースに登録する値の処理
      function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = strip_tags($new_instance['count']);
        return $instance;
      }

    }
    register_widget('custom_Widget_recent_post');


  }
  add_action('widgets_init', 'custom_widget');
endif;
