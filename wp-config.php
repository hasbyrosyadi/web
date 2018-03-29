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
define('DB_NAME', 'kpu');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '9sx:IG!y*3~=38t/|~Q9~!pm%.|k_Y^wrY<AT6e}4YIa8x18q<ppPO[P}]SaTeLL');
define('SECURE_AUTH_KEY',  '{~>Ss!h#*v5P784-?O{5D9XG9)RzWL=Fi_-_H|xEVyO(hZov]*Y/F]UogLN)>2xd');
define('LOGGED_IN_KEY',    'xP&*1}RxfYY@BCFG)v4h2X7|l.D(E}NOu^+h_^3h%N:h>;*D60WsFk32h4Z[Lw03');
define('NONCE_KEY',        '&6l)d]Z@&Wr6$>z$LJUfPo:BTj2wajefQ,@4LEEb#wOJMkPi;~2wm@C*KFfdbHl-');
define('AUTH_SALT',        'r{9UY)kq~NwSZzX=3[kKh^-TjUV|oTD=u7CxuupZ1rL=clU,^K)sLd&P4Vr_slVp');
define('SECURE_AUTH_SALT', 'i^f!vT,<CKJ9}x=T+R]oQ6_<}wjiHg^C:knsgb0wxJ-2R7OZ`ZM2>m7.H0f<)=Pn');
define('LOGGED_IN_SALT',   'o[Z W5,+bT-SN@s#QWHzwR5>|Z}JV!_482}(V=(/)^xdKN!mm;Ac@Xy]m>sWeS6a');
define('NONCE_SALT',       'LW=D?k (B,DqLY`7!y%PZsSfe 8XhR.{Ch_lkT3i?k5>#NxiJIYv5y=cjddnP[Ep');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
