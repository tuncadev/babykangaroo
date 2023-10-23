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
define( 'DB_NAME', 'citymody_kangaroo' );

/** Database username */
define( 'DB_USER', 'citymody_kangaroo' );

/** Database password */
define( 'DB_PASSWORD', '%(SEgs63c2' );

/** Database hostname */
define( 'DB_HOST', 'citymody.mysql.tools' );

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
define( 'AUTH_KEY',         'fD([:9s=V<)|^/-_iTy@ZnhI!z}l8}76MrUr8^pQ#(@OhmP@#9]Fjx+)dG@@aH&N' );
define( 'SECURE_AUTH_KEY',  '-Tw:X2u>-H_JHPY^^$D^X}o?>.Q}P?vP7B!Fh~zWvy^ZI$^bKRd5i.9im+9WF($u' );
define( 'LOGGED_IN_KEY',    '{IO}7_mdi[/X6f(hbEDHC,re>F[1?Di@f:&b(iW_0]D!*[=]?em0V7{iY<4-},_f' );
define( 'NONCE_KEY',        'X:KaBcMFGM])dmgLuvb[*V:mhP-,fA~#q}og/9!Tub9=LrEncZAj3TI.h4FRwv.D' );
define( 'AUTH_SALT',        'zz3JJP?dzTc!ldGxk;uVn?JS`m2<cA0.:Xab9S+*a)Q3 _ILW3Wb[B!1lQq;kYNY' );
define( 'SECURE_AUTH_SALT', '}~*bXzFWY!DY6@v85=^w@!_Q[~])/VBHTw}yt{7btotTf0*d:=}wn] WEpOT S_I' );
define( 'LOGGED_IN_SALT',   '{.B2XSEFc2%{b*ue!_jQur$Ulog0K H.;+V?5,_I ,C-w`68Ca]}f43~ $DoCKt7' );
define( 'NONCE_SALT',       'x~_ki N DTz5B,e1^74m|{kB&r5$:YS{G-s5x-_$pw8[fJP%aQ_18lo,: IjWA./' );

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
