<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'qrcode' );

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
define( 'AUTH_KEY',         '5{-b/pmu3:KD4v@BF5+vyxB8-?C(ThWwCes!U%Of17:[.g46PCX!#<QB,n4~X@LE' );
define( 'SECURE_AUTH_KEY',  '#.kuSnK(C::s[IVKwEH5cG6$eycSw?LOzWR-~ZApv&s<<b D-W2zr:WY]YIYG;a3' );
define( 'LOGGED_IN_KEY',    '#26UX=PD)(@%ow%7?Z1[W3IrK^6u.QWKQ8Vu/aMBc!2ooat 4czq?$.yYp3TD|&b' );
define( 'NONCE_KEY',        'gGKzX|FMly+a57UsA|:|&i9(VCG*;mlk-!&N,T}F{-F%X<Vy:5[,->kvRp9LI@`H' );
define( 'AUTH_SALT',        'K(={:Hg7}4o[O?Qh~z`[hhxXFnzrYwM?BDU*k:`jB|~LG|W4=A7!.a7K5[mHq$CT' );
define( 'SECURE_AUTH_SALT', 'f[@fRQQ}E-8;CXP~&lqu}eGnT<4${UW|Cr>Ie6Wv/Kn2ZAuLQvY1[V0RgFHN<UvG' );
define( 'LOGGED_IN_SALT',   '}YJC#O*yq5G#bjR0 Gy,]Gz=^;0]whC7[Mw*2rnN8&JC3xqG)l<eF0P#D8s[eKyg' );
define( 'NONCE_SALT',       'q3#B)Cd;d{y2 JUyd73YS^X1C 1&xf,61YOK}R$O2SwLFL+y{-C#&3PLlw{q=tLB' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

define("STRIPE_PUBLISHABLE_KEY", "pk_test_51L6Po1DlmL2vFzfyOj9Qwx5S0AomQQ3N07hM5GKxC4FFHB1MyseEIMnFFQqtpbWfNXdWLD1wwJ4OW8ZdB03QFD0W00E5wAiB1V");
define("GOOGLE_TAG_MANAGER", "G-JP09WKX0ZT");

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
