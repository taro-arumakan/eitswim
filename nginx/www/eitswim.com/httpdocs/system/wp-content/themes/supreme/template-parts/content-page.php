<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package studioelc
 */

/**<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> **/
?>


<article
	<?php
	the_content();

	?>
</article><!-- #post-## -->
