<?php
/**
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

get_header();

$parallax_image = '';
$elc_title_image = get_post_meta($post->ID,'elc_title_image',true);
if(!empty($elc_title_image)) {
  $parallax_image = "data-parallax-image=\"{$elc_title_image}\"";
}

$sub_title = get_post_meta($post->ID, 'elc_sub_title', true);

?>

	<!-- Page title -->
  <section id="page-title" class="page-title-center text-light" <?php echo $parallax_image;?>>
		<div class="container">
			<div class="page-title text-light">
				<div data-animation="fadeIn">
          <?php if(!empty($sub_title)):?>
          <span class="post-meta-category"><?php echo $sub_title;?></span>
          <?php endif;?>
          <?php echo elc_get_title('h1', '');?>
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

<?php
while ( have_posts() ) : the_post();
	//get_template_part( 'template-parts/content', 'page' );
	the_content();
endwhile; // End of the loop.
?>

	</div>
	</section><!-- end: Page Content -->

<?php
//get_sidebar();
get_footer();

$add_js = get_post_meta($post->ID, 'elc_add_js', true);
$script = get_post_meta($post->ID, 'elc_add_js_content', true);
//
?>
<?php if(!empty($add_js)):?>
  <script src="<?php echo TDEL; ?>/js/jquery.validate.min.js"></script>
  <script>
    jQuery(function($) {
      $().ready(function() {
<?php echo $script; ?>
      });
    });
  </script>
<?php endif;?>
