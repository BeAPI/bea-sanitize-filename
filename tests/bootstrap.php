<?php

$wp_root = getenv( 'WP_ROOT_FOLDER' );

if ( empty( $wp_root ) ) {
	$wp_root = '/var/www/html/';
}

$wp_root = rtrim( $wp_root, '/' ) . '/';

if ( ! file_exists( $wp_root . 'wp-includes/plugin.php' ) ) {
	$wp_root = '/var/www/html/wordpress/';
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', $wp_root );
}

require_once $wp_root . 'wp-load.php';
require_once dirname( __DIR__ ) . '/bea-sanitize-filename.php';
