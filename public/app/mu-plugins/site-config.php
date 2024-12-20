<?php

/**
 * Plugin Name: Site Specific Configuration
 * Plugin URI: https://vatu.dev
 * Description: Site settings independent to the a theme or plugin.
 * Version: 1.0.0
 * Author: Vatu
 * Author URI: https://vatu.dev/
 * License: GPL3
 *
 * @package   Vatu/Wordpress/Plugin/SiteConfig
 * @copyright 2020-2024 Vatu Ltd.
 */

declare(strict_types=1);

/**
 * Disable indexing for development sites.
 */
if (
	defined( 'WP_ENVIRONMENT_TYPE' )
	&& in_array( WP_ENVIRONMENT_TYPE, [ 'local', 'development', 'qa', 'staging' ], true )
	&& has_filter( 'pre_option_blog_public', '__return_zero' )
) {
	remove_filter( 'pre_option_blog_public', '__return_zero' );
}

/**
 * Disable current theme validation
 *
 * By default, WordPress falls back to a default theme if it can't find
 * the active theme. This is undesirable because it requires manually
 * re-activating the correct theme and can lead to data loss in the form
 * of deactivated widgets and menu location assignments.
 */
add_filter( 'validate_current_theme', '__return_false' );

/**
 * Remove warning about site health statuses.
 *
 * @param array<string,array<string,string>> $tests
 * @return array<string,array<string,string>>
 */
function vatu_disable_site_health_tests( array $tests ): array
{
	unset( $tests['direct']['debug_enabled'] );
	unset( $tests['direct']['available_updates_disk_space'] );
	unset( $tests['async']['background_updates'] );
	return $tests;
}

add_filter( 'site_status_tests', 'vatu_disable_site_health_tests' );

/**
 * Disable Admin notification of User password change.
 */
add_filter( 'wp_password_change_notification_email', '__return_false' );

/**
 * Disable User notification of password change.
 */
add_filter( 'send_password_change_email', '__return_false' );

/**
 * Set Content Security Policy header.
 */
function vatu_content_security_policy_header(): void
{
	header( "Content-Security-Policy: default-src https: data: 'unsafe-inline'; upgrade-insecure-requests" );
}

add_action(
	'send_headers',
	'vatu_content_security_policy_header',
	10,
	0
);

/**
 * Set Referrer header.
 */
function vatu_referrer_policy_header(): void
{
	header( 'Referrer-Policy: origin-when-cross-origin, strict-origin-when-cross-origin' );
}

add_action( 'send_headers', 'vatu_referrer_policy_header', 10, 0 );

/**
 * Set Permissions Policy header.
 */
function vatu_permission_policy_header(): void
{
	header(
		'Permissions-Policy: accelerometer=(),autoplay=(),camera=(),display-capture=(),document-domain=(),encrypted-media=(),fullscreen=(),geolocation=(),gyroscope=(),magnetometer=(),microphone=(),midi=(),payment=(),picture-in-picture=(),publickey-credentials-get=(),screen-wake-lock=(),sync-xhr=(self),usb=(),web-share=(),xr-spatial-tracking=()'
	);
}

add_action( 'send_headers', 'vatu_permission_policy_header', 10, 0 );
