<?php
/**
 * Class SampleTest
 *
 * @package Bea_Sanitize_Filename
 */

/**
 * Sample test case.
 */
class SanitizeFilenameTest extends \Codeception\TestCase\WPTestCase {

	function test_dummy() {
		$this->assertSame( 'should-not-change.lol', sanitize_file_name( 'should-not-change.lol' ) );
	}

	function test_has_filter() {
		$this->assertTrue( (bool) has_filter( 'sanitize_file_name_chars', 'bea_sanitize_file_name_chars' ) );
		$this->assertTrue( (bool) has_filter( 'sanitize_file_name', 'bea_sanitize_file_name' ) );
	}

	function test_replaces_accents() {
		$in  = 'àáâãäåæœçćčèéêëìíîïñòóôõöøùúûüýÿ.jpg';
		$out = 'aaaaaaaeoeccceeeeiiiinoooooouuuuyy.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_convert_to_lowercase() {
		$in  = 'ABCDÇ.jpg';
		$out = 'abcdc.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_convert_underscore_to_dashes() {
		$in  = 'filename_with_underscore.jpg';
		$out = 'filename-with-underscore.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_convert_spaces_to_dashes() {
		$in  = ' testing  filename with    lots of  spaces    .jpg';
		$out = 'testing-filename-with-lots-of-spaces.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_remove_custom_chars() {
		$in  = '’‘“”«»‹›—€[]{}.jpg';
		$out = 'unnamed-file.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}
}
