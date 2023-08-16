<?php
/**
 * TAG用テンプレート
 * The template for displaying all pages
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

global $is_category;
$is_category = false;
global $is_archive;
$is_archive = false;
global $is_tag;
$is_tag = true;
global $is_news;
$is_news = false;

include(STYLESHEETPATH.'/index.php');
