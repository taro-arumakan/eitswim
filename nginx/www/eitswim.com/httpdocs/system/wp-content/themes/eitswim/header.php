<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package eitswim
 */
compress_start();
global $html_lang;
global $luxe;
//if( !isset( $content_width ) ) $content_width = 640;	// これ無いとチェックで怒られる

global $add_class;
global $add_title_class;
global $add_header_class;

$template = get_post_meta(get_the_ID(), '_wp_page_template', true);

?>
<!DOCTYPE html>
<html lang="<?php echo $html_lang; ?>">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="author" content="Agentblue Inc.">

  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->

  <!-- favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo SDEL; ?>/images/favicon.ico">
  <link rel="icon" type="image/png" href="<?php echo SDEL; ?>/images/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="<?php echo SDEL; ?>/images/apple-touch-icon-precomposed.png<?php file_ver(SDEL . '/images/apple-touch-icon-precomposed.png') ?>">
  <!-- Stylesheets & Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700|Open+Sans:300,400,600,700,800|Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet" type="text/css">
  <link href="<?php echo SDEL; ?>/css/plugins.css<?php file_ver(SDEL . '/css/plugins.css') ?>" rel="stylesheet">
  <link href="<?php echo SDEL; ?>/css/custom.css<?php file_ver(SDEL . '/css/custom.css') ?>" rel="stylesheet">
  <link href="<?php echo SDEL; ?>/css/style.css<?php file_ver(SDEL . '/css/style.css') ?>" rel="stylesheet">
  <link href="<?php echo SDEL; ?>/css/responsive.css<?php file_ver(SDEL . '/css/responsive.css') ?>" rel="stylesheet">
  <!-- load header -->
  <?php echo apply_filters('thk_head', ''); ?>
</head>

<?php if (is_404()) : ?>

  <body>
  <?php else : ?>

    <body <?php echo site_name_type(); ?>>
    <?php endif; ?>
    <!-- Wrapper -->
    <div id="wrapper">
      <!-- HEADER -->
      <header id="header" class="header-logo-center header-sticky-resposnive">
        <div id="header-wrap">
          <div class="container">
            <!--Logo-->
            <div id="logo">
              <a itemprop="url" href="/" class="logo" data-dark-logo="<?php echo SDEL; ?>/images/logo-dark.png<?php file_ver(SDEL . '/images/logo-dark.png') ?>"> <img itemprop="logo" src="<?php echo SDEL; ?>/images/logo.png<?php file_ver(SDEL . '/images/logo.png') ?>" alt="eit swim Logo"> </a>
            </div><!--End: Logo-->

            <!--Navigation Resposnive Trigger-->
            <div id="mainMenu-trigger">
              <button class="lines-button x"> <span class="lines"></span> </button>
            </div><!--end: Navigation Resposnive Trigger-->

            <!--shop icon on mobile -->
            <div id="shop-icon">
              <a itemprop="url" href="https://shop.eitswim.com" target="_blank" class="shop" data-dark-logo="<?php echo SDEL; ?>/images/shop.png<?php file_ver(SDEL . '/images/shop.png') ?>"> <img itemprop="logo" src="<?php echo SDEL; ?>/images/shop.png<?php file_ver(SDEL . '/images/shop.png') ?>" alt="eitswim shop"></a>
            </div>

            <!--Navigation-->
            <div id="mainMenu" class="menu-hover-background light">
              <div class="container">
                <nav>
                  <!--Left menu-->
                  <?php
                  $menu_name = 'left-nav';
                  if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
                    $left_menu_list = '';
                    $menu = wp_get_nav_menu_object($locations[$menu_name]);
                    $menu_items = wp_get_nav_menu_items($menu->term_id);
                    //階層化の配列に変換する必要あり
                    $menus = convert_nav_menu_array($menu_items);
                    echo <<<EOF
<ul>
EOF;
                    foreach ($menus as $item) {
                      if (count($item) > 1) {
                        echo <<<EOF
  <li class="dropdown">
EOF;
                        $index = 0;
                        foreach ($item as $item2) {
                          $index += 1;
                          if ($index == 1) {
                            echo <<<EOF
  <a href="{$item2->url}">{$item2->title}</a>
    <ul class="dropdown-menu">
EOF;
                          } else {
                            $last = '';
                            if ($index == count($item)) {
                              $last = ' class="last-child"';
                            }
                            echo <<<EOF
  <li{$last}><a href="{$item2->url}">{$item2->title}</a></li>
EOF;
                          }
                        }
                        echo <<<EOF
    </ul>
  </li>
EOF;
                      } else {
                        foreach ($item as $item3) {
                          echo <<<EOF
  <li><a href="{$item3->url}">{$item3->title}</a></li>
EOF;
                        }
                      }
                    }
                    echo <<<EOF
</ul>
EOF;
                  }
                  ?>
                  <!--Right Menu-->
                  <?php
                  $menu_name = 'right-nav';
                  if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
                    $left_menu_list = '';
                    $menu = wp_get_nav_menu_object($locations[$menu_name]);
                    $menu_items = wp_get_nav_menu_items($menu->term_id);
                    //階層化の配列に変換する必要あり
                    $menus = convert_nav_menu_array($menu_items);
                    echo <<<EOF
<ul>
EOF;
                    foreach ($menus as $item) {
                      if (count($item) > 1) {
                        echo <<<EOF
  <li class="dropdown">
EOF;
                        $index = 0;
                        foreach ($item as $item2) {
                          $index += 1;
                          if ($index == 1) {
                            echo <<<EOF
  <a href="{$item2->url}">{$item2->title}</a>
    <ul class="dropdown-menu">
EOF;
                          } else {
                            $last = '';
                            if ($index == count($item)) {
                              $last = ' class="last-child"';
                            }
                            echo <<<EOF
  <li{$last}><a href="{$item2->url}">{$item2->title}</a></li>
EOF;
                          }
                        }
                        echo <<<EOF
    </ul>
  </li>
EOF;
                      } else {
                        foreach ($item as $item3) {
                          echo <<<EOF
  <li><a href="{$item3->url}">{$item3->title}</a></li>
EOF;
                        }
                      }
                    }
                    echo <<<EOF
</ul>
EOF;
                  }
                  ?>
                </nav>
              </div>
            </div><!--end: Navigation-->

          </div>
        </div>
      </header><!--END: HEADER-->

      <?php if (is_front_page() && is_home()) : ?>
      <?php elseif (is_404()) : ?>
      <?php else : ?>
      <?php endif; ?>