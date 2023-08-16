<?php
$title = $this->title;
if(!empty($model->seo_title)) {
    //Contact Us - Eitswim
	$title = $model->seo_title;
}

$title = 'お問い合せ | eit swim';
if(!empty($_GET['kind'])) {
    if($_GET['kind']=='conf') {
	    $title = '内容確認 | eit swim';
    }
	if($_GET['kind']=='complete') {
		$title = '送信完了 | eit swim';
	}
}
;?><!DOCTYPE html>
<html lang="ja">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="author" content="Agentblue Inc.">
  <!-- Document Title -->
  <title><?php echo $title;?></title>
  <?php if(!empty($model->seo_description)):?>
  <meta name="description" content="<?php echo $model->seo_description;?>">
  <?php else:?>
  <meta name="description" content=" ">
  <?php endif;?>
  <?php if(!empty($model->seo_keywords)):?>
  <meta name="keywords" content="<?php echo $model->seo_keywords;?>">
  <?php else:?>
  <meta name="keywords" content=" ">
  <?php endif;?>
  <!-- favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="/system/wp-content/themes/eitswim/images/favicon.ico">
  <link rel="icon" type="image/png" href="/system/wp-content/themes/eitswim/images/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="/system/wp-content/themes/eitswim/images/apple-touch-icon-precomposed.png">
  <link rel="canonical" href="<?php echo get_current_url();?>" />
  <link rel="alternate" href="<?php echo get_current_url();?>" hreflang="ja">
  <!-- Stylesheets & Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700|Open+Sans:300,400,600,700,800|Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet" type="text/css">
  <link href="/system/wp-content/themes/eitswim/css/plugins.css" rel="stylesheet">
  <link href="/system/wp-content/themes/eitswim/css/custom.css" rel="stylesheet">
  <link href="/system/wp-content/themes/eitswim/css/style.css" rel="stylesheet">
  <link href="/system/wp-content/themes/eitswim/css/responsive.css" rel="stylesheet">
</head>
