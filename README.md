# BEA Sanitize Filename

This mu-plugin allows you to sanitize files on upload, which is pretty handy.
You could then ask "Why it's not already into core?". This is [make WordPress core](https://core.trac.wordpress.org/ticket/22363) !

## Compatibility for MAC owners

On MAC, you can also create an accented character with the combination of *`* and the wanted letter. This has the effect to create a character with a special filename system encoding format which is not sanitized.
An [issue](https://github.com/BeAPI/bea-sanitize-filename/issues/1) is open about this.

## Installation

We assume this is a must have plugin, so it's recommended to use at it as mu-plugin. As it is a folder you must use a MU Loader : https://github.com/BeAPI/wp-mu-loader.

### via Composer

1. Add a line to your repositories array: `{ "type": "git", "url": "https://github.com/BeAPI/bea-sanitize-filename" }`
2. Add a line to your require block: `"bea/sanitize-filename": "dev-master"`
3. Run: `composer update`

### Manual

1. Copy the plugin folder into your must use plugins folder.

## Changelog

### 1.0.1 - 05 Aug 2017
* Add readme

### 1.0.0 - 27 Nov 2015
* Initial