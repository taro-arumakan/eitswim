<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package studioelc
 */

global $paged;
global $is_category;
global $is_tag;
global $is_archive;
global $is_news;
if($is_category || $is_tag) {
  $pg = 'page';
  $cat = get_the_category();
  $cat = isset( $cat[0] ) ? $cat[0] : null;
  $root_uri = get_category_link( $cat->cat_ID );

} else {
  $pg = 'pg';
  $root_uri = get_theme_mod( NEWS_ROOT_URI_EKY, '/news' );
}

get_header();

$parallax_image = '';
$elc_title_image = get_post_meta($post->ID,'elc_title_image',true);
if($is_news === true) {
  $elc_title_image = get_option( 'elc_news_page_news_img', null );
}
if(!empty($elc_title_image)) {
  $parallax_image = "data-parallax-image=\"{$elc_title_image}\"";
}

$root_uri = get_theme_mod( NEWS_ROOT_URI_EKY, '/news' );

$news_count = get_option( 'elc_news_page_news_count', 10 );

?>

  <!-- Page title -->
  <section id="page-title" class="page-title-center text-light" <?php echo $parallax_image;?>>
    <div class="container">
      <div class="page-title text-light">
        <div data-animation="fadeIn">
          <?php echo elc_get_title('h1', '');?>
        </div>
      </div>
      <div class="breadcrumb">
        <ul>
          <?php echo elc_breadcrumb();?>
        </ul>
      </div>
    </div>
  </section><!-- end: Page title -->

  <!-- Content -->
  <section id="page-content" class="sidebar-right">
    <div class="container">
      <div class="row">
        <!-- post content -->
        <div class="content col-md-9 center">

          <!-- Blog -->
          <div id="blog" class="post-thumbnails">

            <?php

            //$per_page = get_option('posts_per_page');
            $paged = 1;
            if(!empty($_GET[$pg])) {
              $paged = $_GET[$pg];
            }
            $prev_paged = $paged - 1;
            $next_paged = $paged + 1;

            $args = array(
              'post_type' => 'post',    //投稿タイプの指定
              'orderby' => 'date',
              'posts_per_page' => $news_count,
              'order' => 'DESC',
              'paged' => $paged,
            );
            if($is_tag) {
              //TAG指定の場合
              $args['tax_query'] = array(
                array(
                  'taxonomy' => 'post_tag',
                  'field' => 'slug',
                  'terms' => $tag
                ));
            } else {
              if(!empty($category_name) && $is_news !== true) {
                //カテゴリ指定の場合
                $args['category_name'] = $category_name;
              }
            }
            if(!empty($_GET['y'])) {
              //年度指定の場合
              $args['date_query'] = array('year' => $_GET['y']);
            }
            //$posts = get_posts( $args );
            $query = new WP_Query($args);
            $posts = $query->posts;

            if( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
              <?php
              $description = get_post_meta($post->ID,'elc_description',true);
              if(empty($description)) {
                $description = get_the_custom_excerpt(get_excerpt_size());
              }
              $image = catch_that_image($post->ID,'studioelc-thumb-525');
              if(empty($image)) {
                $image = ELC_IMG.'no-img-525x350.png';
              }

              $cat = get_the_category($post->ID);
              $cat = isset( $cat[0] ) ? $cat[0] : null;
              $cat_link = get_category_link( $cat->cat_ID )
              ?>
              <!-- Post item-->
              <div class="post-item">
                <div class="post-item-wrap">
                  <div class="post-image">
                    <?php if($image):?>
                      <a href="<?php the_permalink(); ?>"> <img src="<?php echo $image;?>" alt=""> </a>
                    <?php endif;?>
                    <span class="post-meta-category"><a href="<?php echo $cat_link;?>"><?php echo $cat->name;?></a></span>
                  </div>
                  <div class="post-item-description">
                    <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php echo get_post_time('F j, Y');?></span>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo $description;?></p>
                    <a href="<?php the_permalink(); ?>" class="item-link">Read More <i class="fa fa-arrow-right"></i></a>
                  </div>
                </div>
              </div><!-- end: Post item-->
            <?php endforeach; ?>
            <?php endif;
            wp_reset_postdata(); //クエリのリセット ?>



            <!-- Pagination -->
            <div class="pagination pagination-simple p-t-30">
              <ul>
                <?php
                //Pagination
                if (function_exists("elc_pagination")) {
                  elc_pagination($query->max_num_pages);
                }
                ?>
              </ul>
            </div><!-- end: Pagination -->

          </div><!-- end: post content -->

        </div>
      </div>
    </div>
  </section><!-- end: Content -->

<?php
get_footer();
