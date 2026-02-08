<?php
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
define( 'DB_NAME', 'simplecr_wp5873' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'fnDtZH9Q{cEIe%(IP7ohuf1Eao7vzz(CuYd(:-e>9qj:9co?R[Ek`%tbWGN:a{{x' );
define( 'SECURE_AUTH_KEY',  '%O$Ujh^sZci(zpa{,m?B?fih_y#?)(6d5KV7{I2T1UM`lx5Dhbu!s~c|XsTv}GT/' );
define( 'LOGGED_IN_KEY',    '6&IH b;@gi8qk:$@ZXQ-Ot:$<-D$}(^_1~]w7mxTNfA1xT.=50x~4}{%}qZ^^vs8' );
define( 'NONCE_KEY',        'KVkmKu]:EqXu!F)?}vS[|Lie*Wo){336Jzq8j7h3T8wC_8u}AqZha>UON8;UW<f.' );
define( 'AUTH_SALT',        'G0[BeO,Tr_e^OD@X:AJXN[lB,7XZ@)@Z%0i4q6S`8}A5.<|w _r#bdJ{6`]!E y5' );
define( 'SECURE_AUTH_SALT', 'S_W7=Q)2 j~}?%vQGcrwE-q~X.}i&uU$/v9Yd]f5,JNaXRBJ4BorVc{R08 zT8^Q' );
define( 'LOGGED_IN_SALT',   '$Vuup}9Yx%^B}:8n;s|is=BuwiWEQ5!9qscHdjVHDUObL!h*J x$%N +(mg,4^O@' );
define( 'NONCE_SALT',       'iI!smPcG8jhBXxt=l:g19Nq,(a{P*Y{gC@mTud)Ap#[LW+QP=1mr4nI^4P{zr;)E' );

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
$table_prefix = 'scr_';

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
