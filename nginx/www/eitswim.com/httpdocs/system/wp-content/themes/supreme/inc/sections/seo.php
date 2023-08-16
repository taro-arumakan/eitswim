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

?>
<ul>
<li>
<p class="control-title">言語設定</p>
<p class="label-title">lang</p>
<input type="text" value="<?php thk_value_check( 'html_lang', 'text' ); ?>" name="html_lang" />
<p class="label-title">hreflang</p>
<input type="text" value="<?php thk_value_check( 'html_hreflang', 'text' ); ?>" name="html_hreflang" />
</li>
<li>
<p class="control-title"><?php echo __( 'To add tags', 'luxeritas' ); ?></p>
<p class="checkbox">
<input type="checkbox" value="" name="canonical_enable"<?php thk_value_check( 'canonical_enable', 'checkbox' ); ?> />
<?php printf( __( 'Add %s', 'luxeritas' ), 'canonical' . ' ' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="next_prev_enable"<?php thk_value_check( 'next_prev_enable', 'checkbox' ); ?> />
<?php printf( __( 'Add %s', 'luxeritas' ), 'next / prev' . ' ' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="rss_feed_enable"<?php thk_value_check( 'rss_feed_enable', 'checkbox' ); ?> />
<?php printf( __( 'Add %s', 'luxeritas' ), 'RSS Feed' . ' ' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="atom_feed_enable"<?php thk_value_check( 'atom_feed_enable', 'checkbox' ); ?> />
<?php printf( __( 'Add %s', 'luxeritas' ), 'Atom Feed' . ' ' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="dns_prefetch_enable"<?php thk_value_check( 'dns_prefetch_enable', 'checkbox' ); ?> />
<?php //printf( __( 'Add %s', 'luxeritas' ), 'dns_prefetch' . ' ' ); ?>
<?php printf( __( '%s を追加', 'luxeritas' ), 'dns-prefetch' . ' ' ); ?>
</p>
</li>
<script>
$('input[name=dns_prefetch_enable]').on('change', function(){
    if(this.checked) {
        $('textarea[name=dns_prefetch_text]').show();
    } else {
        $('textarea[name=dns_prefetch_text]').hide();
    }
});
</script>
<li>
<p><textarea name="dns_prefetch_text" cols="60" rows="5"
 placeholder="//www.facebook.com"><?php thk_value_check( 'dns_prefetch_text', 'textarea' ); ?></textarea></p>
</li>
<li>
<p class="control-title"><?php echo __( 'The front page meta description', 'luxeritas' ); ?></p>
<input type="text" value="<?php thk_value_check( 'top_description', 'text' ); ?>" name="top_description" />
<p class="f09em"><?php echo __( '* You can change meta description of each post by writing it in &quot;Excerpt&quot; on New Post / Edit Post page.', 'luxeritas' ); ?></p>
<p class="f09em m25-b"><?php echo __( '* You can change meta description of category and tag page by writing it in &quot;Description&quot; on Edit Categories or Tags page.', 'luxeritas' ); ?></p>
</li>
  <li>
    <p class="control-title"><?php echo __( 'Posts page, static page meta description', 'luxeritas' ); ?></p>
    <p class="checkbox">
      <input type="checkbox" value="" name="description_enable"<?php thk_value_check( 'description_enable', 'checkbox' ); ?> />
      <?php echo __( 'Fill it yourself', 'luxeritas' ); ?>
    </p>
  </li>
<li>
<p class="control-title"><?php printf( __( 'Setting of %s', 'luxeritas' ), __( 'Structured data ', 'luxeritas' ) ); ?></p>
<p><?php echo __( 'Types of site names recognized by the search engine', 'luxeritas' ); ?></p>
<p class="radio">
<input type="radio" value="WebSite" name="site_name_type"<?php thk_value_check( 'site_name_type', 'radio', 'WebSite' ); ?> />
<?php echo __( 'Web site name', 'luxeritas' ); ?>
</p>

<p class="radio">
<input type="radio" value="Organization" name="site_name_type"<?php thk_value_check( 'site_name_type', 'radio', 'Organization' ); ?> />
<?php echo __( 'Organization name', 'luxeritas' ); ?>:&nbsp;

<input type="text" value="<?php thk_value_check( 'organization_type', 'text' ); ?>" name="organization_type" />
</p>
</li>

<li>
<p><?php echo __( 'Organization logo', 'luxeritas' ), ' ( ' . __( 'Only when &quoValid only when &quot;Organization name&quot; is selected as the type of site name', 'luxeritas' ), ' ) '; ?></p>
<p class="radio">
<input type="radio" value="onepoint" name="organization_logo"<?php thk_value_check( 'organization_logo', 'radio', 'onepoint' ); ?> />
<?php echo __( 'Make the search engine recognize a one-point logo image as &quot;Organization logo&quot;', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="titleimg" name="organization_logo"<?php thk_value_check( 'organization_logo', 'radio', 'titleimg' ); ?> />
<?php echo __( 'Make the search engine recognize the site title image as &quot;Organization logo&quot;', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="none" name="organization_logo"<?php thk_value_check( 'organization_logo', 'radio', 'none' ); ?> />
<?php echo __( 'There is no organization logo. Or don&apos;t need such a setting', 'luxeritas' ); ?>
</p>
</li>

  <li>
    <p class="control-title"><?php echo __( 'The front page meta keywords', 'luxeritas' ); ?></p>
    <input type="text" value="<?php thk_value_check( 'top_keywords', 'text' ); ?>" name="top_keywords" />
  </li>

<li>
<p class="control-title"><?php printf( __( 'Setting of %s', 'luxeritas' ), 'meta keywords ' ); ?></p>
  <p class="radio">
    <input type="radio" value="input" name="meta_keywords"<?php thk_value_check( 'meta_keywords', 'radio', 'input' ); ?> />
    <?php echo __( 'Fill in posts page and static page keywords yourself', 'luxeritas' ); ?>
  </p>
<p class="radio">
<input type="radio" value="tags" name="meta_keywords"<?php thk_value_check( 'meta_keywords', 'radio', 'tags' ); ?> />
<?php echo __( 'Put tags and category names into meta keywords', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="none" name="meta_keywords"<?php thk_value_check( 'meta_keywords', 'radio', 'none' ); ?> />
<?php echo __( 'Do not need any meta keywords!', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="control-title">Google Analytics 設定</p>
<p class="f09em">トラッキングコード(gtag.js)を貼って下さい</p>
<p><textarea name="html_gtag" cols="200" rows="10"><?php thk_value_check( 'html_gtag', 'textarea',false, true ); ?></textarea></p>
</li>
<li>
<p class="control-title"><?php echo __( 'Date to display in Google search results', 'luxeritas' ); ?></p>
<select name="published">
<option value="published"<?php thk_value_check( 'published', 'select', 'published' ); ?>><?php echo __( 'publish date', 'luxeritas' ); ?></option>
<option value="updated"<?php thk_value_check( 'published', 'select', 'updated' ); ?>><?php echo __( 'updated date', 'luxeritas' ); ?></option>
</select>
<p class="f09em"><?php echo __( '* It is a setting of the date to display in Google search results when there is both a publish date and an update date.', 'luxeritas' ); ?></p>
</li>
<li>
<p class="control-title"><?php echo __( 'Splitting Content for blog posts and pages', 'luxeritas' ); ?></p>
<p class="checkbox">
<input type="checkbox" value="" name="nextpage_index"<?php thk_value_check( 'nextpage_index', 'checkbox' ); ?> />
<?php echo __( 'Prohibit crawlers to index second page onward when contents are split using &lt;!--nextpage--&gt; tag.', 'luxeritas' ); ?>
</p>
</li>
</ul>
