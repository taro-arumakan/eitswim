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
<p class="label-title"><?php echo __( 'How to load icon fonts ( Font Awesome )', 'luxeritas' ); ?></p>
<select name="awesome_load">
<option value="sync"<?php thk_value_check( 'awesome_load', 'select', 'sync' ); ?>><?php echo __( 'Synchronism', 'luxeritas' ), ' (', __( 'No delays in icon font', 'luxeritas' ), ')'; ?></option>
<option value="async"<?php thk_value_check( 'awesome_load', 'select', 'async' ); ?>><?php echo __( 'Asynchronous', 'luxeritas' ), ' (', __( 'High rendering speed', 'luxeritas' ), ')'; ?></option>
<option value="none"<?php thk_value_check( 'awesome_load', 'select', 'none' ); ?>><?php echo __( 'Not required (no load)', 'luxeritas' ); ?></option>
</select>
<p class="f09em m25-b"><?php echo ' <strong>', __( '* Usually, icon font is required.', 'luxeritas' ), '</strong> ', __( 'Please select the &quot;synchronous&quot; or &quot;asynchronous&quot;.', 'luxeritas' ); ?></p>
</li>

<li>
<p class="label-title"><?php echo __( 'CSS of icon fonts', 'luxeritas' ); ?></p>
<p class="radio">
<input type="radio" value="minimum" name="awesome_css_type"<?php thk_value_check( 'awesome_css_type', 'radio', 'minimum' ); ?> />
<?php echo __( 'Minimum CSS required by Luxeritas', 'luxeritas' ); ?>
</p>
<p class="radio">
<input type="radio" value="full" name="awesome_css_type"<?php thk_value_check( 'awesome_css_type', 'radio', 'full' ); ?> />
<?php echo __( 'Font Awesome Original CSS', 'luxeritas' ); ?>
</p>
</li>
</ul>
