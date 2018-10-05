<?php
/*
 Plugin Name: BEA - Sanitize Filename
 Version: 2.0.6
 Plugin URI: https://github.com/BeAPI/bea-sanitize-filename
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

function bea_sanitize_file_name_chars( $special_chars = array() ) {
	// Special caracters
	$special_chars = array_merge( array( '’', '‘', '“', '”', '«', '»', '‹', '›', '—', '€', '©' ), $special_chars );
	/**
	 * Accentued caracters
	 * @see   https://github.com/BeAPI/bea-sanitize-filename/issues/8
	 * @since 2.0.6
	 */
	$special_chars = array_merge( array( 'À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ò','Ó','Ô','Õ','Ö','Ù','Ú','Û','Ü','Ý','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ÿ' ), $special_chars );

	return $special_chars;
}
add_filter( 'sanitize_file_name_chars', 'bea_sanitize_file_name_chars', 10, 1 );

/**
 * Filters the filename by adding more rules :
 * - only lowercase
 * - replace _ by -
 *
 * @since 1.0.1
 *
 * @param string $file_name
 *
 * @return string
 */
function bea_sanitize_file_name( $file_name = '' ) {
	// Empty filename
	if ( empty($file_name) ) {
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

	// only lowercase
	// remove accents
	$file_name = sanitize_title( $file_name );

	// replace _ by -
	$file_name = str_replace( '_', '-', $file_name );

	// Return sanitized file name
	return $file_name . $ext;
}
add_filter( 'sanitize_file_name', 'bea_sanitize_file_name', 10, 1 );
