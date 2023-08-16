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
<p class="label-title"><?php echo 'Site key'; ?></p>
<input type="text" value="<?php thk_value_check( 'recaptcha_site_key', 'text' ); ?>" name="recaptcha_site_key" />
</li>
<li>
<p class="label-title"><?php echo 'Secret key'; ?></p>
<input type="text" value="<?php thk_value_check( 'recaptcha_secret_key', 'text' ); ?>" name="recaptcha_secret_key" />
<p class="f09em bold"><?php echo __( '* To use Google reCAPCHA, you will need Site Key and Secrect Key. Please go to <a href="https://www.google.com/recaptcha" target="_blank">Google reCAPTCHA</a> page and get yours.', 'luxeritas' ); ?></p>
<p class="f09em m25-b bold"><?php echo __( '* It might take some time till you are abel to use the feature after getting your keys.', 'luxeritas' ); ?></p>
</li>
<li>
<p class="label-title"><?php echo __( 'Theme', 'luxeritas' ); ?></p>
<select name="recaptcha_theme">
<option value="light"<?php thk_value_check( 'recaptcha_theme', 'select', 'light' ); ?>><?php echo __( 'light', 'luxeritas' ); ?></option>
<option value="dark"<?php thk_value_check( 'recaptcha_theme', 'select', 'dark' ); ?>><?php echo __( 'dark', 'luxeritas' ); ?></option>
</select>
</li>
<li>
<p class="label-title"><?php echo __( 'Size', 'luxeritas' ); ?></p>
<select name="recaptcha_size">
<option value="normal"<?php thk_value_check( 'recaptcha_size', 'select', 'normal' ); ?>><?php echo __( 'normal', 'luxeritas' ); ?></option>
<option value="compact"<?php thk_value_check( 'recaptcha_size', 'select', 'compact' ); ?>><?php echo __( 'compact', 'luxeritas' ); ?></option>
</select>
</li>
<li>
<p class="label-title"><?php echo __( 'Type of authentication', 'luxeritas' ); ?></p>
<select name="recaptcha_type">
<option value="image"<?php thk_value_check( 'recaptcha_type', 'select', 'image' ); ?>><?php echo __( 'image', 'luxeritas' ); ?></option>
<option value="audio"<?php thk_value_check( 'recaptcha_type', 'select', 'audio' ); ?>><?php echo __( 'audio', 'luxeritas' ); ?></option>
</select>
</li>
</ul>
