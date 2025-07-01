<?php
// TEMPORARY FIX - WordPress URLs causing redirect loop
// define( 'WP_HOME',    'https://miura-diving.com' );
// define( 'WP_SITEURL', 'https://miura-diving.com/wp' );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'yokosukatj_wp2' );

/** Database username */
define( 'DB_USER', 'yokosukatj_wp2' );

/** Database password */
define( 'DB_PASSWORD', 'm3jko6c6pd' );

/** Database hostname */
define( 'DB_HOST', 'mysql8072.xserver.jp' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         't@zY5/W4Jj3H%)D%$ ^]Qy/MGKsUMu*zU1(zn<dQ9.b>kXyN5Jr]!EX__h_x<ns/' );
define( 'SECURE_AUTH_KEY',  '7%}9:5R{r$3<jP@[x.J3<),Su$,1z#=nw[!%I|mqt11kG e4W`PMoku!d,ftj.Bl' );
define( 'LOGGED_IN_KEY',    '%GZ5KdX34HN|K]Zu?{,TV!!sZ-M`owyw+1^?cCjT,7O597H/faX.Q|%~T5P4feVo' );
define( 'NONCE_KEY',        'D1aV0_N9)LTUEk/GlH(#l4%4/1G?=<W?[nP$TpcS0@7YTV(A]U<qU*y.f* s-eW_' );
define( 'AUTH_SALT',        '-.3I %hx},&Zbis,$$[4f[prtzTBi]nK+YQm26aO@/M]x8R[FLwh .0OPb&&;m)n' );
define( 'SECURE_AUTH_SALT', 'C}{cSZ!*#>3rUq-wh9EK{Xa5pCKi7d=LMcSxy<v!U](56U{T5JEf XnA}&ygL#jc' );
define( 'LOGGED_IN_SALT',   'th` ]xq L4RP}|K2=sR?5/Dyl]t3})!+5bct9sg!o]G %+1pRj/*qY<rGf=G!P H' );
define( 'NONCE_SALT',       '^&O;E,/wd &GfcsYQQy#v&-k$Y;/9>){z51>RoUAb @4r#f@JN==juut!BCH{P`)' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
