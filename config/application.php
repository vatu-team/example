<?php

/**
 * Project Configuration.
 *
 * Your base production configuration goes in this file. Environment-specific
 * overrides go in their respective config/environments/{{WP_ENVIRONMENT_TYPE}}.php file.
 *
 * @package   Vatu\Wordpress\Config
 * @author    Vatu <hello@vatu.dev>
 * @link      https://vatu.dev/
 * @license   GNU General Public License v3.0
 * @copyright 2023-2024 Vatu Limited.
 *
 * phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols -- Required to bootstrap WordPress.
 * phpcs:disable Squiz.PHP.DiscouragedFunctions.Discouraged -- False positive, using dependency function.
 * phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable -- Required
 */

declare(strict_types=1);

use Roots\WPConfig\Config;

use function Env\env;

/**
 * Directory containing all of the site's files
 *
 * @var string $root_dir
 */
$root_dir = dirname( __DIR__ );

/**
 * Document Root
 *
 * @var string $webroot_dir
 */
$webroot_dir = $root_dir . '/public';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = Dotenv\Dotenv::createUnsafeImmutable( $root_dir );

if ( file_exists( $root_dir . '/.env' ) ) {
	$dotenv->load();
	$dotenv->required( [ 'WP_HOME', 'WP_SITEURL' ] );

	if ( ! env( 'DATABASE_URL' ) ) {
		$dotenv->required( [ 'DB_NAME', 'DB_USER', 'DB_PASSWORD' ] );
	}
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define( 'WP_ENV', env( 'WP_ENV' ) ?: 'production' );
define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) ?: 'production' );
define( 'WP_DEVELOPMENT_MODE', env( 'WP_DEVELOPMENT_MODE' ) ?: null );

/**
 * URLs
 */
Config::define( 'WP_HOME', env( 'WP_HOME' ) );
Config::define( 'WP_SITEURL', env( 'WP_SITEURL' ) );

/**
 * Custom Content Directory
 */
Config::define( 'CONTENT_DIR', '/app' );
Config::define( 'WP_CONTENT_DIR', $webroot_dir . Config::get( 'CONTENT_DIR' ) );
Config::define( 'WP_CONTENT_URL', Config::get( 'WP_HOME' ) . Config::get( 'CONTENT_DIR' ) );

/**
 * DB settings
 */
Config::define( 'DB_NAME', env( 'DB_NAME' ) );
Config::define( 'DB_USER', env( 'DB_USER' ) );
Config::define( 'DB_PASSWORD', env( 'DB_PASSWORD' ) );
Config::define( 'DB_HOST', env( 'DB_HOST' ) ?: 'localhost' );
Config::define( 'DB_CHARSET', 'utf8mb4' );
Config::define( 'DB_COLLATE', '' );

// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
$table_prefix = env( 'DB_PREFIX' ) ?: 'wp_';

if ( env( 'DATABASE_URL' ) ) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	$dsn = (object) parse_url( env( 'DATABASE_URL' ) );

	Config::define( 'DB_NAME', substr( $dsn->path, 1 ) );
	Config::define( 'DB_USER', $dsn->user );
	Config::define( 'DB_PASSWORD', $dsn->pass ?? null );
	Config::define( 'DB_HOST', isset( $dsn->port ) ? "{$dsn->host}:{$dsn->port}" : $dsn->host );
}

/**
 * Authentication Unique Keys and Salts
 */
Config::define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
Config::define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
Config::define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
Config::define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
Config::define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
Config::define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
Config::define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
Config::define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

/**
 * Custom Settings
 */
Config::define( 'AUTOMATIC_UPDATER_DISABLED', true );
Config::define( 'DISABLE_WP_CRON', env( 'DISABLE_WP_CRON' ) ?: false );
// Disable the plugin and theme file editor in the admin.
Config::define( 'DISALLOW_FILE_EDIT', true );
// Disable plugin and theme updates and installation from the admin.
Config::define( 'DISALLOW_FILE_MODS', true );
// Limit the number of post revisions that WordPress stores (true (default WP): store every revision).
Config::define( 'WP_POST_REVISIONS', env( 'WP_POST_REVISIONS' ) ?: true );
Config::define( 'WP_DEFAULT_THEME', env( 'WP_DEFAULT_THEME' ) ?: null );

/**
 * Debugging Settings
 */
Config::define( 'WP_DEBUG_DISPLAY', false );
Config::define( 'WP_DEBUG_LOG', env( 'WP_DEBUG_LOG' ) ?: false );
Config::define( 'SCRIPT_DEBUG', false );
ini_set( 'display_errors', '0' );

/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
	$_SERVER['HTTPS'] = 'on';
}

$env_config = __DIR__ . '/environments/' . WP_ENVIRONMENT_TYPE . '.php';

if ( file_exists( $env_config ) ) {
	require_once $env_config;
}

/**
 * Multisite
 */
Config::define( 'WP_ALLOW_MULTISITE', env( 'WP_ALLOW_MULTISITE' ) ?: false );
Config::define( 'MULTISITE', env( 'MULTISITE' ) ?: false );

if ( env( 'MULTISITE' ) === true ) {
	Config::define( 'SUBDOMAIN_INSTALL', true );
	Config::define( 'DOMAIN_CURRENT_SITE', env( 'WP_DOMAIN_CURRENT_SITE' ) );
	Config::define( 'PATH_CURRENT_SITE', '/' );
	Config::define( 'SITE_ID_CURRENT_SITE', 1 );
	Config::define( 'BLOG_ID_CURRENT_SITE', 1 );
	Config::define( 'COOKIE_DOMAIN', env( 'COOKIE_DOMAIN' ) ?: null );
}

/*
 * S3 Uploads
 */
Config::define( 'S3_UPLOADS_BUCKET', env( 'S3_UPLOADS_BUCKET' ) ?: null );
Config::define( 'S3_UPLOADS_REGION', env( 'S3_UPLOADS_REGION' ) ?: null );
Config::define( 'S3_UPLOADS_KEY', env( 'S3_UPLOADS_KEY' ) ?: null );
Config::define( 'S3_UPLOADS_SECRET', env( 'S3_UPLOADS_SECRET' ) ?: null );
Config::define( 'S3_UPLOADS_BUCKET_URL', env( 'S3_UPLOADS_BUCKET_URL' ) ?: null );
Config::define( 'S3_UPLOADS_USE_LOCAL', env( 'S3_UPLOADS_USE_LOCAL' ) ?: null );
Config::define( 'S3_UPLOADS_OBJECT_ACL', env( 'S3_UPLOADS_OBJECT_ACL' ) ?: null );

/**
 * Google Tag Manager
 */
Config::define( 'GOOGLE_TAG_MANAGER_CONTAINER_ID', env( 'GOOGLE_TAG_MANAGER_CONTAINER_ID' ) ?: null );

/**
 * Use X-Forwarded-For HTTP Header to Get Visitor's Real IP Address
 */
if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	$http_x_headers         = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
	$_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
}

Config::apply();

/**
 * Bootstrap WordPress
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', "{$webroot_dir}/wp/" );
}
