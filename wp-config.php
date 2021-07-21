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
define( 'DB_NAME', 'uni' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'XT3eIQF8jG7OL2Xl20AgtPZ1tpTUx5JSIsAVu6M8UdVT65GAdymBo6fErTd7g128' );
define( 'SECURE_AUTH_KEY',  '9MoPvBMQ8YV7R9uimgShD18f1WHrlwRxRyc5J5D3jbR0QgfmUHmYPUS8WfruwhMM' );
define( 'LOGGED_IN_KEY',    '532S7b0Tv2AENuag88JkDcxDcnfmwlDnHLb7OyV1HhDQhlOb84bzYQZKt8IxhNrq' );
define( 'NONCE_KEY',        '6NF9TI3y53qY3wx7dcgu61JAUd014MqpLrLF5QOk6rYfXIZl9YWzRRaqJjPY8sgX' );
define( 'AUTH_SALT',        'eRFFiPj5PFQGnpQr8UZ87jgYgBbXp3Tku4bjVTt9u3oVi0G1Ugiuok6xYdrzG2DZ' );
define( 'SECURE_AUTH_SALT', 'x5oyGbNDRBemyojThEuCRZKDtG7O2iGSh7BwKE6xTqqtIpLgmOULtBsqsi98HdWa' );
define( 'LOGGED_IN_SALT',   'TWNARPYCZPofgPgBkd7QUpp112APxYOST8rLmh7bjwu0YbAKH5N1oCd1qe5e5P2B' );
define( 'NONCE_SALT',       'qYUX3HlEolw7QlmqDNtUHc2En4ZJkEhvAyiBXB5rKpaHjNNQIyWCRrpmBk8jT52b' );

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
