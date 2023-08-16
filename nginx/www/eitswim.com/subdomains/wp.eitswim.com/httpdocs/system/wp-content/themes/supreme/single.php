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

$root_uri = get_theme_mod( NEWS_ROOT_URI_EKY, '/news' );

$cat = get_the_category($post->ID);
$cat = isset( $cat[0] ) ? $cat[0] : null;
$cat_link = get_category_link( $cat->cat_ID )

?>

  <!-- Page title -->
  <section id="page-title" class="page-title-center text-light">
    <div class="container">
      <div class="page-title text-light">
        <div data-animation="fadeIn">
          <span class="post-meta-category"><a href="<?php echo $cat_link;?>"><?php echo $cat->name;?></a></span>
          <?php echo elc_get_title('h1', 'nb-title');?>
          <div class="small m-b-20"><span><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;<?php echo get_post_time('M d, Y');?></span></div>
        </div>
      </div>
      <div class="breadcrumb">
        <ul>
          <?php echo elc_breadcrumb();?>
        </ul>
      </div>
    </div>
  </section><!-- end: Page title -->

  <!-- Page Content -->
  <section id="page-content" class="sidebar-right">
    <div class="container">

      <!-- content -->
      <div class="content col-md-9 center">
        <!-- Blog -->
        <div id="blog" class="single-post">
          <!-- Post single item-->
          <div class="post-item">
            <div class="post-item-wrap">
              <div class="post-item-description m-t-0 p-t-0">
                <article>

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
                  <!-- 投稿エリア終了 -->
                </article>

                <?php
                $cat = get_the_category($post->ID);
                $cat = isset( $cat[0] ) ? $cat[0] : null;
                $cat_link = get_category_link( $cat->cat_ID );

                $url_encode=urlencode(get_permalink());
                $title_encode=urlencode(get_the_title()).'｜'.get_bloginfo('name');

                ?>
                <div class="post-meta">
                  <span class="post-meta-date"><i class="fa fa-calendar-o"></i> <?php echo get_post_time('F j, Y');?></span>
                  <span class="post-meta-category"><a href="<?php echo $cat_link;?>"><i class="fa fa-tag"></i> <?php echo $cat->name;?></a></span>
                  <div class="post-meta-share">
                    <a class="btn btn-xs btn-slide btn-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_encode;?>" target="_blank"> <i class="fa fa-facebook"></i> <span>Facebook</span> </a>
                    <a class="btn btn-xs btn-slide btn-twitter" href="https://twitter.com/share?url=<?php echo $url_encode;?>" data-width="100" target="_blank"> <i class="fa fa-twitter"></i> <span>Twitter</span> </a>
                    <a class="btn btn-xs btn-slide btn-googleplus" href="mailto:" data-width="80"> <i class="fa fa-envelope"></i> <span>Mail</span> </a>
                  </div>
                </div>
              </div>
              <?php
              $tags = get_tags();
              ?>
              <?php if(!empty($tags)):?>
                <div class="post-tags">
                  <?php
                  foreach($tags as $tag) {
                    echo '<a href="'. get_tag_link($tag->term_id) .'">' . $tag->name . '</a>'."\n";
                  }
                  ?>
                </div>
              <?php endif;?>
              <?php
              $prev_link = '';
              //すべてのカテゴリー内の前後の記事を取得(false)
              $prev_post = get_previous_post(false);
              $next_post = get_next_post(false);
              $prev_name = '';
              $prev_link = '';
              if( $prev_post ) {
                $prev_name = $prev_post->post_title;
                $prev_link = get_permalink($prev_post->ID);
              }
              $next_name = '';
              $next_link = '';
              if( $next_post ) {
                $next_name = $next_post->post_title;
                $next_link = get_permalink($next_post->ID);
              }
              ?>

              <div class="post-navigation">
                <?php if(!empty($prev_link)):?>
                  <a href="<?php echo $prev_link;?>" class="post-prev">
                    <div class="post-prev-title">
                      <span>Previous Post</span>
                      <?php echo $prev_name;?>
                    </div>
                  </a>
                <?php else:?>
                  &nbsp;
                <?php endif;?>
                <a href="<?php echo $root_uri;?>" class="post-all"> <i class="fa fa-th"></i> </a>
                <?php if(!empty($next_link)):?>
                  <a href="<?php echo $next_link;?>" class="post-next">
                    <div class="post-next-title">
                      <span>Next Post</span>
                      <?php echo $next_name;?>
                    </div>
                  </a>
                <?php else:?>
                  &nbsp;
                <?php endif;?>
              </div>

              <?php
              $args = array(
                'post__not_in' => array($post -> ID),
                'post_type' => 'post',    //投稿タイプの指定
                'category_name' => $category_name,
                'orderby' => 'rand',
                'posts_per_page' => 2,
              );

              $posts = get_posts( $args );
              ?>
              <?php if(!empty($posts)):?>
                <!-- Section title -->
                <div class="page-title p-t-60 p-b-40 text-center">
                  <h2 class="font-weight">Related Article</h2>
                </div>

                <!-- Blog -->
                <div id="blog" class="grid-layout post-2-columns m-b-30" data-item="post-item" data-layout="fitRows">

                  <?php
                  $related_post = array();
                  if( $posts ) : foreach( $posts as $post ) : setup_postdata( $post ); ?>
                    <?php
                    $src = get_permalink($post->ID);
                    $temp_post = array();
                    $temp_post['ID'] = $post->ID;
                    $image = catch_that_image($post->ID,'studioelc-thumb-525');
                    if(empty($image)) {
                      $image = ELC_IMG.'no-img-525x350.png';
                    }
                    $temp_post['thumbnail'] = $image;
                    $temp_post['title'] = get_the_title($post->ID);
                    $temp_post['link'] = get_permalink($post->ID);
                    $temp_post['date'] = get_post_time('F j, Y', $post->ID);
                    $description = get_post_meta($post->ID,'elc_description',true);
                    if(empty($description)) {
                      $description = get_the_custom_excerpt(30);
                    }
                    $temp_post['content'] = $description;
                    $related_post[] = $temp_post;
                    ?>
                  <?php endforeach; ?>
                  <?php endif;
                  wp_reset_postdata(); //クエリのリセット ?>
                  <?php foreach($related_post as $item):?>
                    <?php
                    $cat = get_the_category($item['ID']);
                    $cat = isset( $cat[0] ) ? $cat[0] : null;
                    $cat_link = get_category_link( $cat->cat_ID )
                    ?>
                    <!-- Post item-->
                    <div class="post-item border">
                      <div class="post-item-wrap">
                        <div class="post-image">
                          <a href="<?php echo $item['link'];?>"><img alt="" src="<?php echo $item['thumbnail'];?>"></a>
                          <span class="post-meta-category"><a href="<?php echo $cat_link;?>"><?php echo $cat->name;?></a></span>
                        </div>
                        <div class="post-item-description">
                          <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php echo $item['date'];?></span>
                          <h2><a href="<?php echo $item['link'];?>"><?php echo $item['title'];?></a></h2>
                        </div>
                      </div>
                    </div><!-- end: Post item-->
                  <?php endforeach;?>

                </div>
              <?php endif;?>
            </div>
          </div><!-- end: Post single item-->

        </div>
      </div><!-- end: content -->

    </div>
  </section><!-- end: Page Content -->

<?php
get_footer();
