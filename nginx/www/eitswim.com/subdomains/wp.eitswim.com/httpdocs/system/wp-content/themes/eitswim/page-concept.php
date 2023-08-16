<?php
/**
Template Name:CONCEPT用(パンくず無し）
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

<!-- Page Content -->
<section id="page-content" class="p-b-0">
  <div class="container">
    <!-- content -->
    <div class="content col-md-9 center">
      <article><!-- このarticleタグは、テンプレート側 -->
        <?php
        /** 出力側の変換機能を無効化する **/
        remove_filter( 'the_content', 'wpautop' );
        remove_filter( 'the_content', 'wptexturize' );
        while ( have_posts() ) : the_post();

          //get_template_part( 'template-parts/content', 'page' );
          the_content();
        endwhile; // End of the loop.
        ?>
      </article><!-- このarticleタグは、テンプレート側 -->
    </div><!-- end: content -->
  </div>
</section><!-- end: Page Content -->

<?php
//get_sidebar();
get_footer();
?>

