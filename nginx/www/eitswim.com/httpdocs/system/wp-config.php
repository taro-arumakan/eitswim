<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_eitswim');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'eQ5fuRIo5hWL');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'E}_o4Q*d!:/82CwvM54.qB<AS$ntK{y$90!iXZD>x<x)|fI4$EW-px(Vc.UDD:iF');
define('SECURE_AUTH_KEY',  'P95B@[,1y0-GkuMdLWGCL9#Uvk2u`|GM4EPWjQ`G4-h}r<=8}vI!O6wE!t]>DU[&');
define('LOGGED_IN_KEY',    'SXEVEWn8qj`ihNI7&Ii< 1&CXpoOs.P*!r@6j`]v19Jl^LGtV0VZJ[I*_P$1yqKq');
define('NONCE_KEY',        '.5%b_z1~e%0{$JuDv53#o{rj =7>pqwRGY6*CUAyA4%chJ6Pfj!{P&A&@OhJ}=;@');
define('AUTH_SALT',        '@=[[eRZuc[)5,NG1S^!y+ua1e>W+fZ~V>`0nS*5yX5bMw<F6DLWk.SsnoJ=o&qD-');
define('SECURE_AUTH_SALT', ' -D&C~w*@k;2hxydO~A9b#k~AKhXxW!^2^[Tn>bY2SaQ9Z/>(}RW.tB@H*Cr[<*z');
define('LOGGED_IN_SALT',   'wHl@D|?gq~,ig:wMpYFRWStHsg/)W:TF|Rv__!F,Yk; 0{3tQ/X?[BN2Z=)I}}#&');
define('NONCE_SALT',       './><;UEuqSE{)2qmw5HG@X3UT[rUH@AXne<&lJ@g*W#M/Y1Ku6GwB2x)lBwojfa}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/** マルチサイト有効 */
define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'eitswim.com');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('FS_METHOD','direct');
define('AUTOMATIC_UPDATER_DISABLED', true);
add_filter( 'auto_update_translation', '__return_true' );
