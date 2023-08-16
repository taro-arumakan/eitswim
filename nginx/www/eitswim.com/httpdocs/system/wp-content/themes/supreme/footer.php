<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package studioelc
 */

global $luxe;
global $is_news;

$is_404 = is_404();
if($is_news === true) {
  $is_404 = false;
}
?>

<?php
//
$menu_name = 'footer-link';
$menu_list = '<a href="/company/">会社概要</a>　<a href="/privacy/">個人情報保護方針</a>';
if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
  $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
  $menu_items = wp_get_nav_menu_items($menu->term_id);
  if(!empty($menu_items)) $menu_list = '';
  foreach($menu_items as $item) {
    $menu_list .= "<a href=\"{$item->url}\">{$item->title}</a>　";
  }
}
?>

<?php if (!is_maintenance() && !$is_404): ?>

  <?php
  global $wp_query;
  $postid = $wp_query->post->ID;
  $action = get_post_meta($postid, 'radio_call_to_action', true);
  if($is_news === true) {
    $action = get_option( 'elc_news_page_news_contact', true );
  }

  $call_to_action_img = get_post_meta( $postid, 'elc_call_to_action_image' );
  if(empty($call_to_action_img)) {
    $call_to_action_img = TDEL . '/images/visual01.jpg';
  } else {
    $call_to_action_img = $call_to_action_img[0];
  }

  ?>
  <?php if (($action == 'ON') || elc_is_home()): ?>
    <?php
    $title = get_post_meta($postid, 'elc_call_to_action_title', true);
    $caption = get_post_meta($postid, 'elc_call_to_action_caption', true);
    $link = get_post_meta($postid, 'elc_call_to_action_link', true);
    ?>
    <!--call-to-action -->
    <div class="call-to-action cta-center background-image m-b-0" style="background-image:url(<?php echo $call_to_action_img; ?>)">
      <div class="col-md-10">
        <?php if($title):?>
        <h3 class="text-light"><?php echo $title;?></h3>
        <?php else:?>
        <h3 class="text-light">Webサイト制作、運用、販促物、ブランディングデザイン</h3>
        <?php endif;?>
        <?php if($caption):?>
        <p class="text-light"><?php echo $caption;?></p>
        <?php else:?>
        <p class="text-light">どんな些細なことでも構いません、お気軽にお問い合せください。</p>
        <?php endif;?>
      </div>
      <div class="col-md-2">
        <?php if($link):?>
        <a class="btn btn-light btn-outline" href="<?php echo $link;?>">Contact us now!</a>
        <?php else:?>
        <a class="btn btn-light btn-outline" href="/contact">Contact us now!</a>
        <?php endif;?>
      </div>
    </div><!--END: call-to-action -->
  <?php endif; wp_reset_query(); ?>

<?php endif;?>

  <!-- FOOTER -->
  <footer class="background-grey" id="footer">
    <div class="footer-content">
      <div class="container">
        <div class="row text-center">
          <a href="<?php echo home_url('/');?>"><img src="<?php echo TDEL; ?>/images/logo2.png" width="128" alt="株式会社スタジオイーエルシー"></a>
          <div class="copyright-text text-center">
            <?php echo isset( $luxe['copyright'] ) ? $luxe['copyright'] : ''; ?><br>
            <?php echo $menu_list;?>
          </div>
          <?php
          //Google+ URL	text
          $google = get_option( 'elc_top_page_google_url' );
          //Facebook URL	text
          $facebook = get_option( 'elc_top_page_facebook_url' );
          //Twitter URL	text
          $twitter = get_option( 'elc_top_page_twitter_url' );

          ?>
          <div class="social-icons center">
            <ul>
              <?php if(!empty($facebook)):?>
              <li class="social-facebook"><a target="_blank" href="<?php echo $facebook;?>"><i class="fa fa-facebook"></i></a></li>
              <?php endif;?>
              <?php if(!empty($twitter)):?>
              <li class="social-twitter"><a target="_blank" href="<?php echo $twitter;?>"><i class="fa fa-twitter"></i></a></li>
              <?php endif;?>
              <?php if(!empty($google)):?>
                <li class="social-gplus"><a target="_blank" href="<?php echo $google;?>"><i class="fa fa-google-plus"></i></a></li>
              <?php endif;?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer><!-- END: FOOTER -->

  </div><!-- end: Wrapper -->

<!-- Go to top button -->
<a id="goToTop"><i class="fa fa-angle-up top-icon"></i><i class="fa fa-angle-up"></i></a>
<!--Plugins-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="<?php echo TDEL; ?>/js/plugins.js" defer></script>

<!-- functions JS -->
<script src="<?php echo TDEL; ?>/js/functions.min.js" defer></script>
<script src="<?php echo TDEL; ?>/js/ga.js"></script>
<script id="tagjs" type="text/javascript">
  (function () {
    var tagjs = document.createElement("script");
    var s = document.getElementsByTagName("script")[0];
    tagjs.async = true;
    tagjs.src = "//s.yjtag.jp/tag.js#site=YWk5A2I";
    s.parentNode.insertBefore(tagjs, s);
  }());
</script>
<noscript>
  <iframe src="//b.yjtag.jp/iframe?c=YWk5A2I" width="1" height="1" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
</noscript>

<?php wp_footer(); ?>
<?php if ( is_admin_bar_showing()):?>
  <link rel='stylesheet' id='dashicons-css'  href='<?php echo includes_url();?>css/dashicons.min.css?ver=<?php echo get_bloginfo('version');?>' type='text/css' media='all' />
  <link rel='stylesheet' id='admin-bar-css'  href='<?php echo includes_url();?>css/admin-bar.min.css?ver=<?php echo get_bloginfo('version');?>' type='text/css' media='all' />
<?php endif;?>

</body>
</html>
