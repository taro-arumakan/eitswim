<?php
/**
 Template Name:記事用テンプレート(１カラム）
 Template Post Type: post
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
<section id="page-title" class="page-title-center text-light" data-parallax-image="<?php echo SDEL;?>/images/slide02.jpg">
    <div class="container">
        <div class="page-title text-light">
            <span class="post-meta-category"><?php echo get_cat();?></span>
            <?php echo elc_get_title('h1');?>
            <div class="small m-b-20"><?php echo get_post_time('F j, Y');?></div>
            <div class="align-center"><!-- 各SNSへの投稿 -->
                <a class="btn btn-xs btn-slide btn-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_encode;?>"  data-width="100"> <i class="fa fa-facebook"></i> <span class="spcial-btn">Facebook</span> </a>
                <a class="btn btn-xs btn-slide btn-twitter" href="https://twitter.com/share?url=<?php echo $url_encode;?>" data-width="90"> <i class="fa fa-twitter"></i> <span class="spcial-btn">Twitter</span> </a>
                <a class="btn btn-xs btn-slide btn-googleplus" href="https://plus.google.com/share?url=<?php echo $url_encode;?>" data-width="90"> <i class="fa fa-google-plus"></i> <span class="spcial-btn">Google+</span> </a>
                <!--<a class="btn btn-xs btn-slide btn-pinterest" href="https://pinterest.com/pin/create/button/?url=<?php echo $url_encode;?>&media=&description=<?php echo $url_encode;?>" data-width="100"> <i class="fa fa-pinterest"></i> <span class="spcial-btn">Pinterest</span> </a>-->
            </div>
        </div>
    </div>
</section><!-- end: Page title -->

<!-- Page Content -->
<section class="sidebar-right p-b-0">
    <div class="container">

        <!-- content -->
        <div class="content col-md-9 center">
            <!-- Blog -->
            <div id="blog" class="single-post">
                <!-- Post single item-->
                <div class="post-item">
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
                                <span class="post-meta-date"><i class="fa fa-calendar-o"></i> <?php echo get_post_time('F j, Y');?></span>
                                <span class="post-meta-category">
                                                <?php echo get_cat_detail();?>
                                        </span>
                                <div class="post-meta-share">
                                    <a class="btn btn-xs btn-slide btn-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_encode;?>"  data-width="100"> <i class="fa fa-facebook"></i> <span class="spcial-btn">Facebook</span> </a>
                                    <a class="btn btn-xs btn-slide btn-twitter" href="https://twitter.com/share?url=<?php echo $url_encode;?>" data-width="90"> <i class="fa fa-twitter"></i> <span class="spcial-btn">Twitter</span> </a>
                                    <a class="btn btn-xs btn-slide btn-googleplus" href="mailto:#" data-width="80"> <i class="fa fa-envelope"></i> <span>Mail</span> </a>
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
                                    echo "<a href=\"{$link}\">{$tag->name}</a>";
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
                        if( $prev_post ) {
                            $prev_name = $prev_post->post_title;
                            $prev_link = 'href="'.get_permalink($prev_post->ID).'"';
                        }
                        $next_name = '';
                        $next_link = '';
                        if( $next_post ) {
                            $next_name = $next_post->post_title;
                            $next_link = 'href="'.get_permalink($next_post->ID).'"';
                        }
                        ?>

                        <div class="post-navigation">
                            <?php if(!empty($prev_link)):?>
                            <?php endif;?>
                            <?php if(!empty($next_link)):?>
                            <?php endif;?>
                            <a <?php echo $prev_link;?> class="post-prev">
                                <div class="post-prev-title"><span>Previous Post</span><?php echo $prev_name;?></div>
                            </a>
                            <a href="<?php echo $cat_link;?>" class="post-all"> <i class="fa fa-th"></i> </a>
                            <a <?php echo $next_link;?> class="post-next">
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
                            <div id="blog" class="grid-layout post-2-columns m-b-30" data-item="post-item" data-layout="fitRows">

                                <?php foreach( $posts as $post ):
                                    setup_postdata( $post );
                                    ?>
                                    <?php $image = catch_that_image();?>
                                    <?php
                                    if(empty($image)) $image = SDEL.'/images/noimage.png';
                                    ?>
                                    <!-- Post item-->
                                    <div class="post-item border">
                                        <div class="post-item-wrap">
                                            <div class="post-image">
                                                <?php if(!empty($image)):?>
                                                    <a href="<?php the_permalink() ?>">
                                                        <img class="alpha-50" src="<?php echo $image ?>" alt="<?php the_title_attribute(); ?>"></a>
                                                <?php endif;?>
                                                <span class="post-meta-category">
                                                                <?php echo get_cat();?>
                                                            </span>
                                            </div>
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
                </div><!-- end: Post single item-->

            </div>
        </div><!-- end: content -->

    </div>
</section><!-- end: Page Content -->

<!-- PARALLAX -->
<section class="text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg01.jpg">
    <div class="container">
        <div class="row">
            <div class="col-md-9 center text-center text-light">
                <h2 data-animation="fadeInUp" data-animation-delay="600" class="text-medium m-b-30 t100">Download Logpose</h2>
                <p data-animation="fadeInUp" data-animation-delay="1000" class="lead m-b-30">Record and enjoy the session when you enjoyed the best wave, the best snow quality.</p>
                <a data-animation="fadeInUp" data-animation-delay="1200" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank"><img class="alpha" src="<?php echo SDEL; ?>/images/appstore-dark.svg" width="160px" alt="Logpose App Store"></a>
            </div>
        </div>
    </div>
</section><!-- end: PARALLAX -->

<?php
get_footer();
?>
