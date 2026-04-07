<?php
use PHPUnit\Framework\TestCase;

class SanitizeFilenameTest extends TestCase {

	/**
	 * @dataProvider trivial_filename_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_dummy( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function trivial_filename_provider() {
		return array(
			'simple_lol' => array( 'should-not-change.lol', 'should-not-change.lol' ),
			'simple_txt' => array( 'plain.txt', 'plain.txt' ),
		);
	}

	public function test_has_filter() {
		$this->assertTrue( (bool) has_filter( 'sanitize_file_name_chars', 'bea_sanitize_file_name_chars' ) );
		$this->assertTrue( (bool) has_filter( 'sanitize_file_name', 'bea_sanitize_file_name' ) );
	}

	/**
	 * Accented letters listed in `bea_sanitize_file_name_chars` are removed (not transliterated) before the basename is lowercased.
	 *
	 * @dataProvider replaces_accents_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_replaces_accents( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function replaces_accents_provider() {
		return array(
			'latin_long_sequence' => array(
				'àáâãäåæœçćčèéêëìíîïñòóôõöøùúûüýÿ.jpg',
				'aaaaaaaeoeccceeeeiiiinoooooouuuuyy.jpg',
			),
			// Core `remove_accents()` transliterates before this plugin strips remaining specials.
			'single_acute_e'      => array( 'mot-clé.jpg', 'mot-cle.jpg' ),
			'german_umlaut_o'     => array( 'schön.png', 'schon.png' ),
			'cedilla'             => array( 'français.gif', 'francais.gif' ),
		);
	}

	/**
	 * @dataProvider lowercase_basename_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_convert_to_lowercase( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function lowercase_basename_provider() {
		return array(
			'ascii_with_cedilla_strip' => array( 'ABCDÇ.jpg', 'abcdc.jpg' ),
			'all_caps_png'       => array( 'HELLO.PNG', 'hello.PNG' ),
			// Extension casing is preserved; only the basename is lowercased.
			'mixed_case_one_dot' => array( 'FooBar.Baz', 'foobar.Baz' ),
		);
	}

	/**
	 * @dataProvider underscore_to_dash_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_convert_underscore_to_dashes( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function underscore_to_dash_provider() {
		return array(
			'single_underscores' => array( 'filename_with_underscore.jpg', 'filename-with-underscore.jpg' ),
			// Each `_` becomes one `-`; adjacent underscores are not collapsed.
			'multiple_adjacent'  => array( 'a__b__c.txt', 'a--b--c.txt' ),
			// Core trims leading/trailing `._-` then this plugin turns inner `_` into `-`.
			'leading_trailing'   => array( '_wrap_.pdf', 'wrap-.pdf' ),
		);
	}

	/**
	 * Only the final extension suffix is removed for basename processing; a naive global replace would drop every matching substring.
	 * Middle segments longer than five letters avoid WordPress multi-extension “fake ext” underscores (see core `sanitize_file_name()`).
	 *
	 * @dataProvider extension_suffix_case_provider
	 *
	 * @param string $input    Raw filename as sent on upload (before this plugin’s filter).
	 * @param string $expected Full `sanitize_file_name()` result after WordPress core + plugin.
	 */
	public function test_extension_removed_only_from_suffix( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function extension_suffix_case_provider() {
		return array(
			'ascii_css_token_twice_long_middle' => array( 'something.css.backup.css', 'something.css.backup.css' ),
			// Core marks the first `json` segment as a disallowed “extension” and appends `_`; this plugin then turns `_` into `-`.
			'ascii_json_token_twice'            => array( 'data.json.backup.json', 'data.json-.backup.json' ),
			'ascii_zip_one_long_middle'         => array( 'archive.longbackup.zip', 'archive.longbackup.zip' ),
			'ascii_duplicate_word_middle'      => array( 'prefix.backup.backup.zip', 'prefix.backup.backup.zip' ),
			'underscore_before_long_middle'    => array( 'A_B.backup.css', 'a-b.backup.css' ),
			'accented_basename_ascii_middle'   => array( 'Café.longueur.zip', 'cafe.longueur.zip' ),
			'accented_resume_long_middle'      => array( 'Résumé.documentation.pdf', 'resume.documentation.pdf' ),
			'utf8_cyrillic_long_middle'        => array( 'report.длинный.txt', 'report.длинный.txt' ),
			'utf8_cjk_long_middle'             => array( '写真.longbackup.jpg', '写真.longbackup.jpg' ),
			'utf8_mixed_ascii_cjk'             => array( 'export.データバックアップ.csv', 'export.データバックアップ.csv' ),
			// Middle segment must be long enough that core does not add “fake extension” underscores (see `final` in `Draft.final.CSV`).
			'mixed_case_long_middle'           => array( 'DRAFT.longchapter.PDF', 'draft.longchapter.PDF' ),
		);
	}

	/**
	 * Typical camera exports, invoices, and messy user-chosen names (spaces, mixed case, underscores).
	 *
	 * @dataProvider upload_style_filename_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_upload_style_filenames( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function upload_style_filename_provider() {
		return array(
			'camera_jpeg_uppercase'       => array( 'IMG_0001.JPEG', 'img-0001.JPEG' ),
			'invoice_underscores'         => array( 'FACTURE_NO_42.xml', 'facture-no-42.xml' ),
			'spaces_and_mixed_case_png'   => array( 'Summer 2024 BEACH.png', 'summer-2024-beach.png' ),
			'copy_suffix_parentheses'     => array( 'Client Copy (2).jpg', 'client-copy-2.jpg' ),
			'leading_trailing_space_trim' => array( '  scan-001.tiff  ', 'scan-001.tiff' ),
			'numeric_basename'            => array( '20240407_153022.pdf', '20240407-153022.pdf' ),
			'dashed_export_name'          => array( 'Export - Q1 - Final.svg', 'export-q1-final.svg' ),
			// `app` is treated as a fake intermediate extension: core appends `_`, plugin maps it to `-`.
			'multiple_dots_short_middle'  => array( 'bundle.app.release.zip', 'bundle.app-.release.zip' ),
		);
	}

	/**
	 * UTF-8 filenames that should survive core + plugin when valid for uploads.
	 *
	 * @dataProvider utf8_filename_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_utf8_filenames( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function utf8_filename_provider() {
		return array(
			'japanese_with_space'  => array( '会議 メモ.docx', '会議-メモ.docx' ),
			'arabic_basename'        => array( 'تقرير-شهري.pdf', 'تقرير-شهري.pdf' ),
			'japanese_single_stem' => array( 'ファイル名.pdf', 'ファイル名.pdf' ),
		);
	}

	/**
	 * Several rules at once: core sanitization, `@` stripping, accents, underscores, spaces, multi-dot basenames, UTF-8, mixed case extensions.
	 *
	 * @dataProvider mixed_combination_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_mixed_filename_combinations( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function mixed_combination_provider() {
		return array(
			'accent_space_underscore_multi_dot' => array(
				'Résumé_FINAL  batch.vacationphotos.jpg',
				'resume-final-batch.vacationphotos.jpg',
			),
			'at_accent_multi_dot_csv'           => array(
				'café@shop.longitem.csv',
				'cafeshop.longitem.csv',
			),
			'ascii_underscore_utf8_multi_dot'   => array(
				'SCAN_写真.longbackup.JPEG',
				'scan-写真.longbackup.JPEG',
			),
			'paren_space_accent_single_ext'     => array(
				' Facture (copie) Été 2024.pdf ',
				'facture-copie-ete-2024.pdf',
			),
			'upper_multi_dot_underscore_css'    => array(
				'Lib_v1.utilities.backup.css',
				'lib-v1.utilities.backup.css',
			),
		);
	}

	/**
	 * @dataProvider at_sign_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_remove_at_sign( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function at_sign_provider() {
		return array(
			'multiple_at' => array( 'filename@with@arobase.jpg', 'filenamewitharobase.jpg' ),
			'single_at'   => array( 'user@name.png', 'username.png' ),
			'at_edges'    => array( '@start@end@.gif', 'startend.gif' ),
		);
	}

	/**
	 * @dataProvider spaces_to_dashes_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_convert_spaces_to_dashes( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function spaces_to_dashes_provider() {
		return array(
			'long_run'   => array(
				' testing  filename with    lots of  spaces    .jpg',
				'testing-filename-with-lots-of-spaces-.jpg',
			),
			'two_words'  => array( 'hello world.png', 'hello-world.png' ),
			'tab_mix'    => array( "one\ttwo\tthree.pdf", 'one-two-three.pdf' ),
		);
	}

	/**
	 * @dataProvider custom_chars_strip_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_remove_custom_chars( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function custom_chars_strip_provider() {
		return array(
			'quotes_brackets_euro' => array( '’‘“”«»‹›—€[]{}.jpg', 'e.jpg' ),
			'typographic_only'     => array( '«hello».txt', 'hello.txt' ),
			'em_dash_middle'       => array( 'part—piece.zip', 'partpiece.zip' ),
		);
	}

	/**
	 * @dataProvider custom_chars_no_euro_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_remove_custom_chars_not_euro( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function custom_chars_no_euro_provider() {
		return array(
			'all_but_euro'  => array( '’‘“”«»‹›—[]{}.jpg', 'unnamed-file.jpg' ),
			'braces_only'   => array( '{x}.png', 'x.png' ),
			'guillemets'    => array( '‹x›.gif', 'x.gif' ),
		);
	}

	/**
	 * @dataProvider copyright_char_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_remove_copyright_char( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function copyright_char_provider() {
		return array(
			'middle'  => array( 'copyright©file.jpg', 'copyrightfile.jpg' ),
			'prefix'  => array( '©notice.txt', 'notice.txt' ),
			'suffix'  => array( 'brand©.png', 'brand.png' ),
		);
	}

	/**
	 * @dataProvider uppercase_accent_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_replaces_uppercase_accent( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function uppercase_accent_provider() {
		return array(
			'leading_a_grave' => array( 'À-file.jpg', 'a-file.jpg' ),
			// Core `remove_accents()` runs before character stripping; É becomes E.
			'capital_e_acute' => array( 'Écran.tiff', 'ecran.tiff' ),
			'capital_c_ced'   => array( 'ÇA-va.bmp', 'ca-va.bmp' ),
		);
	}

	/**
	 * @dataProvider non_latin_provider
	 *
	 * @param string $input    Raw filename.
	 * @param string $expected After `sanitize_file_name()`.
	 */
	public function test_non_latin( $input, $expected ) {
		$this->assertSame( $expected, sanitize_file_name( $input ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function non_latin_provider() {
		return array(
			'japanese_black' => array( '真っ黒い.png', '真っ黒い.png' ),
			'chinese_short'  => array( '文档.rtf', '文档.rtf' ),
		);
	}
}
