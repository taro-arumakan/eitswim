<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package elc
 */

get_header(); ?>

  <!-- SECTION HOME  -->
  <section class="parallax fullscreen" data-height-xs="568" data-parallax-image="<?php echo SDEL; ?>/images/slider01.jpg">
    <div class="container container-fullscreen">
      <div class="text-middle text-left">
        <div class="container p-b-100">
          <h1 class="text-large-light t700" style="color:#dca17e !important;">404 Not Found</h1>
          <p class="lead" style="color:#dca17e !important;">Ooops, This Page Could Not Be Found!</p>
          <p class="lead" style="color:#dca17e !important;">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
          <a class="btn btn-light btn-rounded m-t-40" href="/">Back to Home Screen</a>
        </div>
      </div>
    </div>
  </section>
  <!-- end: SECTION HOME -->

<?php
//get_sidebar();
get_footer();
