<?php
/*
Plugin Name: BEA - Sanitize Filename
Version: 2.0.6
Plugin URI: https://github.com/BeAPI/bea-sanitize-filename
Description: Remove all punctuation and accents from the filename of uploaded files.
Author: Be API
Author URI: https://beapi.fr
Domain Path: languages
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
 * Add more special chars.
 *
 * @param array $special_chars
 *
 * @return array
 */
function bea_sanitize_file_name_chars( $special_chars = array() ) {
	// Special caracters
	$special_chars = array_merge( array( '’', '‘', '“', '”', '«', '»', '‹', '›', '—', '€', '©' ), $special_chars );

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

if ( ! function_exists( '_ticket_24661_patch' ) ) :
	// From: https://core.trac.wordpress.org/attachment/ticket/24661/24661.6.patch
	/**
	 * Removes "Mn" nonspacing combining marks that follow Latin characters.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	function _ticket_24661_patch( $string ) {
		if ( _wp_can_use_pcre_ucp() ) { // If UCP available.
			$string = preg_replace( '/(?<=\p{Latin})\p{Mn}+/u', '', $string );
		} else {
			// Generated by "gen_cat_regex_alts.php" from "http://www.unicode.org/Public/9.0.0/ucd/UnicodeData.txt".
			static $mn_regex_alts = '\xcc[\x80-\xbf]|\xcd[\x80-\xaf]|\xd2[\x83-\x87]|\xd6[\x91-\xbd\xbf]|\xd7[\x81\x82\x84\x85\x87]|\xd8[\x90-\x9a]|\xd9[\x8b-\x9f\xb0]|\xdb[\x96-\x9c\x9f-\xa4\xa7\xa8\xaa-\xad]|\xdc[\x91\xb0-\xbf]|\xdd[\x80-\x8a]|\xde[\xa6-\xb0]|\xdf[\xab-\xb3]|\xe0(?:\xa0[\x96-\x99\x9b-\xa3\xa5-\xa7\xa9-\xad]|\xa1[\x99-\x9b]|\xa3[\x94-\xa1\xa3-\xbf]|\xa4[\x80-\x82\xba\xbc]|\xa5[\x81-\x88\x8d\x91-\x97\xa2\xa3]|\xa6[\x81\xbc]|\xa7[\x81-\x84\x8d\xa2\xa3]|\xa8[\x81\x82\xbc]|\xa9[\x81\x82\x87\x88\x8b-\x8d\x91\xb0\xb1\xb5]|\xaa[\x81\x82\xbc]|\xab[\x81-\x85\x87\x88\x8d\xa2\xa3]|\xac[\x81\xbc\xbf]|\xad[\x81-\x84\x8d\x96\xa2\xa3]|\xae\x82|\xaf[\x80\x8d]|\xb0[\x80\xbe\xbf]|\xb1[\x80\x86-\x88\x8a-\x8d\x95\x96\xa2\xa3]|\xb2[\x81\xbc\xbf]|\xb3[\x86\x8c\x8d\xa2\xa3]|\xb4\x81|\xb5[\x81-\x84\x8d\xa2\xa3]|\xb7[\x8a\x92-\x94\x96]|\xb8[\xb1\xb4-\xba]|\xb9[\x87-\x8e]|\xba[\xb1\xb4-\xb9\xbb\xbc]|\xbb[\x88-\x8d]|\xbc[\x98\x99\xb5\xb7\xb9]|\xbd[\xb1-\xbe]|\xbe[\x80-\x84\x86\x87\x8d-\x97\x99-\xbc]|\xbf\x86)|\xe1(?:\x80[\xad-\xb0\xb2-\xb7\xb9\xba\xbd\xbe]|\x81[\x98\x99\x9e-\xa0\xb1-\xb4]|\x82[\x82\x85\x86\x8d\x9d]|\x8d[\x9d-\x9f]|\x9c[\x92-\x94\xb2-\xb4]|\x9d[\x92\x93\xb2\xb3]|\x9e[\xb4\xb5\xb7-\xbd]|\x9f[\x86\x89-\x93\x9d]|\xa0[\x8b-\x8d]|\xa2[\x85\x86\xa9]|\xa4[\xa0-\xa2\xa7\xa8\xb2\xb9-\xbb]|\xa8[\x97\x98\x9b]|\xa9[\x96\x98-\x9e\xa0\xa2\xa5-\xac\xb3-\xbc\xbf]|\xaa[\xb0-\xbd]|\xac[\x80-\x83\xb4\xb6-\xba\xbc]|\xad[\x82\xab-\xb3]|\xae[\x80\x81\xa2-\xa5\xa8\xa9\xab-\xad]|\xaf[\xa6\xa8\xa9\xad\xaf-\xb1]|\xb0[\xac-\xb3\xb6\xb7]|\xb3[\x90-\x92\x94-\xa0\xa2-\xa8\xad\xb4\xb8\xb9]|\xb7[\x80-\xb5\xbb-\xbf])|\xe2(?:\x83[\x90-\x9c\xa1\xa5-\xb0]|\xb3[\xaf-\xb1]|\xb5\xbf|\xb7[\xa0-\xbf])|\xe3(?:\x80[\xaa-\xad]|\x82[\x99\x9a])|\xea(?:\x99[\xaf\xb4-\xbd]|\x9a[\x9e\x9f]|\x9b[\xb0\xb1]|\xa0[\x82\x86\x8b\xa5\xa6]|\xa3[\x84\x85\xa0-\xb1]|\xa4[\xa6-\xad]|\xa5[\x87-\x91]|\xa6[\x80-\x82\xb3\xb6-\xb9\xbc]|\xa7\xa5|\xa8[\xa9-\xae\xb1\xb2\xb5\xb6]|\xa9[\x83\x8c\xbc]|\xaa[\xb0\xb2-\xb4\xb7\xb8\xbe\xbf]|\xab[\x81\xac\xad\xb6]|\xaf[\xa5\xa8\xad])|\xef(?:\xac\x9e|\xb8[\x80-\x8f\xa0-\xaf])|\xf0(?:\x90(?:\x87\xbd|\x8b\xa0|\x8d[\xb6-\xba]|\xa8[\x81-\x83\x85\x86\x8c-\x8f\xb8-\xba\xbf]|\xab[\xa5\xa6])|\x91(?:\x80[\x81\xb8-\xbf]|\x81[\x80-\x86\xbf]|\x82[\x80\x81\xb3-\xb6\xb9\xba]|\x84[\x80-\x82\xa7-\xab\xad-\xb4]|\x85\xb3|\x86[\x80\x81\xb6-\xbe]|\x87[\x8a-\x8c]|\x88[\xaf-\xb1\xb4\xb6\xb7\xbe]|\x8b[\x9f\xa3-\xaa]|\x8c[\x80\x81\xbc]|\x8d[\x80\xa6-\xac\xb0-\xb4]|\x90[\xb8-\xbf]|\x91[\x82-\x84\x86]|\x92[\xb3-\xb8\xba\xbf]|\x93[\x80\x82\x83]|\x96[\xb2-\xb5\xbc\xbd\xbf]|\x97[\x80\x9c\x9d]|\x98[\xb3-\xba\xbd\xbf]|\x99\x80|\x9a[\xab\xad\xb0-\xb5\xb7]|\x9c[\x9d-\x9f\xa2-\xa5\xa7-\xab]|\xb0[\xb0-\xb6\xb8-\xbd\xbf]|\xb2[\x92-\xa7\xaa-\xb0\xb2\xb3\xb5\xb6])|\x96(?:\xab[\xb0-\xb4]|\xac[\xb0-\xb6]|\xbe[\x8f-\x92])|\x9b\xb2[\x9d\x9e]|\x9d(?:\x85[\xa7-\xa9\xbb-\xbf]|\x86[\x80-\x82\x85-\x8b\xaa-\xad]|\x89[\x82-\x84]|\xa8[\x80-\xb6\xbb-\xbf]|\xa9[\x80-\xac\xb5]|\xaa[\x84\x9b-\x9f\xa1-\xaf])|\x9e(?:\x80[\x80-\x86\x88-\x98\x9b-\xa1\xa3\xa4\xa6-\xaa]|\xa3[\x90-\x96]|\xa5[\x84-\x8a]))|\xf3\xa0(?:[\x84-\x86][\x80-\xbf]|\x87[\x80-\xaf])'; // 1690 code points.

			// Generated by "gen_script_regex_alts.php" from "http://www.unicode.org/Public/9.0.0/ucd/Scripts.txt".
			static $latin_regex_alts = '[\x41-\x5a\x61-\x7a]|\xc2[\xaa\xba]|\xc3[\x80-\x96\x98-\xb6\xb8-\xbf]|[\xc4-\xc9][\x80-\xbf]|\xca[\x80-\xb8]|\xcb[\xa0-\xa4]|\xe1(?:\xb4[\x80-\xa5\xac-\xbf]|\xb5[\x80-\x9c\xa2-\xa5\xab-\xb7\xb9-\xbf]|\xb6[\x80-\xbe]|[\xb8-\xbb][\x80-\xbf])|\xe2(?:\x81[\xb1\xbf]|\x82[\x90-\x9c]|\x84[\xaa\xab\xb2]|\x85[\x8e\xa0-\xbf]|\x86[\x80-\x88]|\xb1[\xa0-\xbf])|\xea(?:\x9c[\xa2-\xbf]|\x9d[\x80-\xbf]|\x9e[\x80-\x87\x8b-\xae\xb0-\xb7]|\x9f[\xb7-\xbf]|\xac[\xb0-\xbf]|\xad[\x80-\x9a\x9c-\xa4])|\xef(?:\xac[\x80-\x86]|\xbc[\xa1-\xba]|\xbd[\x81-\x9a])'; // 1350 code points.

			$string = preg_replace( '/(?<=' . $latin_regex_alts . ')(?:' . $mn_regex_alts . ')+/', '', $string );
		}

		return $string;
	}
endif;

if ( ! function_exists( '_wp_can_use_pcre_ucp' ) ) :
	// From: https://core.trac.wordpress.org/attachment/ticket/24661/24661.6.patch
	/**
	 * Returns whether PCRE Unicode character properties (UCP, eg \p{L}) are available for use.
	 *
	 * @param bool $set - Used for testing only
	 *             null   : default - get PCRE UCP capability
	 *             false  : Used for testing - return false for future calls to this function
	 *             'reset': Used for testing - restore default behavior of this function
	 *
	 * @since 4.7.0
	 * @access private
	 *
	 * @staticvar string $ucp_pcre
	 *
	 * @ignore
	 */
	function _wp_can_use_pcre_ucp( $set = null ) {
		static $ucp_pcre = 'reset';

		if ( null !== $set ) {
			$ucp_pcre = $set;
		}

		if ( 'reset' === $ucp_pcre ) {
			$ucp_pcre = false !== @preg_match( '/\p{L}/u', '' );
		}

		return $ucp_pcre;
	}
endif;
