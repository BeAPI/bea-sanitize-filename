<?php
/**
 * Class SampleTest
 *
 * @package Bea_Sanitize_Filename
 */

/**
 * Sample test case.
 */
class SanitizeFilenameTest extends WP_UnitTestCase {

	function test_dummy() {
		$this->assertSame( 'should-not-change.lol', sanitize_file_name( 'should-not-change.lol' ) );
	}

	function test_replaces_accents() {
		$in  = 'àáâãäåæœçćčèéêëìíîïñòóôõöøùúûüýÿ';
		$out = 'aaaaaaaeoeccceeeeiiiinoooooouuuuyy';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	/*function test_replaces_combining_accents() {
		$in  = 'àáâãäåçèéêëìíîïñòóôõöùúûüýÿ';
		$out = 'aaaaaaceeeeiiiinooooouuuuyy';
		$this->assertEquals( $out, sanitize_file_name( $in ) );
	}*/

	function test_convert_to_lowercase() {
		$in = 'ABCDÇ';
		$out = 'abcdc';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_convert_underscore_to_dashes() {
		$in = 'filename_with_underscore';
		$out = 'filename-with-underscore';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_convert_spaces_to_dashes() {
		$in = ' testing  filename with    lots of  spaces    ';
		$out = 'testing-filename-with-lots-of-spaces';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	function test_remove_custom_chars() {
		$in = '’‘“”«»‹›—€[]{}';
		$out = '';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}
}
