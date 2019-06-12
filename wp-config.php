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
define( 'DB_NAME', 'tdt_live' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'rX9IFxdyV3XTefidhRnk' );

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
define( 'AUTH_KEY',         'h0+abgp:va>6$cn_HhQWKR<ced!N<Bg%_R >L/*X`16q[.!pxk3v-(L16rtRJ?&3' );
define( 'SECURE_AUTH_KEY',  'Fy?8YI_=%Oh=q11sA)!~;C^zR?-,bpkj8)7C0kTcU{ev=Hkm+-D9X(Xs~[zbq4OH' );
define( 'LOGGED_IN_KEY',    'gewBdHOUMBf-+F+`kXVhs4>TuFRo`VV)R}oX? 8S3xZ3MF|l{ti(;g#KFy_#=PyQ' );
define( 'NONCE_KEY',        'q}zrDT=V(ODf+4tv&wV2_A{.(re/i*J`CcHN_{e56O0eS:>ljMSaf`GpKfrxp,Y)' );
define( 'AUTH_SALT',        'Y84lCqHuUF?b9/dWk)dC4jm)k7%%47{}!7*qkV7HYHCVECeQr7<kF~1s9rE@[y~:' );
define( 'SECURE_AUTH_SALT', 'YPI|5PQl!ju4n?>n*X.$R/7s[wN/0,%8#AW,--=xz^w5F-P*3bpL9Y03()L&j# ~' );
define( 'LOGGED_IN_SALT',   '7^V1@l*~M-h7X=U<zBH?Twie?St&/VQB:6+%5,tX{:@U?BCx>3uu-|<ax8rQ}q-9' );
define( 'NONCE_SALT',       'EM|[PX4:q^8U{q)Fw } By-@O!kAP+_2LjaRQ]g;8TWq#sn.^f;XT{Z*I&lNoAUh' );

/**#@-*/



/**  SES Keys  **/


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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
define( 'WP_MEMORY_LIMIT', '256M' );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );

