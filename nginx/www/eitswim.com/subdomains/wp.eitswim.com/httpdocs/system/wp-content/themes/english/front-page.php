<?php

$news_count = get_option( 'elc_news_page_news_count', 10 );
$news_count = 3;

    /** 出力側の変換機能を無効化する **/
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );

get_header();

?>
<!-- Content -->
<section class="p-b-0">
  <div class="container">
    <div id="blog">
      <div class="post-item p-b-0">
        <div class="post-item-wrap">
          <div class="post-slider">
            <div class="carousel dots-inside arrows-visible arrows-only" data-items="1" data-loop="true" data-autoplay="true">
              <?php dynamic_sidebar( 'main_visual' ); ?>
            </div>
          </div>
        </div>
      </div>
    </div><!-- end: Blog -->
  </div>
</section><!-- end: Content -->

<!-- Content -->
<section class="p-b-0">
  <div class="container text-center">
    <h3 class="m-b-0"><a href="/think-eco/">THINK ECO.</a></h3>
    <p><a href="/english/think-eco/">Small Changes, Big Difference</a></p>
  </div>
</section>

<!-- News -->
<?php
$args = array(
  'post_type' => 'post',
  'posts_per_page' => -1,
  'order'=>'DESC',
  'orderby'=>'post_date',
);
$posts = get_posts($args);
$is_news = '';
$news_title = '';
$news_link = '';
$news_dt = '';
foreach( $posts as $post ) {
  setup_postdata( $post );
  $is_news = get_post_meta( $post->ID, 'elc_top_news', true );
  if(!empty($is_news)) {
    $news_dt = get_post_time('F j, Y');
    $news_link = get_the_permalink();
    $news_title = get_the_title();
  }
}
wp_reset_postdata();
?>
<?php if(!empty($news_link)):?>
<section id="page-content" class="p-b-0">
  <div class="container">
    <!-- post content -->
    <div class="content col-md-9 center">

      <!-- Blog -->
      <div id="blog" class="post-thumbnails text-center">

        <!-- Post item-->
        <div class="post-item">
          <div class="post-item-wrap">
            <div class="post-item-description">
              <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php echo $news_dt;?></span>
              <h2><a href="<?php echo $news_link;?>"><?php echo $news_title;?></a></h2>
            </div>
          </div>
        </div><!-- end: Post item-->
      </div><!-- end: Blog -->
    </div><!-- end: post content -->
  </div>
</section><!-- end: Content -->
<?php endif;?>


<?php
get_footer();
?>
