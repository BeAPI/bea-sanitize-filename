<?php
/*
  Plugin Name:  BEA Sanitize filename
  Description:  Remove all punctuation and accents from the filename of uploaded files.
  Plugin URI:   https://beapi.fr
  Version:      1.0.2
  Author:       Be API
  Author URI:   https://beapi.fr

  --------

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * Add some special chats to espace to WordPress basic list
 *
 * @param array $special_chars
 *
 * @return array
 */
function bea_sanitize_file_name_chars( $special_chars = array() ) {
	$special_chars = array_merge( array( '’', '‘', '“', '”', '«', '»', '‹', '›', '—', '€' ), $special_chars );

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
function bea_sanitize_file_name( $file_name ) {
	// get filedata.
	$file_data = pathinfo( $file_name );

	// only lowercase, alphanumeric, - and _.
	$file_data['filename'] = sanitize_title_with_dashes( $file_data['filename'] );

	// replace _ by -.
	$file_data['filename'] = str_replace( '_', '-', $file_data['filename'] );

	$suffix = ! empty( $file_data['extension'] ) ? '.' . $file_data['extension'] : '';
	return $file_data['filename'] . $suffix;
}

add_filter( 'sanitize_file_name', 'bea_sanitize_file_name', 10, 1 );
