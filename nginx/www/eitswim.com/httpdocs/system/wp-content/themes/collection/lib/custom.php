<?php
/**
 * Luxeritas WordPress Theme - free/libre wordpress platform
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @copyright Copyright (C) 2015 Thought is free.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @author LunaNuko
 * @link https://thk.kanzae.net/
 * @translators rakeem( http://rakeem.jp/ )
 */

add_action( 'customize_register', function( $wp_customize ) {
  $wp_customize->add_section( 'top_page_section', array(
    'title'		=> __( 'トップページ設定', 'luxeritas' ),
    'description'	=> '<p class="bold f11em mm15b">' . __( 'Top page setting', 'luxeritas' ) . '</p>',
    'priority'	=> 27
  ) );
  //Facebook URL	text
  $wp_customize->add_setting( 'elc_top_page_facebook_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_facebook_url', array(
    'settings'	=> 'elc_top_page_facebook_url',
    'label'		=> __( 'Footer Facebook URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

  //Instagram+ URL	text
  $wp_customize->add_setting( 'elc_top_page_instagram_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_instagram_url', array(
    'settings'	=> 'elc_top_page_instagram_url',
    'label'		=> __( 'Footer Instagram URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

  //Google+ URL	text
  $wp_customize->add_setting( 'elc_top_page_google_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_google_url', array(
    'settings'	=> 'elc_top_page_google_url',
    'label'		=> __( 'Footer Google URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

  //Twitter URL	text
  $wp_customize->add_setting( 'elc_top_page_twitter_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_twitter_url', array(
    'settings'	=> 'elc_top_page_twitter_url',
    'label'		=> __( 'Footer Twitter URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

  //Vimeo URL	text
  $wp_customize->add_setting( 'elc_top_page_vimeo_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_vimeo_url', array(
    'settings'	=> 'elc_top_page_vimeo_url',
    'label'		=> __( 'Footer Vimeo URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

  //Youtube URL	text
  $wp_customize->add_setting( 'elc_top_page_youtube_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_youtube_url', array(
    'settings'	=> 'elc_top_page_youtube_url',
    'label'		=> __( 'Footer Youtube URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

  //rss URL	text
  $wp_customize->add_setting( 'elc_top_page_rss_url', array(
    'default'	=> '',
    'type'      => 'option',
    'transport' => 'postMessage',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_top_page_rss_url', array(
    'settings'	=> 'elc_top_page_rss_url',
    'label'		=> __( 'Footer rss URL', 'studioelc' ),
    'description'	=> '<p class="f09em">' . __( '', 'studioelc' ) . '</p>',
    'section'	=> 'top_page_section',
    'type'		=> 'text',
    'priority'	=> 100
  ) );

if(false):
  //---------------------------------------------------------------------------
  // NEWSページ
  //---------------------------------------------------------------------------
  $wp_customize->add_section( 'news_page_section', array(
    'title'		=> __( 'プログページ設定', 'luxeritas' ),
    'description'	=> '<p class="bold f11em mm15b">' . __( 'News page setting', 'luxeritas' ) . '</p>',
    'priority'	=> 27
  ) );

  /**
  // News 背景画像
  $wp_customize->add_setting( 'elc_news_page_news_img', array(
  'default'	=> null,
  'type'      => 'option',
  'sanitize_callback' => 'thk_sanitize_url'
  ) );
  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'elc_news_page_news_img', array(
  'settings'	=> 'elc_news_page_news_img',
  'label'		=> __( 'News タイトル画像', 'luxeritas' ),
  'description'	=> __( '* タイトルに背景画像を表示します。(1920 x 1280).', 'luxeritas' ),
  'section'	=> 'news_page_section',
  'priority'	=> 5
  ) ) );
   */

  //NEWSタイトル	text
  $wp_customize->add_setting( 'elc_news_page_news_title', array(
    'default'	=> 'Media',
    'type'      => 'option',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_news_page_news_title', array(
    'settings'	=> 'elc_news_page_news_title',
    'label'		=> __( 'Media', 'luxeritas' ),
    'description'	=> '<p class="m0t">' . __( '', 'luxeritas' ) . '</p>',
    'section'	=> 'news_page_section',
    'type'		=> 'text',
    'priority'	=> 10
  ) );
  //NEWS slug	text
  $wp_customize->add_setting( 'elc_news_page_news_slug', array(
    'default'	=> 'media',
    'type'      => 'option',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_news_page_news_slug', array(
    'settings'	=> 'elc_news_page_news_slug',
    'label'		=> __( 'Blog slug', 'luxeritas' ),
    'description'	=> '<p class="m0t">' . __( '', 'luxeritas' ) . '</p>',
    'section'	=> 'news_page_section',
    'type'		=> 'text',
    'priority'	=> 20
  ) );
  //NEWS meta description	text
  $wp_customize->add_setting( 'elc_news_page_news_description', array(
    'default'	=> '',
    'type'      => 'option',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_news_page_news_description', array(
    'settings'	=> 'elc_news_page_news_description',
    'label'		=> __( 'meta description', 'luxeritas' ),
    'description'	=> '<p class="m0t">' . __( '', 'luxeritas' ) . '</p>',
    'section'	=> 'news_page_section',
    'type'		=> 'text',
    'priority'	=> 30
  ) );
  //NEWS meta keywords	text
  $wp_customize->add_setting( 'elc_news_page_news_keywords', array(
    'default'	=> '',
    'type'      => 'option',
    'sanitize_callback' => 'thk_sanitize'
  ) );
  $wp_customize->add_control( 'elc_news_page_news_keywords', array(
    'settings'	=> 'elc_news_page_news_keywords',
    'label'		=> __( 'meta keywords', 'luxeritas' ),
    'description'	=> '<p class="m0t">' . __( '', 'luxeritas' ) . '</p>',
    'section'	=> 'news_page_section',
    'type'		=> 'text',
    'priority'	=> 40
  ) );
endif;

});

//ショートコード
add_shortcode('IMAGE_PATH',function($atts){
  return SDEL.'/';
});
