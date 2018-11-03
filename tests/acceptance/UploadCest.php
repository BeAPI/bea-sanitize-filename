<?php


class UploadCest {

	public function _before( AcceptanceTester $I ) {
		$I->loginAsAdmin();
	}

	public function TestABCD( AcceptanceTester $I ) {
		$I->wantTo( 'Test the upload abcd file' );
		$I->amOnPage( 'wp-admin/media-new.php?browser-uploader' );

		$I->attachFile( '#async-upload', 'ABCDEC.jpg' );
		$I->click( '#file-form input[type="submit"]' );
		$I->amOnPage( 'wp-admin/upload.php?mode=list' );
		$I->see( 'ABCDEC.jpg' );
	}

	public function TestUnderscore( AcceptanceTester $I ) {
		$I->wantTo( 'Test the upload underscore file' );
		$I->amOnPage( 'wp-admin/media-new.php?browser-uploader' );

		$I->attachFile( '#async-upload', 'filename-with_underscore.jpg' );
		$I->click( '#file-form input[type="submit"]' );
		$I->amOnPage( 'wp-admin/upload.php?mode=list' );
		$I->see( 'filename-with_underscore.jpg' );

	}

	public function TestSpaces( AcceptanceTester $I ) {
		$I->wantTo( 'Test the upload spaced file' );
		$I->amOnPage( 'wp-admin/media-new.php?browser-uploader' );

		$I->attachFile( '#async-upload', 'testing  filename with    lots of  spaces    .jpg' );
		$I->click( '#file-form input[type="submit"]' );
		$I->amOnPage( 'wp-admin/upload.php?mode=list' );
		$I->see( 'testing-filename-with-lots-of-spaces-.jpg' );

	}

	public function TestSpecials( AcceptanceTester $I ) {
		$I->wantTo( 'Test the upload special chars file' );
		$I->amOnPage( 'wp-admin/media-new.php?browser-uploader' );

		$I->attachFile( '#async-upload', '’‘“”«»‹›—€[]{}.jpg' );
		$I->click( '#file-form input[type="submit"]' );
		$I->amOnPage( 'wp-admin/upload.php?mode=list' );
		$I->see( 'unnamed-file.jpg' );

	}
}
