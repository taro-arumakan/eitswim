<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package studioelc
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<!-- Sidebar-->
<div class="sidebar sidebar-modern col-md-3">

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

</div>
<!-- END: Sidebar-->
