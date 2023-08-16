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
<p class="label-title"><?php echo __( 'Load CSS for widgets', 'luxeritas' ); ?></p>
<p class="f09em"><?php echo __( '* If you are not using CSS for widgets, disabling its load will decrease the total filze size of the page and can make your page render faster.', 'luxeritas' ); ?></p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_search"<?php thk_value_check( 'css_search', 'checkbox' ); ?> />
<?php echo __( 'Search Form Widget', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_archive"<?php thk_value_check( 'css_archive', 'checkbox' ); ?> />
<?php echo __( 'Categories &amp; Archive DropDown Widget', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_calendar"<?php thk_value_check( 'css_calendar', 'checkbox' ); ?> />
<?php echo __( 'Calendar Widget', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_new_post"<?php thk_value_check( 'css_new_post', 'checkbox' ); ?> />
<?php echo __( 'Recent posts', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')'; ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_adsense"<?php thk_value_check( 'css_adsense', 'checkbox' ); ?> />
<?php echo __( 'Adsense Widget', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')'; ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_rcomments"<?php thk_value_check( 'css_rcomments', 'checkbox' ); ?> />
<?php echo __( 'Recent Comments', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')'; ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_follow_button"<?php thk_value_check( 'css_follow_button', 'checkbox' ); ?> />
<?php echo __( 'SNS Follow Button (by Luxeritas)', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_rss_feedly"<?php thk_value_check( 'css_rss_feedly', 'checkbox' ); ?> />
<?php echo __( 'RSS / Feedly Button (by Luxeritas)', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="css_qr_code"<?php thk_value_check( 'css_qr_code', 'checkbox' ); ?> />
<?php echo __( 'QR Code', 'luxeritas' ) . ' (' . __( 'by Luxeritas', 'luxeritas' ) . ')'; ?>
</p>
</li>
</ul>
