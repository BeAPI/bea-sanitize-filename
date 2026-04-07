=== BEA - Sanitize Filename ===
Contributors: beapi, maximeculea, momo360modena
Donate link: http://paypal.me/BeAPI
Tags: uploads, sanitize, media
Requires at least: 4.0
Requires php: 8.0
Tested up to: 7.0
Stable tag: 2.0.10
License: GPLv3 or later
License URI: https://github.com/BeAPI/bea-sanitize-filename/blob/master/LICENSE.md

Remove all punctuation and accents from the filename of uploaded files.

== Description ==

This plugin allows you to sanitize files on upload, which is pretty handy. It means all punctuation and accents from the filename of uploaded files will be removed.

## Features

- All accented letters will be converted to same letters without accent
- Special characters will be deleted
- Underscores will be replaced by hyphens

## Warning For Mac Owners

On MAC, you can also create an accented character with the combination of ` and the wanted letter. This has the effect to create a character with a special filename system encoding format which is not sanitized. An [issue](https://github.com/BeAPI/bea-sanitize-filename/issues/1) is already open about this.

## Who ?

Created by [Be API](https://beapi.fr), the French WordPress leader agency since 2009. Based in Paris, we are more than 30 people and always [hiring](https://beapi.workable.com) some fun and talented guys. So we will be pleased to work with you.

This plugin is only maintained, which means we do not guarantee some free support. Consider reporting an [issue](https://github.com/BeAPI/bea-sanitize-filename/issues) and be patient.

To facilitate the process of submitting an issue and quicker answer, we only use Github, so don't use WP.Org support, it will not be considered. 

== Installation ==

= Requirements =

= WordPress =
- Download and install using the built-in WordPress plugin installer.
- Site activate in the "Plugins" area of the admin.
- Nothing more, this plugin is ready to use !

== Frequently Asked Questions ==

= Will this plugin affect existing files ? =

No.

Only after plugin activation, while a file is uploaded to the library. However, inf the future a tool will be available to sanitize old files.

= Can I use into a multisite ? =

Yes.

You just need to activate on each site.

== Changelog ==

= 2.0.10 - 07 Apr 2026 =
- Improve `sanitize_file_name` handling so only the real file suffix is transformed (avoid stripping when an extension-like segment appears earlier in the name)
- Expand PHPUnit coverage with data providers and upload-style filename cases
- Tidy `sanitize_file_name_chars` flow and align PHPCS with PHP 8.0; refine Composer/npm test scripts

= 2.0.9 - 02 Apr 2026 =
- Fix invalid smart quotes in special characters list
- Migrate tests to `wp-env` with PHPUnit unit tests only
- Improve testing documentation and scripts

= 2.0.8 - 02 Apr 2026 =
- Add `@` character to the sanitized characters list

= 2.0.7 - 20 Sept 2022 =
- Non Latin characters in filename are handled

= 2.0.6 - 05 Oct 2018 =
- #8 : Add More accentuated characters

= 2.0.5 - 12 Feb 2018 =
- i18n

= 2.0.0 - 02 Feb 2018 =
- Go to WP

= 1.0.4 - 19 Dec 2017 =
- Branding refactor

= 1.0.3 - 19 Dec 2017 =
- Fix some PHP notices

= 1.0.2 - 08 Sept 2017 = 
- Update `sanitize_file_name_chars` list

= 1.0.1 - 05 Aug 2017 =
- Add readme
- Replace underscore by hyphen
- Only lowercase

= 1.0.0 - 27 Nov 2015 =
- Initial
