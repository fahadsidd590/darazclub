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
define( 'DB_NAME', "darazclub" );

/** Database username */
define( 'DB_USER', "root" );

/** Database password */
define( 'DB_PASSWORD', "" );

/** Database hostname */
define( 'DB_HOST', "localhost" );

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
define( 'AUTH_KEY',         '3gwvgrsyyy9ubsdrb1apqqo7wdgv6izjkxzi2cjcksnjdt0madaztejz6lumlugb' );
define( 'SECURE_AUTH_KEY',  'rwgedrqtorlt1bscvyyf0vdnncojuhpnscxdkl57bbzv53tn2yaamnnwgbzlhqhq' );
define( 'LOGGED_IN_KEY',    'al7u3pn8gjug0rztelsl36ojoco41jqwvbl7dwvkrpxpvw26ozn2r23tjzt6v1am' );
define( 'NONCE_KEY',        'ejb9tol11rtunewet6zwglkh62invmzxjr10z5b1uq5yrovol515kv7vwrr5eqjn' );
define( 'AUTH_SALT',        'gqrireimj4ntdyy2u82cotiefdo0lur7nh1uw36pnxqya6tcvjtqupocqmxa48k8' );
define( 'SECURE_AUTH_SALT', 'lq2tpa2laeotyadqx6rfmq0p4gxrv77bt3nsq2u5z8czntgq2ui3jbqpsdzryz5k' );
define( 'LOGGED_IN_SALT',   'qqvpl88m7ziyuxbgqmtht9bik7hlfych5ugtx7isknyrgtbzyktcfalkqmuxd0c6' );
define( 'NONCE_SALT',       'fzuz7oscylvq3iiv3erxflqzoqzl6swydvknxx5l8vomn7t1p3q237lnorrkwrgl' );

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
$table_prefix = 'wprq_';

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
