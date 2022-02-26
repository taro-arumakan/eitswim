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

?>
<!-- Page title -->
<section id="page-title" class="page-title-center p-t-80 p-b-80 dark" style="background:#fff; border:none;">
  <div class="container">
    <div class="page-title">
      <span class="post-meta-category"><a href="<?php echo $cat_link;?>"><?php echo $cat_name;?></a></span>
      <?php echo elc_get_title( 'h1', '', 'font-size:24px; line-height:28px; letter-spacing:0.05em;');?>
      <div class="small m-b-20"><?php echo get_post_time('F j, Y');?></div>
      <div class="align-center"><!-- 各SNSへの投稿 -->
        <div class="social-icons social-icons-border social-icons-rounded social-icons-colored-hover">
          <ul>
            <li class="social-facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_encode;?>"><i class="fa fa-facebook"></i></a></li>
            <li class="social-twitter"><a href="https://twitter.com/share?url=<?php echo $url_encode;?>"><i class="fa fa-twitter"></i></a></li>
            <li class="social-gplus"><a href="https://plus.google.com/share?url=<?php echo $url_encode;?>"><i class="fa fa-google-plus"></i></a></li>
            <li class="social-pinterest"><a href="https://pinterest.com/pin/create/button/?url=<?php echo $url_encode;?>&media=&description=<?php echo $url_encode;?>" data-width="100"><i class="fa fa-pinterest"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="breadcrumb">
        <ul>
          <?php echo elc_breadcrumb();?>
        </ul>
      </div>
    </div>
  </div>
</section><!-- end: Page title -->

<!-- Page Content -->
<section id="page-content" class="p-b-0">
  <div class="container">

    <!-- content -->
    <div class="content col-md-9 center">
      <!-- Blog -->
      <div id="blog" class="single-post">
        <!-- Post single item-->
        <div class="post-item p-b-0">
          <div class="post-item-wrap">
            <div class="post-item-description m-t-0 p-t-0">
              <article data-lightbox="gallery"><!-- このarticleタグは、テンプレート側 -->

                <!-- ブログ投稿内容 ここから -->
                <?php
                /** 出力側の変換機能を無効化する **/
                //remove_filter( 'the_content', 'wpautop' );
                //remove_filter( 'the_content', 'wptexturize' );

                while ( have_posts() ) : the_post();

                  //the_content();
                  $content = get_the_content();
                  $content = apply_filters( 'the_content', $content );
                  echo $content;

                endwhile; // End of the loop.
                ?>

              </article><!-- このarticleタグは、テンプレート側 -->
              <div class="post-meta">
                <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php echo get_post_time('F j, Y');?></span>
                <div class="post-meta-share p-0 m-0">
                  <div class="social-icons social-icons-border social-icons-rounded social-icons-colored-hover p-0 m-0">
                    <ul>
                      <li class="social-facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_encode;?>"><i class="fa fa-facebook"></i></a></li>
                      <li class="social-twitter"><a href="https://twitter.com/share?url=<?php echo $url_encode;?>"><i class="fa fa-twitter"></i></a></li>
                      <li class="social-gplus"><a href="https://plus.google.com/share?url=<?php echo $url_encode;?>"><i class="fa fa-google-plus"></i></a></li>
                      <li class="social-pinterest"><a href="https://pinterest.com/pin/create/button/?url=<?php echo $url_encode;?>&media=&description=<?php echo $url_encode;?>" data-width="100"><i class="fa fa-pinterest"></i></a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <?php
            $tags = get_tags();
            ?>
            <?php if(!empty($tags)):?>
              <!-- Tag Cloud -->
              <div class="post-tags">
                <?php
                foreach($tags as $tag) {
                  $link = get_tag_link($tag->term_id);
                  echo "<a href=\"{$link}\">{$tag->name}</a>&nbsp;";
                }
                ?>
              </div>
              <!-- .tagcloud end -->
            <?php endif;?>

            <?php
            $prev_link = '';
            //すべてのカテゴリー内の前後の記事を取得(false)
            $prev_post = get_previous_post(false);
            $next_post = get_next_post(false);
            $prev_name = '';
            $prev_link = '';
            $prev_hide = '';
            if( $prev_post ) {
              $prev_name = $prev_post->post_title;
              $prev_link = 'href="'.get_permalink($prev_post->ID).'"';
            } else {
              $prev_hide = ' style="display:none;"';
            }
            $next_name = '';
            $next_link = '';
            $next_hide = '';
            if( $next_post ) {
              $next_name = $next_post->post_title;
              $next_link = 'href="'.get_permalink($next_post->ID).'"';
            } else {
              $next_hide = ' style="display:none;"';
            }
            ?>

            <div class="post-navigation">
              <a <?php echo $prev_link;?> class="post-prev"<?php echo $prev_hide;?>>
                <div class="post-prev-title"><span>Previous Post</span><?php echo $prev_name;?></div>
              </a>
              <a href="<?php echo $cat_link;?>" class="post-all"> <i class="fa fa-th"></i> </a>
              <a <?php echo $next_link;?> class="post-next"<?php echo $next_hide;?>>
                <div class="post-next-title"><span>Next Post</span><?php echo $next_name;?></div>
              </a>
            </div>

            <?php
            $args = array(
              'post__not_in' => array($post -> ID),
              'post_type' => 'post',    //投稿タイプの指定
              'cat' => $cat_id,
              'orderby' => 'rand',
              'posts_per_page' => 4,
            );

            $posts = get_posts( $args );
            ?>
            <?php if(!empty($posts)):?>
              <!-- Section title -->
              <div class="page-title p-t-60 p-b-40 text-center">
                <h2 class="t200">Related Article</h2>
              </div>

              <!-- Blog -->
              <div id="blog" class="grid-layout post-2-columns" data-item="post-item" data-layout="fitRows">

                <?php foreach( $posts as $post ):
                  setup_postdata( $post );
                  ?>
                  <?php $image = catch_that_image();?>
                  <?php
                  //if(empty($image)) $image = SDEL.'/images/noimage.png';
                  ?>
                  <!-- Post item-->
                  <div class="post-item border">
                    <div class="post-item-wrap">
                      <?php //if(!empty($image)):?>
                      <?php if(false):?>
                      <div class="post-image">
                          <a href="<?php the_permalink() ?>">
                            <img class="alpha-50" src="<?php echo $image ?>" alt="<?php the_title_attribute(); ?>"></a>
                      </div>
                      <?php endif;?>
                      <div class="post-item-description">
                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php the_time('Y.m.d');?></span>
                        <h2 class="serelatedtitle"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                      </div>
                    </div>
                  </div><!-- end: Post item-->

                <?php endforeach;?>

              </div>
            <?php endif;?>

          </div>
        </div>
      </div><!-- end: Post single item-->

    </div>
  </div><!-- end: content -->

  </div>
</section><!-- end: Page Content -->

<?php
get_footer();
?>
<script>
    $(function() {
        var attr = $("div.post-image a").attr("data-lightbox");
        if(!attr) {
            $("div.post-image a").attr("data-lightbox","gallery-item");
        }
    });
</script>
