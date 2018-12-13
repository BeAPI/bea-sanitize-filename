<?php
/*
 Plugin Name: BEA - Sanitize Filename
 Version: 2.1.0
 Plugin URI: https://fr.wordpress.org/plugins/bea-sanitize-filename
 Description: Remove all punctuation and accents from the filename of uploaded files.
 Author: Be API Technical team
 Author URI: https://beapi.fr
 Domain Path: languages
 Text Domain: bea-sanitize-filename
 Contributors: Amaury Balmer, Maxime Culea
 --------
 Copyright 2018 Be API Technical team (human@beapi.fr)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEA_Sanitize_Filename {
	function __construct() {
		add_filter( 'sanitize_file_name_chars', [ $this, 'add_file_name_chars' ] );

		add_filter( 'sanitize_file_name', [ __CLASS__, 'sanitize_file_name' ] );
		// Manage library upload
		add_filter( 'wp_handle_upload_prefilter', [ $this, 'handle_upload_file' ] );
		// Manage code library insertion
		add_filter( 'wp_handle_sideload_prefilter', [ $this, 'handle_upload_file' ] );
	}

	/**
	 * Handle special chars which are not in core
	 * Accentuated characters are removed with remove_accents()
	 *
	 * @param array $special_chars
	 *
	 * @author Maxime CULEA
	 *
	 * @return array
	 */
	function add_file_name_chars( $special_chars = [] ) {
		// Special caracters
		$special_chars = array_merge( [ '’', '‘', '“', '”', '«', '»', '‹', '›', '—', '€', '©' ], $special_chars );

		return $special_chars;
	}

	/**
	 * Filters the filename by adding more rules :
	 * - only lowercase
	 * - replace _ by -
	 * - remove accents with sanitize filename
	 *
	 * @since 1.0.1
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	static function sanitize_file_name( $file_name = '' ) {
		// Empty filename
		if ( empty( $file_name ) ) {
			return $file_name;
		}

		// get extension
		preg_match( '/\.[^\.]+$/i', $file_name, $ext );

		// No extension, go out ?
		if ( ! isset( $ext[0] ) ) {
			return $file_name;
		}

		// Get only first part
		$ext = $ext[0];

		// work only on the filename without extension
		$file_name = str_replace( $ext, '', $file_name );

		/**
		 * 1 : _ with - and only lowercase
		 * @see wp-includes/formatting.php\sanitize_title_with_dashes()
		 *
		 * 2 : remove accents
		 * @see : wp-includes/formatting.php\remove_accents()
		 */
		$file_name = sanitize_title( $file_name );

		// replace _ by -
		$file_name = str_replace( '_', '-', $file_name );

		// Return sanitized file name
		return $file_name . $ext;
	}

	/**
	 * On file upload, add the global sanitize file name process which also include ours
	 *
	 * @param $file
	 *
	 * @author Maxime CULEA
	 *
	 * @return mixed
	 */
	function handle_upload_file( $file ) {
		$file['name'] = sanitize_file_name( $file['name'] );

		return $file;
	}
}

new BEA_Sanitize_Filename();