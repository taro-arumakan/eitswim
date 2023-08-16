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
<p class="checkbox">
<input type="checkbox" value="" name="buffering_enable"<?php thk_value_check( 'buffering_enable', 'checkbox' ); ?> />
<?php echo __( 'To enable the sequential output of buffering', 'luxeritas' ); ?>
</p>
<p class="f09em"><?php echo __( '* It will improve the speed if you check this.', 'luxeritas' ); ?></p>
<p class="f09em m25-b"><?php echo __( '* If you use cache related plugins, it may conflict one another.', 'luxeritas' ); ?></p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="add_role_attribute"<?php thk_value_check( 'add_role_attribute', 'checkbox' ); ?> />
<?php echo __( 'Add the role attribute for a barrier-free', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="remove_hentry_class"<?php thk_value_check( 'remove_hentry_class', 'checkbox' ); ?> />
<?php echo __( 'Remove hentry class', 'luxeritas' ); ?>
</p>
<p class="f09em m25-b"><?php echo __( '* Checking this will avoid getting hetnry errors from Google Webmasters even if you have the post dates, update dates, and author information not displayed.', 'luxeritas' ); ?></p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="enable_mb_slug"<?php thk_value_check( 'enable_mb_slug', 'checkbox' ); ?> />
<?php echo __( 'Allow multi byte characters for slugs', 'luxeritas' ), ' (', __( 'Not recommended', 'luxeritas' ), ')'; ?>
</p>
</li>
<li>
<p class="control-title"><?php printf( __( 'Setting of %s', 'luxeritas' ), 'user-scalable ' ); ?></p>
<p class="f09em"><?php echo __( '* If you choose NO, the older version of Androids will be able to have sticky menus.  But it will  <span style="font-weight:bold">disalllow zoom function on smartphones</span>.', 'luxeritas' ); ?></p>
<p class="radio">
<input type="radio" value="yes" name="user_scalable"<?php thk_value_check( 'user_scalable', 'radio', 'yes' ); ?> />
yes
</p>
<p class="radio">
<input type="radio" value="no" name="user_scalable"<?php thk_value_check( 'user_scalable', 'radio', 'no' ); ?> />
no
</p>
</li>
<li>
<p class="control-title"><?php printf( __( 'Setting for %s', 'luxeritas' ), __( 'design purpose', 'luxeritas' ) ); ?></p>
<p class="checkbox">
<input type="checkbox" value="" name="categories_a_inner"<?php thk_value_check( 'categories_a_inner', 'checkbox' ); ?> />
<?php echo __( 'Place the number of posts inside the &lt;a&gt; tag in the category widget', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="archives_a_inner"<?php thk_value_check( 'archives_a_inner', 'checkbox' ); ?> />
<?php echo __( 'Place the number of posts inside the &lt;a&gt; tag in the archive widget', 'luxeritas' ); ?>
</p>
</li>
<li>
<p class="checkbox">
<input type="checkbox" value="" name="parent_css_uncompress"<?php thk_value_check( 'parent_css_uncompress', 'checkbox' ); ?> />
<?php echo __( 'Non-compression for Parent Theme (for debugging)', 'luxeritas' ); ?>
</p>
</li>
</ul>
