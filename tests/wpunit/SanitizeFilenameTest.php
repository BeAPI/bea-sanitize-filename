<?php
use PHPUnit\Framework\TestCase;

class SanitizeFilenameTest extends TestCase {

	public function test_dummy() {
		$this->assertSame( 'should-not-change.lol', sanitize_file_name( 'should-not-change.lol' ) );
	}

	public function test_has_filter() {
		$this->assertTrue( (bool) has_filter( 'sanitize_file_name_chars', 'bea_sanitize_file_name_chars' ) );
		$this->assertTrue( (bool) has_filter( 'sanitize_file_name', 'bea_sanitize_file_name' ) );
	}

	public function test_replaces_accents() {
		$in  = 'àáâãäåæœçćčèéêëìíîïñòóôõöøùúûüýÿ.jpg';
		$out = 'aaaaaaaeoeccceeeeiiiinoooooouuuuyy.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_convert_to_lowercase() {
		$in  = 'ABCDÇ.jpg';
		$out = 'abcdc.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_convert_underscore_to_dashes() {
		$in  = 'filename_with_underscore.jpg';
		$out = 'filename-with-underscore.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_remove_at_sign() {
		$in  = 'filename@with@arobase.jpg';
		$out = 'filenamewitharobase.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_convert_spaces_to_dashes() {
		$in  = ' testing  filename with    lots of  spaces    .jpg';
		$out = 'testing-filename-with-lots-of-spaces-.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_remove_custom_chars() {
		$in  = '’‘“”«»‹›—€[]{}.jpg';
		$out = 'e.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_remove_custom_chars_not_euro() {
		$in  = '’‘“”«»‹›—[]{}.jpg';
		$out = 'unnamed-file.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_remove_copyright_char() {
		$in  = 'copyright©file.jpg';
		$out = 'copyrightfile.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_replaces_uppercase_accent() {
		$in  = 'À-file.jpg';
		$out = 'a-file.jpg';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}

	public function test_non_latin() {
		$in  = '真っ黒い.png';
		$out = '真っ黒い.png';
		$this->assertSame( $out, sanitize_file_name( $in ) );
	}
}
