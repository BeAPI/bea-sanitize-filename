# BEA Sanitize Filename

This mu-plugin allows you to sanitize files on upload, which is pretty handy.
You could then ask "Why it's not already into core?". This is [make WordPress core](https://core.trac.wordpress.org/ticket/22363) !

## Features

* All accented letters will be converted to same letters without accent
* Special characters will be deleted
* Underscores will be replaced by hyphens

## Installation

We assume this is a must have plugin, so it's recommended to use at it as mu-plugin. As it is a folder you must use a MU Loader : https://github.com/BeAPI/wp-mu-loader.

### via Composer

1. Add a line to your repositories array: `{ "type": "git", "url": "https://github.com/BeAPI/bea-sanitize-filename" }`
2. Add a line to your require block: `"bea/sanitize-filename": "dev-master"`
3. Run: `composer update`

### Manual

1. Copy the plugin folder into your must use plugins folder.

## Compatibility for MAC owners

On MAC, you can also create an accented character with the combination of *`* and the wanted letter. This has the effect to create a character with a special filename system encoding format which is not sanitized.
An [issue](https://github.com/BeAPI/bea-sanitize-filename/issues/1) is open about this.

## Testing

### Introduction

The tests are based on [WP-Browser](https://github.com/lucatume/wp-browser).

You have WPUnit and Acceptance tests.  

### Installation

1. Install [Lando](https://docs.devwithlando.io/installation/installing.html)
2. From command line into the project folder execute `./bin/lando-start.sh`
3. From command line into the project folder execute `lando composer install`

The local url will be https://beasanitizefilename.lndo.site and credentials will be
* user : admin
* password : admin

### Tools
To test the code, just launch :
* For Wpunit tests and desktop : `lando test-local`
* For desktop mobile : `lando test-mobile`

If you need to test the code on BrowserStack, you need to define two environments variables :
* `BROWSERSTACK_USERNAME_REAL` : the usernmae of your browserStack account
* `BROWSERSTACK_KEY` : the key of your browserStack account

/!\ Do not commit theses credentials /!\

## Customization

Need to customize the environment variables ? every codeception file can be overrided bit by bit by creating a new file without the .dist.
So to customize the .env file you'l need to :

* Create a codeception.yml file
* Put into the file :
```
params:
- .env.local
```
* Create a .env.local file and change the desired environment variables like `BROWSERSTACK_KEY`

## Changelog

### 1.0.2 - 08 Sept 2017
* Update `sanitize_file_name_chars` list

### 1.0.1 - 05 Aug 2017
* Add readme
* Replace underscore by hyphen
* Only lowercase

### 1.0.0 - 27 Nov 2015
* Initial