<?php
/**
 Template Name:法務系テンプレート(サイドバー）
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
$elc_title_image = get_post_meta($post->ID,'elc_title_image',true);
if(!empty($elc_title_image)) {
  $parallax_image = "data-parallax-image=\"{$elc_title_image}\"";
}

$sub_title = get_post_meta($post->ID, 'elc_sub_title', true);
?>

    <!-- Page title -->
    <section id="page-title" class="page-title-center text-light" data-parallax-image="<?php echo SDEL; ?>/images/slide02.jpg">
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

    <!-- Page Content -->
    <section class="sidebar-right p-b-0">
        <div class="container">
            <div class="row">
                <!-- content -->
                <div class="content col-md-8">
                    <!-- Blog -->
                    <div id="blog" class="single-post">
                        <!-- Post single item-->
                        <div class="post-item">
                            <div class="post-item-wrap">

                                <div class="post-item-description m-t-0 p-t-0">
                                    <article><!-- このarticleタグは、テンプレート側 -->

                                        <!-- コンテンツ内容 ここから -->

<?php
/** 出力側の変換機能を無効化する **/
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );
while ( have_posts() ) : the_post();

	//get_template_part( 'template-parts/content', 'page' );
	the_content();
endwhile; // End of the loop.
?>
                                        <!-- コンテンツ内容 ここまで -->

                                    </article><!-- このarticleタグは、テンプレート側 -->
                                </div>
                            </div>
                        </div><!-- end: Post single item-->

                    </div>
                </div><!-- end: content -->

                <!-- Sidebar-->
                <div class="sidebar col-md-4">

                    <!--widget newsletter-->
                    <div class="widget  widget-newsletter">
                        <form id="widget-search-form-sidebar" action="/" method="get" class="form-inline">
                            <div class="input-group">
                                <input type="text" aria-required="true" name="s" class="form-control widget-search-form" placeholder="Search for pages...">
                                <span class="input-group-btn">
                  <button type="submit" id="widget-widget-search-form-button" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
                            </div>
                        </form>
                    </div><!--end: widget newsletter-->

                    <!-- banner -->
		            <?php if ( is_active_sidebar( 'sidebar_banner1' ) ) : ?>
                        <div class="widget">
                            <div class="post-image">
					            <?php dynamic_sidebar( 'sidebar_banner1' ); ?>
                            </div>
                        </div><!-- End: banner -->
		            <?php endif; ?>

		            <?php if ( is_active_sidebar( 'sidebar_page' ) ) : ?>
			            <?php dynamic_sidebar( 'sidebar_page' ); ?>
		            <?php endif; ?>

                </div><!-- end: sidebar-->

            </div>
        </div>
    </section><!-- end: Page Content -->

    <!-- PARALLAX -->
    <section class="text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg01.jpg">
        <div class="container">
            <div class="row">
                <div class="col-md-9 center text-center text-light">
                    <h2 data-animation="fadeInUp" data-animation-delay="600" class="text-medium m-b-30 t100">Download Logpose</h2>
                    <p data-animation="fadeInUp" data-animation-delay="1000" class="lead m-b-30">Record and enjoy the session when you enjoyed the best wave, the best snow quality.</p>
                    <a data-animation="fadeInUp" data-animation-delay="1200" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank"><img class="alpha" src="<?php echo SDEL;?>/images/appstore-dark.svg" width="160px" alt="Logpose App Store"></a>
                </div>
            </div>
        </div>
    </section><!-- end: PARALLAX -->

<?php
//get_sidebar();
get_footer();
?>
