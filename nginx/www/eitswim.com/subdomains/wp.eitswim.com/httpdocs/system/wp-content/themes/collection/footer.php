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
?>
  <!-- FOOTER -->
  <footer id="footer" class="p-t-50 p-b-50">
    <div class="footer-content">
      <div class="container">
        <div class="row text-center">
          <!-- Social -->
          <div class="clearfix">
            <div class="center social-icons social-icons-medium social-icons-rounded social-icons-colored-hover">
              <?php
              //Facebook URL	text
              //$facebook = get_option( 'elc_top_page_facebook_url' );
              //Instagram URL	text
              switch_to_blog(1);
              $instagram = get_option( 'elc_top_page_instagram_url' );
              restore_current_blog();
              //Twitter URL	text
              //$twitter = get_option( 'elc_top_page_twitter_url' );
              ?>
              <ul>
                <li class="social-instagram"><a href="<?php echo $instagram;?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
              </ul>
            </div><!-- end: Social -->
          </div>
          <div class="copyright-text text-center p-t-0">
            <p class="m-b-0"><!--<a href="/news">NEWS</a> /--> <a href="/privacy">PRIVACY POLICY</a></p>
            <?php echo isset( $luxe['copyright'] ) ? $luxe['copyright'] : ''; ?>
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
  <script src="<?php echo SDEL; ?>/js/plugins.js<?php file_ver(SDEL.'/js/plugins.js') ?>"></script>

  <!-- functions JS -->
  <script src="<?php echo SDEL; ?>/js/functions.js<?php file_ver(SDEL.'/js/functions.js') ?>"></script>

<?php wp_footer(); ?>
<?php if ( is_admin_bar_showing()):?>
  <link rel='stylesheet' id='dashicons-css'  href='<?php echo includes_url();?>css/dashicons.min.css?ver=<?php echo get_bloginfo('version');?>' type='text/css' media='all' />
  <link rel='stylesheet' id='admin-bar-css'  href='<?php echo includes_url();?>css/admin-bar.min.css?ver=<?php echo get_bloginfo('version');?>' type='text/css' media='all' />
<?php endif;?>
  </body>
  </html>
<?php
compress_end();

