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
<input type="checkbox" value="" name="jquery_load"<?php thk_value_check( 'jquery_load', 'checkbox' ); ?> />
<?php echo __( 'Use jQuery', 'luxeritas' ); ?>
<p class="f09em m25-b"><?php echo __( '* In most cases , jQuery is mandatory.', 'luxeritas' ), ' <strong>', __( 'Please leave it checked unless you know what you are doing.', 'luxeritas' ), '</strong>'; ?></p>
</li>
<li>
<input type="checkbox" value="" name="jquery_defer"<?php thk_value_check( 'jquery_defer', 'checkbox' ); ?> />
<?php echo __( 'Make jQuery asynchronous ( be careful when using this )', 'luxeritas' ); ?>
<p class="f09em"><?php echo __( '* When you are combining jQuery and other scripts, Will add &quot;async&quot; property for jQuery and add &quot;defer&quot; property for external script plugin files.', 'luxeritas' ); ?></p>
<p class="f09em"><?php echo __( '* Otherwise, Will add &quot;defer&quot; property for jQuery and external script plugin files. ( &quot;async&quot; property will not be added )', 'luxeritas' ); ?></p>
<p class="f09em"><?php echo '※ <strong>', __( 'Some plugin may not work correctly when this option is enabled. ( Such as plugins that runs in inline )', 'luxeritas' ), '</strong>'; ?></p>
</li>
</ul>
