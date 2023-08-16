<?php
/**
 Template Name:About用
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
    <!-- Main Visual -->
    <div id="slider" class="inspiro-slider slider-fullscreen arrows-large arrows-creative dots-creative" data-height-xs="360">
        <div class="slide " data-parallax-image="<?php echo SDEL;?>/images/sec_ful-bg02.jpg">
            <div class="container-wide">
                <div class="slide-captions col-md-10">
                    <!-- Captions -->
                    <h1 class="text-medium-light t400" data-caption-animation="zoom-out">About Company</h1>
                    <p class="lead">Everything you need to know about our Company!</p>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
    </div><!-- end: Main Visual -->

    <!-- Content 01 -->
    <section id="AboutCompany" class="image-block no-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6" data-animation="fadeInLeft" data-animation-delay="800" style="height:609px; background:url(<?php echo SDEL;?>/images/about_company.jpg) 50% 50% / cover no-repeat;"> </div>
                <div class="col-md-6 p-t-80 p-b-80" data-animation="fadeInUp" data-animation-delay="800">
                    <h2 class="t100">About Studio E.L.C Inc.</h2>
                    <p class="lead">We have a web strategy through communication with customers, we exist to shape our customers' desires and lead the net business to success.<br>
                        Studio E.L.C Inc. cherishes each customer carefully, conducts polite correspondence, we try to create better things.</p>
                    <a class="scroll-to btn btn-rounded btn-light text-none t700" href="#ServiceSolutions">Service &amp; Solutions</a>
                </div>
            </div>
        </div>
    </section><!-- end: Content 01 -->

    <!-- PARALLAX -->
    <section class="text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg04.jpg">
        <div class="container text-right">
            <div data-animation="fadeInUp" data-animation-delay="700" class="heading text-right m-b-40">
                <h2 class="text-medium m-b-5 t100">If you need<br>our solutions.</h2>
                <h3 class="t100">Please feel free to contact us.</h3>
            </div>
        </div>
    </section><!-- end: PARALLAX -->

    <!-- Content 02 -->
    <section id="ServiceSolutions" class="image-block no-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 p-t-80 p-b-80" data-animation="fadeInUp" data-animation-delay="800">
                    <h2 class="t100">Service &amp; Solutions</h2>
                    <p class="lead">Using designs and technologies that are best suited to your strategy, we will consistently create and implement our site operations strategy, design production, content production, system development, and we will help you to function as a pillar of our management and marketing strategy I will.</p>
                    <a class="btn btn-rounded btn-light text-none t700" href="/contact">Contact us</a>
                </div>
                <div class="col-md-6" data-animation="fadeInRight" data-animation-delay="800" style="height:609px; background:url(<?php echo SDEL;?>/images/solution_company.jpg) 50% 50% / cover no-repeat;"> </div>
            </div>
        </div>
    </section><!-- end: Content 02 -->

    <!-- PARALLAX -->
    <section class="text-center text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg05.jpg">
        <div class="container">
            <div class="heading heading-center m-b-40">

                <h2 class="text-medium m-b-5 t100">Please feel free to contact.</h2>
                <h3 class="t100">Studio E.L.C Inc.</h3>
            </div>
            <a class="btn btn-rounded btn-light btn-outline text-none t700" href="/contact">Contact us</a>
        </div>
    </section><!-- end: PARALLAX -->

<!-- old -->

<?php
//get_sidebar();
get_footer();
