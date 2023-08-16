<?php
/**
Template Name: MEDIA用テンプレート

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

$cat_ID = 0;
if(!empty($cat)) {
  $cat_ID = $cat;
}
//カスタム投稿タイプを取得
$type = get_custom_filed_name($cat_ID);
if(!empty($type) && $type == 'city') {
  include(STYLESHEETPATH.'/category-city.php');
} else if(!empty($type) && $type == 'surf') {
  include(STYLESHEETPATH.'/category-surf.php');
} else {
}
