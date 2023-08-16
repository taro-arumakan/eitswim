<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package studioelc
 */

$parallax_image = '';
$elc_title_image = get_post_meta($post->ID,'elc_title_image',true);
if(!empty($elc_title_image)) {
    $parallax_image = "data-parallax-image=\"{$elc_title_image}\"";
}

$sub_title = get_post_meta($post->ID, 'elc_sub_title', true);
$news_count = get_option( 'elc_news_page_news_count', 10 );


get_header(); ?>

    <!-- Page title -->
    <section id="page-title" class="page-title-center text-light" data-parallax-image="<?php echo SDEL; ?>/images/slide03.jpg">
        <div class="container">
            <div class="page-title text-light">
                <div data-animation="fadeIn">
                    <?php echo elc_get_title('h1');?>
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
                        $search = new WP_Query($query_string);
                        if($search->found_posts > 0) {
                            $posts = $search->posts;
                        }
                        if(!empty($posts)) {
                        foreach( $posts as $post ):
                            setup_postdata( $post );

                            $d = get_post_time('F j, Y');

                            $image = catch_that_image(null,'thumbnail');
                            if(empty($image)) {
                                $image = SDEL.'/images/noimage.png';
                            }
                            ?>
                            <!-- Post item-->
                            <div class="post-item">
                                <div class="post-item-wrap">
                                    <div class="post-image">
                                        <a href="<?php the_permalink() ?>">
                                            <?php if(!empty($image)):?>
                                                <img src="<?php echo $image;?>" alt="">
                                            <?php endif;?>
                                        </a>
                                        <?php if(!empty(get_cat())):?>
                                        <span class="post-meta-category"><?php echo get_cat();?></span>
                                        <?php endif;?>
                                    </div>
                                    <div class="post-item-description">
                                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i><?php echo get_post_time('F j, Y');?></span>
                                        <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                                        <p><?php echo get_the_custom_excerpt(160);?></p>
                                        <a href="<?php the_permalink() ?>" class="item-link">Read More <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div><!-- end: Post item-->

                        <?php endforeach;
                        }
                        wp_reset_postdata();
                        ?>

                    </div><!-- end: Blog -->

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
    </section><!-- end: Content -->

    <!-- PARALLAX -->
    <section class="text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL; ?>/images/sec_bg01.jpg">
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
