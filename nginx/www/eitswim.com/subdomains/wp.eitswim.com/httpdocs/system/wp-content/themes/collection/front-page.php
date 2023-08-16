<?php

$news_count = get_option( 'elc_news_page_news_count', 10 );
$news_count = 3;

    /** 出力側の変換機能を無効化する **/
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );

get_header();

?>
<!-- Page title -->
<section id="page-title" class="page-title-center p-t-80 p-b-80 dark" style="background:#fff; border:none;">
  <div class="container">
    <div class="page-title">
      <h1 style="font-size:24px; line-height:28px; letter-spacing:0.05em;">COLLECTION</h1>
    </div>
    <div class="breadcrumb">
      <ul>
        <li><a href="/">HOME</a></li>
        <li class="active">COLLECTION</li>
      </ul>
    </div>
  </div>
</section><!-- end: Page title -->

<!-- Content -->
<section id="page-content" class="p-b-0">
  <div class="container">
    <!-- post content -->

    <!-- Colection list -->
    <div class="grid-layout post-2-columns" data-item="post-item">

      <?php switch_to_blog(1) ?>
      <?php
      $menu_name = 'left-nav';
      if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
        $left_menu_list = '';
        $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        //階層化の配列に変換する必要あり
        $menus = convert_nav_menu_array($menu_items);
        restore_current_blog();
        foreach($menus as $item) {
          if(count($item)>1) {
            $index = 0;
            foreach($item as $item2) {
              $index += 1;
              if($index>1) {
                $image = get_category_image($item2->url);
                echo <<<EOF
      <!-- Collection -->
      <div class="post-item border">
        <div class="post-item-wrap">
          <div class="post-image">
            <a href="{$item2->url}"> <img alt="" src="{$image}"> </a>
          </div>
          <div class="post-item-description">
            <h2><a href="{$item2->url}">{$item2->title}</a></h2>
          </div>
        </div>
      </div><!-- end: Collection -->
EOF;
              }
            }
          }
        }
      }
      ?>
    </div><!-- end: Colection list -->

  </div><!-- end: post content -->

</section><!-- end: Content -->

<?php
get_footer();
?>
