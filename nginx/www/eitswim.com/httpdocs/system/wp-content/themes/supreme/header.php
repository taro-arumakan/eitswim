<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package studioelc
 */

global $luxe;
if( !isset( $content_width ) ) $content_width = 1280;	// これ無いとチェックで怒られる

?><!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="author" content="Studio E.L.C Inc.">
  <!-- Document title -->
  <!-- Stylesheets & Fonts -->
  <link rel="shortcut icon" type="image/png" href="<?php echo TDEL; ?>/images/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="<?php echo TDEL; ?>/images/apple-touch-icon-precomposed.png">
    <link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans|Raleway" rel="stylesheet" type="text/css">
  <link href="<?php echo TDEL; ?>/css/plugins.css" rel="stylesheet">
  <link href="<?php echo TDEL; ?>/css/style.css" rel="stylesheet">
  <link href="<?php echo TDEL; ?>/css/responsive.css" rel="stylesheet">

  <?php echo apply_filters( 'thk_head', '' );	// load header; ?>
</head>

<body>

<!-- Wrapper -->
<div id="wrapper">

  <?php if (!is_maintenance()): ?>
  <!--HEADER-->
  <header id="header" class="header-transparent header-fullwidth header-plain dark header-sticky-resposnive">
    <div id="header-wrap">
      <div class="container">
        <?php
        // Structured data of site information.
        $site_name_type = '';
        if( isset( $luxe['site_name_type'] ) ) {
          if( $luxe['site_name_type'] === 'Organization' ) {
            if( isset( $luxe['organization_type'] ) ) {
              $site_name_type = ' itemscope itemtype="https://schema.org/' . $luxe['organization_type'] . '"';
            }
          }
          else {
            $site_name_type = ' itemscope itemtype="https://schema.org/' . $luxe['site_name_type'] . '"';
          }
        }
        ?>
        <!--Logo-->
        <div id="logo" <?php echo $site_name_type;?>>
          <a itemprop="url" href="<?php echo home_url('/'); ?>" class="logo" data-dark-logo="<?php echo TDEL; ?>/images/logo-dark.png">
            <img itemprop="logo" src="<?php echo TDEL; ?>/images/logo.png" alt="Studio E.L.C Inc. Logo"></a>
        </div><!--End: Logo-->

        <!--Navigation Resposnive Trigger-->
        <div id="mainMenu-trigger">
          <button class="lines-button x"> <span class="lines"></span> </button>
        </div><!--end: Navigation Resposnive Trigger-->

        <!--Navigation-->
        <div id="mainMenu" class="menu-hover-background light">
          <?php echo
          wp_nav_menu(
            array(
              'theme_location' => 'global-nav',
              'container' => 'div',
              'container_class' => 'container',
              'depth' => '0',
              'echo' => false,
              'items_wrap' => '<nav><ul>%3$s</ul></nav>',
            )
          )
          ?>
        </div><!--end: Navigation-->
      </div>
    </div>
  </header><!--END: HEADER-->
  <?php endif;?>

  <?php if (!is_maintenance()): ?>
	  <?php global $no_title;?>
	  <?php if (( is_front_page() && is_home() ) ||
						is_404() ||
						is_maintenance() ||
						!empty($no_title) ||
						$name == 'profile'): ?>

	  <?php else : ?>

		  <?php if(false):?>
		<!-- SECTION IMAGE FULLSCREEN -->
		<section class="halfscreen parallax" style="background: url('/images/titlebg.jpg') 50% 50% / cover no-repeat;" data-stellar-background-ratio="0.5">
			<div class="container-fluid">
				<div class="container-fullscreen">
					<div class="text-middle text-center text-light">
						<h1 class="text-uppercase text-medium"><?php echo get_title();//the_title();?></h1>
						<?php if(is_single()):?>
						<div class="post-date">
							<span class="post-date-day"><?php echo get_post_time('j');?></span>
							<span class="post-date-month"><?php echo get_post_time('F');?></span>
							<span class="post-date-year"><?php echo get_post_time('Y');?></span>
						</div>
						<?php endif;?>
						<div class="page-title-center">
							<div class="breadcrumb">
								<ul>
									<?php echo elc_breadcrumb();?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- END: SECTION IMAGE FULLSCREEN -->
		<?php endif;?>

	  <?php endif;?>
	<?php endif;?>

