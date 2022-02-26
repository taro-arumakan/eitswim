<?php

/**
Template Name:NEWS用
 *
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

global $add_class;
$add_class = '';
global $add_title_class;
$add_title_class = '';
global $add_header_class;
$add_header_class = '';

get_header();

$parallax_image = '';
$elc_title_image = get_post_meta($post->ID, 'elc_title_image', true);
if (!empty($elc_title_image)) {
  $parallax_image = "data-parallax-image=\"{$elc_title_image}\"";
}

$sub_title = get_post_meta($post->ID, 'elc_sub_title', true);
$news_count = get_option('elc_news_page_news_count', 10);

$primary_term = get_primary_term();
if (!empty($primary_term)) {
  $cat_id   = $primary_term->term_id;
  $cat_name = $primary_term->name;
  $cat_slug = $primary_term->slug;
  $cat_link = get_category_link($cat_id);
}

?>
<!-- Page title -->
<section id="page-title" class="page-title-center p-t-80 p-b-80 dark" style="background:#fff; border:none;">
  <div class="container">
    <div class="page-title">
      <h1 style="font-size:24px; line-height:28px; letter-spacing:0.05em;">BLOG &amp; NEWS</h1>
    </div>
  </div>
  <div class="breadcrumb">
    <ul>
      <li><a href="/">HOME</a></li>
      <li class="active">BLOG &amp; NEWS</li>
    </ul>
  </div>
  </div>
</section><!-- end: Page title -->

<!-- Content -->
<section id="page-content" class="p-b-0">
  <div class="container">
    <!-- post content -->
    <div class="content col-md-9 center">

      <!-- Blog -->
      <div id="blog" class="post-thumbnails">

        <?php
        $args = array(
          'post_type' => 'post',
          //          'category' => $cat_id,
          'posts_per_page' => $news_count,
          'order' => 'DESC',
          'orderby' => 'post_date',
        );
        if (!empty($page)) {
          $args['paged'] = $page;
          global $paged; //現在のページ値
          $paged = $page;
        }
        //$posts = get_posts($args);
        $posts = new WP_Query($args);
        /**
         * NEWマーク 投稿日から2週間以内はNEW表示
         */
        foreach ($posts->posts as $post) :
          setup_postdata($post);
          $d = get_post_time('F j, Y');

          $image = catch_that_image(null, 'thumbnail');
          if (empty($image)) {
            //$image = '/images/noimage.jpg';
          }
        ?>
          <!-- Post item-->
          <div class="post-item">
            <div class="post-item-wrap">
              <div class="post-item-description">
                <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php echo get_post_time('F j, Y'); ?></span>
                <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                <p><?php echo get_the_custom_excerpt(60); ?></p>
                <a href="<?php the_permalink() ?>" class="item-link">Read More <i class="fa fa-chevron-circle-right" style="padding-top:3px;"></i></a>
              </div>
            </div>
          </div><!-- end: Post item-->
        <?php endforeach;
        wp_reset_postdata();
        ?>

      </div><!-- end: Blog -->

      <!-- Pagination -->
      <div class="pagination pagination-simple p-t-30">
        <ul>
          <?php
          //Pagination
          if (function_exists("elc_pagination")) {
            elc_pagination($posts->max_num_pages);
          }
          ?>
        </ul>
      </div><!-- end: Pagination -->

    </div><!-- end: post content -->
  </div>
</section><!-- end: Content -->

<?php
//get_sidebar();
get_footer();
?>