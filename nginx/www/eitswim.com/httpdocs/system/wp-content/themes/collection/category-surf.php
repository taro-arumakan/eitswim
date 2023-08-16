<?php
/**
Template Name: MEDIA用テンプレート

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

get_header();
$cat_ID = 0;
if(!empty($cat)) {
  $cat_ID = $cat;
}
$args = array(
  'post_type' => 'surf',    //投稿タイプの指定
  'orderby'  => 'ID',
  'posts_per_page' => -1,
  'order' => 'ASC',
);
$args['meta_query'] = array(
  array( 'key'=>'elc_item_category',
    'value'=>$cat_ID,
    'compare'=>'=',
  )
);

$posts = get_posts( $args );

?>

  <!-- Page title -->
  <section id="page-title" class="page-title-center p-t-80 p-b-80 dark" style="background:#fff; border:none;">
    <div class="container">
      <div class="page-title">
        <?php echo elc_get_title( 'h1', '', "font-size:24px; line-height:28px; letter-spacing:0.05em;");?>
      </div>
      <div class="breadcrumb">
        <ul>
          <?php echo elc_breadcrumb();?>
        </ul>
      </div>
    </div>
  </section><!-- end: Page title -->

  <!-- Content -->
  <section id="page-content">
    <div class="container">

      <!-- Portfolio -->
      <div id="portfolio" class="grid-layout portfolio-2-columns" data-lightbox="gallery" data-margin="5">

        <?php
        foreach( $posts as $post ):
          setup_postdata( $post );

          $d = get_post_time('F j, Y');

          $image = catch_that_image(null,'full');
          if(empty($image)) {
            //$image = '/images/noimage.jpg';
          }
          ?>
          <!-- portfolio item -->
          <div class="portfolio-item img-zoom pf-illustrations pf-uielements pf-media">
            <div class="portfolio-item-wrap">
              <div class="portfolio-image">
                <a href="#"><img src="<?php echo $image;?>" alt=""></a>
              </div>
              <div class="portfolio-description">
                <a data-lightbox="gallery-item" href="<?php echo $image;?>"><i class="fa fa-expand"></i></a>
                <?php
                $link = get_post_meta( $post->ID, 'elc_collection_link', true );
                ?>
                <?php if(!empty($link)):?>
                  <a target="_blank" href="<?php echo $link; ?>"><i class="fa fa-shopping-cart"></i></a>
                <?php else:?>
                  <a href="<?php the_permalink() ?>"><i class="fa fa-share"></i></a>
                <?php endif;?>
              </div>
            </div>
          </div><!-- end: portfolio item -->
        <?php endforeach;
        wp_reset_postdata();
        ?>

      </div>
      <!-- end: Portfolio -->

    </div>
  </section>
  <!-- end: Content -->

<?php
//get_sidebar();
get_footer();
