<?php
/*
Plugin Name: BEA - Sanitize Filename
Version: 2.0.10
Plugin URI: https://github.com/BeAPI/bea-sanitize-filename
Description: Remove all punctuation and accents from the filename of uploaded files.
Requires at least: 4.0
Requires PHP: 8.0
Author: Be API
Author URI: https://beapi.fr
License: GPLv3 or later
License URI: https://github.com/BeAPI/bea-sanitize-filename/blob/master/LICENSE.md
Text Domain: bea-sanitize-filename
Contributors: Amaury Balmer, Maxime Culea

--------

Copyright 2018-2019 Be API Technical team (human@beapi.fr)

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

/**
 * Merges extra characters into the list WordPress strips from upload file names.
 *
 * Callback for the `sanitize_file_name_chars` filter.
 *
 * @param array $special_chars Characters already collected by WordPress or other callbacks.
 * @return array Combined list of characters removed during filename sanitization.
 */
function bea_sanitize_file_name_chars( $special_chars = array() ) {
	// Punctuation, symbols, and typographic quotes.
	$special_chars = array_merge( array( '’', '‘', '”', '“', '«', '»', '‹', '›', '—', '€', '©', '@' ), $special_chars );

	/**
	 * Accented Latin letters removed from the basename.
	 *
	 * @see https://github.com/BeAPI/bea-sanitize-filename/issues/8
	 * @since 2.0.6
	 */
	$special_chars = array_merge( array( 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ' ), $special_chars );

	return $special_chars;
}

add_filter( 'sanitize_file_name_chars', 'bea_sanitize_file_name_chars', 10, 1 );

/**
 * Applies extra rules after WordPress core sanitization.
 *
 * - Lowercase the basename (extension unchanged).
 * - Strip accents via `remove_accents()`.
 * - Replace underscores with hyphens.
 *
 * @since 1.0.1
 *
 * @param string $file_name File name after `sanitize_file_name` character stripping.
 * @return string File name with the rules above applied.
 */
function bea_sanitize_file_name( $file_name = '' ) {
	// Empty filename
	if ( empty( $file_name ) ) {
		return $file_name;
	}

	// Separate filename and extension (last segment after the final dot).
	$extension = pathinfo( $file_name, PATHINFO_EXTENSION );
	if ( '' === $extension ) {
		return $file_name;
	}

	$suffix = '.' . $extension;
	$suffix_length = strlen( $suffix );

	// Strip the suffix only at the end; str_replace( $suffix, '', $file_name ) would remove every occurrence.
	if ( ! str_ends_with( $file_name, $suffix ) ) {
		return $file_name;
	}

	$file_name = substr( $file_name, 0, -$suffix_length );

	// only lowercase
	$file_name = mb_strtolower( $file_name );

	// remove accents
	$file_name = remove_accents( $file_name );

	// replace _ by -
	$file_name = str_replace( '_', '-', $file_name );

	// Return sanitized file name
	return $file_name . $suffix;
}

add_filter( 'sanitize_file_name', 'bea_sanitize_file_name', 10, 1 );
