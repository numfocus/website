<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_numfocus');

/** MySQL database username */
define('DB_USER', 'numfocus_wpuser');

/** MySQL database password */
define('DB_PASSWORD', 'FocusOnPython123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'rr~d)WA]3m[Mh5+E?QJq6ed*-j@h=aU4S5PWl0 tOiGGZ9y!f=+;kDb>r&1_G<%I');
define('SECURE_AUTH_KEY',  'YA;K_{)y,bRWREgVeT-j3@@C[|Losxi.-LP.[S=}zw{gwx|zS$$q;@MH*|>/Fkk<');
define('LOGGED_IN_KEY',    'Nypwp_:Z)46Y(.+@;fQDk<94wSh(.b2(WUB^QJM;ktvc(Gf(H(lr_jNy(K_hHzN,');
define('NONCE_KEY',        ' #n+U%R1M`d/gk7I(a*b31wDgdcRFqo;{~cG1;$jN+h5,)g?|q&6+gu+P%jde$3J');
define('AUTH_SALT',        '|9r?o|?]DXmN+QU>-I:+eP*!W.7o}X x$gO@ i&iwUi2NhvwZRHy.!z%H2l,auyP');
define('SECURE_AUTH_SALT', 'sAm[>K*>bY},A@z+7(Qc|j7wK%SQp:Eh3WA3R]I7yXUe{K%!_}y+ar=?:q,)0@W.');
define('LOGGED_IN_SALT',   '@$p~%FMUt8vYhAGi5F)pY%Hv+?W`%C[{F.IPWue|W**V#+qL0NaxlUd)s<1;kWQ_');
define('NONCE_SALT',       'mYd,Ro)jJVXU5f*N>+b-*v|=^t-E9eR^S{NKwFpm!#CE]G6 @x 0S|um(-+y/b7^');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
