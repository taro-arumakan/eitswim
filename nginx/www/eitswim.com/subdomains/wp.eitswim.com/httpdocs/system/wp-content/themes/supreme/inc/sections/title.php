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

$title_sep = get_theme_mod( 'title_sep', 'pipe' );
if($title_sep=='pipe') {
  $sep = ' | ';
} else {
  $sep = ' - ';
}
?>
<ul>
<li>
<p class="control-title"><?php echo __( 'Separator of title', 'luxeritas' ); ?></p>
<p class="radio">
<input type="radio" value="pipe" name="title_sep"<?php thk_value_check( 'title_sep', 'radio', 'pipe' ); ?> />
<?php echo '|&nbsp;&nbsp;&nbsp;( ' . __( 'The pipe symbol', 'luxeritas' ) . ' )'; ?>
</p>
<p class="radio">
<input type="radio" value="hyphen" name="title_sep"<?php thk_value_check( 'title_sep', 'radio', 'hyphen' ); ?> />
<?php echo '&#045;&nbsp;&nbsp;&nbsp;( ' . __( 'The hyphen symbol', 'luxeritas' ) . ' )'; ?>
</p>
</li>
<li>
<p class="control-title"><?php echo __( 'Page title when Front Page is post pages', 'luxeritas' ); ?></p>
<p><?php echo __( 'SEO Title', 'luxeritas' ); ?><br>
<input type="text" value="<?php thk_value_check( 'top_seo_title', 'text' ); ?>" name="top_seo_title" />
</p>
<p class="radio">
<input type="radio" value="site" name="title_top_list"<?php thk_value_check( 'title_top_list', 'radio', 'site' ); ?> />
<?php echo __( 'Site name', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="site_catch" name="title_top_list"<?php thk_value_check( 'title_top_list', 'radio', 'site_catch' ); ?> />
<?php echo __( 'Site name', 'luxeritas' ) . $sep . __( 'Tagline', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="catch_site" name="title_top_list"<?php thk_value_check( 'title_top_list', 'radio', 'catch_site' ); ?> />
    <?php echo __( 'Tagline', 'luxeritas' ) . $sep . __( 'Site name', 'luxeritas' ); ?>
</p>
</li>

<?php if(false):?>
<li>
<p class="control-title"><?php echo __( 'Page title when Front Page is static pages', 'luxeritas' ); ?></p>
<p class="radio">
<input type="radio" value="site" name="title_front_page"<?php thk_value_check( 'title_front_page', 'radio', 'site' ); ?> />
<?php echo __( 'Site name', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="site_catch" name="title_front_page"<?php thk_value_check( 'title_front_page', 'radio', 'site_catch' ); ?> />
<?php echo __( 'Site name', 'luxeritas' ) . $sep . __( 'Tagline', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="site_title" name="title_front_page"<?php thk_value_check( 'title_front_page', 'radio', 'site_title' ); ?> />
<?php echo __( 'Site name', 'luxeritas' ) . $sep . __( 'Page title', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="title_site" name="title_front_page"<?php thk_value_check( 'title_front_page', 'radio', 'title_site' ); ?> />
<?php echo __( 'Page title', 'luxeritas' ) . $sep . __( 'Site name', 'luxeritas' ); ?>
</p>
</li>
<?php endif;?>

<li>
<p class="control-title"><?php echo __( 'Other page title', 'luxeritas' ); ?></p>
<p class="radio">
<input type="radio" value="title" name="title_other"<?php thk_value_check( 'title_other', 'radio', 'title' ); ?> />
<?php echo __( 'Page title', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="site_title" name="title_other"<?php thk_value_check( 'title_other', 'radio', 'site_title' ); ?> />
<?php echo __( 'Site name', 'luxeritas' ) . $sep . __( 'Page title', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="title_site" name="title_other"<?php thk_value_check( 'title_other', 'radio', 'title_site' ); ?> />
<?php echo __( 'Page title', 'luxeritas' ) . $sep . __( 'Site name', 'luxeritas' ); ?>
</p>
</li>
</ul>
<script>
  jQuery(function($) {
    $('input[name=title_sep]').on('click', function(){
      var val = $(this).val();
      if(val == 'hyphen') {
        var text = $('input[value=site_catch]').parent('p').html();
        text = text.replace('|', '-');
        $('input[value=site_catch]').parent('p').html(text);
        var text = $('input[value=catch_site]').parent('p').html();
        text = text.replace('|', '-');
        $('input[value=catch_site]').parent('p').html(text);
        var text = $('input[value=site_title]').parent('p').html();
        text = text.replace('|', '-');
        $('input[value=site_title]').parent('p').html(text);
        var text = $('input[value=title_site]').parent('p').html();
        text = text.replace('|', '-');
        $('input[value=title_site]').parent('p').html(text);
      } else {
        var text = $('input[value=site_catch]').parent('p').html();
        text = text.replace('-', '|');
        $('input[value=site_catch]').parent('p').html(text);
        var text = $('input[value=catch_site]').parent('p').html();
        text = text.replace('-', '|');
        $('input[value=catch_site]').parent('p').html(text);
        var text = $('input[value=site_title]').parent('p').html();
        text = text.replace('-', '|');
        $('input[value=site_title]').parent('p').html(text);
        var text = $('input[value=title_site]').parent('p').html();
        text = text.replace('-', '|');
        $('input[value=title_site]').parent('p').html(text);
      }
    });
  });

</script>