<?php
/**
 * Theme Functions
 *
 * @package   Vatu\Wordpress\Theme\Base
 * @author    Vatu <hello@vatu.dev>
 * @link      https://vatu.dev/
 * @license   GNU General Public License v3.0
 * @copyright 2023 Vatu Limited.
 */

declare(strict_types=1);

if ( ! is_admin() ) {
	wp_enqueue_style(
		'base-styles',
		get_template_directory_uri() . '/style.css',
		[ 'global-styles' ],
		(string) filemtime( get_template_directory() . '/style.css' )
	);
}

add_editor_style(
	[
		'style.css',
		'editor-style.css',
	]
);
