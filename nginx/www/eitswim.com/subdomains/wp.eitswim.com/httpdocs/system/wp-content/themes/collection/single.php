<?php
/**
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
 *
 */

get_header();

$root_uri = get_theme_mod( NEWS_ROOT_URI_EKY, '/media' );

$primary_term = get_primary_term();
if(!empty($primary_term)) {
    $cat_id   = $primary_term->term_id;
    $cat_name = $primary_term->name;
    $cat_slug = $primary_term->slug;
    $cat_link = get_category_link( $cat_id );
}

$url_encode=urlencode(get_permalink());
$title_encode=urlencode(get_the_title()).'｜'.get_bloginfo('name');
$image = catch_that_image(null,'full');

?>
<!-- Page title -->
<section id="page-title" class="page-title-center p-t-40 p-b-0 dark" style="background:#fff; border:none;">
  <div class="container">
    <div class="page-title">
      <div class="align-center"><!-- 各SNSへの投稿 -->
        <div class="social-icons social-icons-border social-icons-rounded social-icons-colored-hover">
          <ul>
            <li class="social-facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_encode;?>"><i class="fa fa-facebook"></i></a></li>
            <li class="social-twitter"><a href="https://twitter.com/share?url=<?php echo $url_encode;?>"><i class="fa fa-twitter"></i></a></li>
            <li class="social-gplus"><a href="https://plus.google.com/share?url=<?php echo $url_encode;?>"><i class="fa fa-google-plus"></i></a></li>
            <li class="social-pinterest"><a href="https://pinterest.com/pin/create/button/?url=<?php echo $url_encode;?>&media=&description=<?php echo $url_encode;?>"><i class="fa fa-pinterest"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section><!-- end: Page title -->
<!-- Content -->
<section class="p-0">
  <div class="container">
    <div class="row m-b-40">
      <div class="col-md-12">
        <div class="col-md-10 center text-center">
          <div class="row">
            <div class="post-image m-b-40">
              <img src="<?php echo $image;?>" alt="image">
            </div>
          </div>
          <?php
          $link = get_post_meta( $post->ID, 'elc_collection_link', true );
          ?>
          <?php if(!empty($link)):?>
          <div class="row">
            <a href="<?php echo $link;?>">Go to Shop</a>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <?php
  $cat_ID = get_post_meta( $post->ID, 'elc_item_category', true );
  $cat_lnk = get_category_link($cat_ID);
  $cat_lnk = str_replace('/category/', '/', $cat_lnk);
  $prev_post = get_custom_previous_post();
  $next_post = get_custom_next_post();
  $prev_name = '';
  $prev_link = '';
  $prev_hide = '';
  if( $prev_post ) {
    $prev_name = $prev_post->post_title;
    $prev_link = 'href="'.get_permalink($prev_post->ID).'"';
  } else {
    $prev_hide = ' style="display:none"';
  }
  $next_name = '';
  $next_link = '';
  $next_hide = '';
  if( $next_post ) {
    $next_name = $next_post->post_title;
    $next_link = 'href="'.get_permalink($next_post->ID).'"';
  } else {
    $next_hide = ' style="display:none"';
  }
  ?>
  <div class="post-navigation">
    <a <?php echo $prev_link;?> class="post-prev" <?php echo $prev_hide;?>>
    <div class="post-prev-title"><span>PREVIOUS</span>LOOK</div>
    </a>
    <a href="<?php echo $cat_lnk;?>" class="post-all"> <i class="fa fa-th"></i> </a>
    <a <?php echo $next_link;?> class="post-next" <?php echo $next_hide;?>>
    <div class="post-next-title"><span>NEXT</span>LOOK</div>
    </a>
  </div>
</section><!-- end: Content -->

<?php
get_footer();
?>
