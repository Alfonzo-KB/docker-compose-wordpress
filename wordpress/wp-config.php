<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'wp' );

/** MySQL database username */
define( 'DB_USER', 'wp' );

/** MySQL database password */
define( 'DB_PASSWORD', 'secret' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'Fa^f}&o6`U=QW:DA5PU01f|lIBt9/<0uUAN.}/L[h;PG3)RM~9FwXw%>Y(b6H4i~');
define('SECURE_AUTH_KEY',  'k&W.WJwyl>~CWY;Rph)g+u8R-LtCxj*J$G0{2#A]=+lB>r-cH|=@]U#LB,J7r[]H');
define('LOGGED_IN_KEY',    '#K{u1FWTY8*x5[8Y9cMx)}(F`P0ci;TNZp/0~T~lC{4DXyJMZ~yl4S7iE:=nU1Xq');
define('NONCE_KEY',        'oZ.}#2s=GSw5AS<WME=lV)>%p%*H-|k::eZw*-P/ EezPEJx+x([8Ch[W<6|w98c');
define('AUTH_SALT',        'En11RTy!tv6Xi1Ms/{1PHPWj#a+xuQCh;O^-=;)gOaLBvFu-xH#) uggWv: !zc<');
define('SECURE_AUTH_SALT', 'go.gy|U)w8t} no{oao]m|si>cLFh!H,ux%T|0]P 7$z0fn?i1VR8S3,}Y<1 xbL');
define('LOGGED_IN_SALT',   '` CC3j/B6s/9o&I|G7_g0CwD%DZ7 Q&t ^/5AP@F!)m,BNul[^MKGxy!+rM1TpK-');
define('NONCE_SALT',       '3-i_57l8!9w+@;nbjtS;sUk-B-#/arO]Ckis-H}<mT5L(Cz5jQMX(l-E[?B]>m=P');

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
