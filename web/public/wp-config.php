<?php
/**
 * The base configuration for WordPress
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

require_once __DIR__ . '/vendor/autoload.php';

if ( file_exists( __DIR__ . '/.env' ) ) {
	$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
	$dotenv->load();
}

define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) );
define( 'DB_HOST', getenv( 'DB_HOST' ) );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**
 * Secret key
 *
 * @link https://api.wordpress.org/secret-key/1.1/salt/
 */
define( 'AUTH_KEY', '4}z7,T7zHHgoh>$l+w-kA=$`/[vUq.TO(z|&F^8-|+OS%c<kmRZVEfdV@:CYk`mV' );
define( 'SECURE_AUTH_KEY', 'UOa@)[PNR G?9Sm<DNuhl| Pm)b|2D$O1ysTGA+rpsn+i3Kcw+]*o4@H-U:@c<IR' );
define( 'LOGGED_IN_KEY', 'PCgw=e?eYLuKn!;>N!gM2m7b=|mP><&`.{!!cv~[uDPM+7z-As##MW}O+Z#e({gG' );
define( 'NONCE_KEY', '_Q[Uz[VQ|+@+kGV1mClHXrs2,nKOn1*}g;k(jQN[SnFbEc!T$.sgS9{$z.}b*|;i' );
define( 'AUTH_SALT', 'tT+^E2;DvoB~F32AT-}|j-COzr@f&TjS~6+9.u^|&D4K/@l-:fJvCL{z+Py.F_Qm' );
define( 'SECURE_AUTH_SALT', '*fO<$[OLq.Lj[V&DtH Ldx4tp8K_W}tZD-U%}Sm-=&qS64]+@OYSkYaA}1N9n8/k' );
define( 'LOGGED_IN_SALT', '7Qg4[~%?5h-3 :J]=ih$PHd+GYw:rwj*~g|b<vvUoBv_dhYL9Fa}`sVhUtT9`O6N' );
define( 'NONCE_SALT', 'o`!J019uu; f9L^bX8SxXfu|P2pb?,RgWiGJ,@:KKYwdy*h?K-8apAWM~#cHco[f' );

/**
 * Database Table prefix
 */
// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$table_prefix = 'wp_';

/**
 * debugging mode
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', getenv( 'WP_ENV' ) === 'development' );

/**
* Revision
*/
define( 'WP_POST_REVISIONS', 3 );

// テスト環境用mailhog設定
if ( WP_DEBUG ) {
	define( 'WPMS_ON', true );
	define( 'WPMS_SMTP_HOST', 'mailhog' ); // The SMTP mail host.
	define( 'WPMS_SMTP_PORT', 1025 ); // The SMTP server port number.
	define( 'WPMS_SSL', '' ); // Possible values '', 'ssl', 'tls' - note TLS is not STARTTLS.
	define( 'WPMS_SMTP_AUTH', false ); // True turns it on, false turns it off.
	define( 'WPMS_SMTP_AUTOTLS', false ); // True turns it on, false turns it off.
	define( 'WPMS_MAILER', 'smtp' );
}

/**
 * Absolute path to the WordPress directory.
*/
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/**
 * Sets up WordPress vars and included files.
*/
require_once ABSPATH . 'wp-settings.php';
