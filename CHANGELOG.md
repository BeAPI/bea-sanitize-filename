# Changelog

## 2.0.10 - 07 Apr 2026
* Improve `sanitize_file_name` handling so only the real file suffix is transformed (avoid stripping when an extension-like segment appears earlier in the name)
* Expand PHPUnit coverage with data providers and upload-style filename cases
* Tidy `sanitize_file_name_chars` flow and align PHPCS with PHP 8.0; refine Composer/npm test scripts

## 2.0.9 - 02 Apr 2026
* Fix invalid smart quotes in special characters list
* Migrate tests to `wp-env` with PHPUnit unit tests only
* Improve testing documentation and scripts

## 2.0.8 - 02 Apr 2026
* Add `@` character to the sanitized characters list

## 2.0.7 - 20 Sept 2022
* Non Latin characters in filename are handled

## 2.0.6 - 05 Oct 2018
* #8 : Add More accentuated characters 

## 2.0.5 - 12 Feb 2018
* i18n

## 2.0.0 - 02 Feb 2018
* Go to WP

## 1.0.4 - 19 Dec 2017
* Branding refactor

## 1.0.3 - 19 Dec 2017
* Fix some PHP notices

## 1.0.2 - 08 Sept 2017
* Update `sanitize_file_name_chars` list

## 1.0.1 - 05 Aug 2017
* Add readme
* Replace underscore by hyphen
* Only lowercase

## 1.0.0 - 27 Nov 2015
* Initial
