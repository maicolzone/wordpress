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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'SchW=$lpz`(=wBq%b9u GUIU:=;}JVmn^M5FnLhNAPrF@SqDI7,(MC/$XzbmSm$k' );
define( 'SECURE_AUTH_KEY',  'c>`i}pWw}YcNbRcAKfv}UP42UVIDc*g4v!M-{2AzAB!6st@.r;E6UUq*66=ug+d;' );
define( 'LOGGED_IN_KEY',    'Y,6fZ)fa^)veobB#)lmE*5Q[0JS%^{Hsv[?A5Lk>LyHi8D8+*a%X-}A5dU0IQs>0' );
define( 'NONCE_KEY',        '~z;-+^kyIqjD).skMkXAf91wuFUO3X=n,9{#%tmI9k}x.2T7F4Ry>8 Gf5xKO=QG' );
define( 'AUTH_SALT',        '.H}2f,d;;LEVI& u%pj:] Xs}9_,^B]%MOWynLwY~7WQ~K1.&=EX%ljujXxs;v>G' );
define( 'SECURE_AUTH_SALT', 'f^C,Am=~b5[~t@Ypf:5>B_dLdEn$r$%cp:)XQ=O4(M;loUud)FdFQ<K&`V-Jn5gl' );
define( 'LOGGED_IN_SALT',   '4d1bv}vr[@Z#|^LEhXWg$&:g83i><u]bH!TAMnuk)qs<NZBth4yR6K]z+=J fiZ<' );
define( 'NONCE_SALT',       ' ^EypUCnOH.q!j$^c0o&;fCX9(<1v0> be=[($kVDOw>B.7~,nsF]hILy;[474o[' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
