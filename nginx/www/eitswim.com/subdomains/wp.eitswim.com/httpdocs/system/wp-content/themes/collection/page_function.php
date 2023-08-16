<?php
/**
 Template Name:Function用
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

//価格設定
$price_month = get_option( 'elc_top_page_price_month');
$price_year = get_option( 'elc_top_page_price_year');
if(empty($price_month)) $price_month = '2.99';
if(empty($price_year)) $price_year = '30.99';

get_header();

$parallax_image = '';
$elc_title_image = get_post_meta($post->ID,'elc_title_image',true);
if(!empty($elc_title_image)) {
    $parallax_image = "data-parallax-image=\"{$elc_title_image}\"";
}

$sub_title = get_post_meta($post->ID, 'elc_sub_title', true);
?>

    <!-- Main Visual -->
    <section id="slider" class="fullscreen" data-parallax-image="<?php echo SDEL;?>/images/sec_ful-bg01.jpg">
        <div class="container container-fullscreen text-light">
            <div class="text-middle">
                <div class="container-wide">
                    <div class="slide-captions col-md-10">
                        <h1 class="text-medium-light t400" data-caption-animation="zoom-out">Logpose apps function</h1>
                        <p class="lead">riding distance, riding speed and the difference in elevation of your activity!</p>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- end: Main Visual -->

    <!-- Content 01 -->
    <section id="AboutLogpose">
        <div class="container">
            <div class="row">
                <div class="col-md-6" style="height:556px">
                    <div class="post-image">
                        <div class="image-absolute" data-animation="fadeInLeft" data-animation-delay="800"> <img src="<?php echo SDEL;?>/images/iphonex_01.png" alt=""> </div>
                        <div class="image-absolute" data-animation="fadeInLeft" data-animation-delay="400"> <img src="<?php echo SDEL;?>/images/watch_01.png" alt=""> </div>
                    </div>
                </div>
                <div class="col-md-6 p-t-40" data-animation="fadeInUp" data-animation-delay="1000">
                    <div class="m-b-40">
                        <h2 class="t100">About Logpose</h2>
                        <span class="lead" itemprop="description">Action Sports tracking application "Logpose" records surfing, snowboarding, skiing's riding and records the maximum speed within the session, the distance of each riding, the speed distribution of each riding, energy consumption, heart rate.<br>
            Record and enjoy the session when you enjoyed the best wave, the best snow quality.</span>
                    </div>
                    <a class="scroll-to btn btn-rounded btn-light text-none t700" href="#SessionTracking">Feature Description</a>
                </div>
            </div>
        </div>
    </section><!-- end: Content 01 -->

    <!-- PARALLAX -->
    <section class="text-center text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg03.jpg">
        <div class="container">
            <div class="heading heading-center m-b-40">
                <h2 class="text-medium-light m-b-5">If you use Logpose app.</h2>
                <h3 class="t100">Record your session, then Let's take advantage of next it!!!</h3>
            </div>
            <a data-animation="fadeInUp" data-animation-delay="1200" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank"><img class="alpha" src="<?php echo SDEL;?>/images/appstore-dark.svg" width="160px" alt="Logpose App Store"></a>
        </div>
    </section><!-- end: PARALLAX -->

    <!-- Content 02 -->
    <section id="SessionTracking">
        <div class="container">
            <div class="row">
                <div class="col-md-6 p-t-40" data-animation="fadeInUp" data-animation-delay="1000">
                    <div class="m-b-40">
                        <h2 class="t100">Session Tracking</h2>
                        <span class="lead">Logpose will record sessions of action sports, surfing, snowboarding and skiing.</span>
                        <ul class="m-t-20">
                            <li>Riding distance</li>
                            <li>Riding speed</li>
                            <li>Difference in elevation</li>
                            <li>Active energy</li>
                            <li>Heart beat ( Display of Watch app only )</li>
                        </ul>
                    </div>
                    <a class="scroll-to btn btn-rounded btn-light text-none t700" href="#RidingAnalysis">Next Function</a>
                </div>
                <div class="col-md-6" style="height:556px">
                    <div class="post-image">
                        <div class="image-absolute" data-animation="fadeInDown" data-animation-delay="400"> <img src="<?php echo SDEL;?>/images/iphonex_session.png" alt=""> </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- end: Content 02 -->

    <!-- PARALLAX -->
    <section class="text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg01.jpg">
        <div class="container text-right">
            <div data-animation="fadeInUp" data-animation-delay="700" class="heading text-right m-b-40">
                <h2 class="text-medium-light m-b-5">If you use<br>Logpose app.</h2>
                <h3 class="t100">Record your session, then Let's take advantage of next it!!!</h3>
            </div>
        </div>
    </section><!-- end: PARALLAX -->

    <!-- Content 03 -->
    <section id="RidingAnalysis">
        <div class="container">
            <div class="row">
                <div class="col-md-6 m-b-30" style="height:556px">
                    <div class="post-image">
                        <div class="image-absolute" data-animation="fadeInLeft" data-animation-delay="400"> <img src="<?php echo SDEL;?>/images/riding_analysis.png" alt=""> </div>
                    </div>
                </div>
                <div class="col-md-6 p-t-40" data-animation="fadeInUp" data-animation-delay="1000">
                    <div class="m-b-40">
                        <h2 class="t100">Riding Analysis</h2>
                        <span class="lead">You can check the increase and decrease in speed.<br>
            You can check the speed distribution of each riding and look back on your own riding.<br>
            Save the data to the cloud server.</span>
                    </div>
                    <a class="scroll-to btn btn-rounded btn-light text-none t700" href="#dataconfirmation">Next Function</a>
                </div>
            </div>
        </div>
    </section><!-- end: Content 03 -->

    <!-- PARALLAX -->
    <section class="text-center text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg03.jpg">
        <div class="container">
            <div class="heading heading-center m-b-40">
                <h2 class="text-medium-light m-b-5">If you use Logpose app.</h2>
                <h3 class="t100">Record your session, then Let's take advantage of next it!!!</h3>
            </div>
            <a data-animation="fadeInUp" data-animation-delay="1200" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank"><img class="alpha" src="<?php echo SDEL;?>/images/appstore-dark.svg" width="160px" alt="Logpose App Store"></a>
        </div>
    </section><!-- end: PARALLAX -->

    <!-- Content 04 -->
    <section id="dataconfirmation" class="background-grey">
        <div class="container">
            <div class="row">
                <div class="col-md-6 p-t-40" data-animation="fadeInLeft" data-animation-delay="1000">
                    <div class="m-b-40">
                        <h2 class="t100">Easy data confirmation</h2>
                        <span class="lead">Even though you are in the sea, how many does your ride? What is the maximum speed during the session?<br>
            You can easily check with the Apple Watch app.</span>
                        <ul class="m-t-20">
                            <li>Riding speed</li>
                            <li>Active energy</li>
                            <li>Heart beat</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6" style="height:556px">
                    <div class="post-image">
                        <div class="image-absolute" data-animation="fadeInRight" data-animation-delay="400"> <img src="<?php echo SDEL;?>/images/watch.png" alt=""> </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- end: Content 04 -->

    <!-- Gallery Carousel -->
    <section id="Gallery" class="p-t-40 p-b-40">
        <div class="container">
            <div class="text-center">
                <hr class="space">
                <h2 class="t100">App Screenshot</h2>
            </div>
            <hr class="space">
            <div class="carousel" data-items="5" data-lightbox="gallery" data-margin="20">

                <div class="portfolio-item img-zoom pf-illustrations pf-media pf-icons pf-Media">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <img src="<?php echo SDEL;?>/images/screenshot/01s.jpg" srcset="<?php echo SDEL;?>/images/screenshot/01s.jpg 1x, <?php echo SDEL;?>/images/screenshot/01s@2x.jpg 2x" alt="Logpose home">
                        </div>
                        <div class="portfolio-description">
                            <a title="Logpose App Home" data-lightbox="gallery-item" href="<?php echo SDEL;?>/images/screenshot/01s@2x.jpg" class="btn btn-light btn-rounded">Zoom</a>
                        </div>
                    </div>
                </div>

                <div class="portfolio-item img-zoom pf-illustrations pf-media pf-icons pf-Media">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <img src="<?php echo SDEL;?>/images/screenshot/02s.jpg" srcset="<?php echo SDEL;?>/images/screenshot/02s.jpg 1x, <?php echo SDEL;?>/images/screenshot/02s@2x.jpg 2x" alt="Logpose home">
                        </div>
                        <div class="portfolio-description">
                            <a title="Logpose App Feed" data-lightbox="gallery-item" href="<?php echo SDEL;?>/images/screenshot/02s@2x.jpg" class="btn btn-light btn-rounded">Zoom</a>
                        </div>
                    </div>
                </div>

                <div class="portfolio-item img-zoom pf-illustrations pf-media pf-icons pf-Media">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <img src="<?php echo SDEL;?>/images/screenshot/03s.jpg" srcset="<?php echo SDEL;?>/images/screenshot/03s.jpg 1x, <?php echo SDEL;?>/images/screenshot/03s@2x.jpg 2x" alt="Logpose home">
                        </div>
                        <div class="portfolio-description">
                            <a title="Logpose App Data" data-lightbox="gallery-item" href="<?php echo SDEL;?>/images/screenshot/03s@2x.jpg" class="btn btn-light btn-rounded">Zoom</a>
                        </div>
                    </div>
                </div>

                <div class="portfolio-item img-zoom pf-illustrations pf-media pf-icons pf-Media">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <img src="<?php echo SDEL;?>/images/screenshot/04s.jpg" srcset="<?php echo SDEL;?>/images/screenshot/04s.jpg 1x, <?php echo SDEL;?>/images/screenshot/04s@2x.jpg 2x" alt="Logpose home">
                        </div>
                        <div class="portfolio-description">
                            <a title="Logpose App Data Locatopn Privacy" data-lightbox="gallery-item" href="<?php echo SDEL;?>/images/screenshot/04s@2x.jpg" class="btn btn-light btn-rounded">Zoom</a>
                        </div>
                    </div>
                </div>

                <div class="portfolio-item img-zoom pf-illustrations pf-media pf-icons pf-Media">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <img src="<?php echo SDEL;?>/images/screenshot/05s.jpg" srcset="<?php echo SDEL;?>/images/screenshot/05s.jpg 1x, <?php echo SDEL;?>/images/screenshot/05s@2x.jpg 2x" alt="Logpose home">
                        </div>
                        <div class="portfolio-description">
                            <a title="Logpose App Tracking Start" data-lightbox="gallery-item" href="<?php echo SDEL;?>/images/screenshot/05s@2x.jpg" class="btn btn-light btn-rounded">Zoom</a>
                        </div>
                    </div>
                </div>

                <div class="portfolio-item img-zoom pf-illustrations pf-media pf-icons pf-Media">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <img src="<?php echo SDEL;?>/images/screenshot/06s.jpg" srcset="<?php echo SDEL;?>/images/screenshot/06s.jpg 1x, <?php echo SDEL;?>/images/screenshot/06s@2x.jpg 2x" alt="Logpose home">
                        </div>
                        <div class="portfolio-description">
                            <a title="Logpose App Friend Search" data-lightbox="gallery-item" href="<?php echo SDEL;?>/images/screenshot/06s@2x.jpg" class="btn btn-light btn-rounded">Zoom</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section><!-- End: Gallery Carousel -->

    <!-- Pricing Table -->
    <section id="Pricing" itemprop="priceRange" class="background-grey p-t-40 p-b-40">
        <div class="container">
            <div class="text-center">
                <hr class="space">
                <h2 class="t100">Pricing</h2>
                <p class="lead">Use Logpose to enjoy surfing, snowboarding and skiing!</p>
            </div>
            <hr class="space">
            <div class="row">
                <div class="pricing-table">

                    <!-- Price Free -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="plan">
                            <div class="plan-header">
                                <h4 class="t400 m-b-20">Starter Plan</h4>
                                <div class="plan-price t400">FREE</div>
                            </div>
                            <div class="plan-list">
                                <ul>
                                    <li>Sync across devices Unlimited</li>
                                    <li>Number of saved sessions 5</li>
                                    <li>Session Privacy Need Pro</li>
                                    <li>Hide a location Need Pro</li>
                                    <li>Delete riding Need Pro</li>
                                </ul>
                                <div class="plan-button"> <a class="btn btn-rounded" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank">Get Started</a> </div>
                            </div>
                        </div>
                    </div><!-- end: Price Free -->

                    <!-- Price Pro Monthly -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="plan">
                            <div class="plan-header">
                                <h4 class="t400 m-b-20">Monthly Plan</h4>
                                <div class="plan-price t400"><sup>$</sup><?php echo $price_month;?> </div>
                            </div>
                            <div class="plan-list">
                                <ul>
                                    <li>Sync across devices Unlimited</li>
                                    <li>Number of saved sessions Unlimited</li>
                                    <li>Session Privacy is available</li>
                                    <li>Hide a location is available</li>
                                    <li>Delete riding is available</li>
                                </ul>
                                <div class="plan-button"> <a class="btn btn-light btn-rounded" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank">Get Started</a> </div>
                            </div>
                        </div>
                    </div><!-- end: Price Pro Monthly -->

                    <!-- Price Anualy -->
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="plan">
                            <div class="plan-header">
                                <h4 class="t400 m-b-20">Annually Plan</h4>
                                <div class="plan-price t400"><sup>$</sup><?php echo $price_year;?></div>
                            </div>
                            <div class="plan-list">
                                <ul>
                                    <li>Sync across devices Unlimited</li>
                                    <li>Number of saved sessions Unlimited</li>
                                    <li>Session Privacy is available</li>
                                    <li>Hide a location is available</li>
                                    <li>Delete riding is available</li>
                                </ul>
                                <div class="plan-button"> <a class="btn btn-light btn-rounded" href="https://itunes.apple.com/us/app/logpose/id1307451351" target="_blank">Get Started</a> </div>
                            </div>
                        </div>
                    </div><!-- end: Price Anualy -->

                </div>
            </div>

        </div>
    </section><!-- end: Pricing Table -->

    <!-- PARALLAX -->
    <section class="text-light p-t-150 p-b-150" data-parallax-image="<?php echo SDEL;?>/images/sec_bg02.jpg">
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
