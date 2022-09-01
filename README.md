<a href="https://beapi.fr">![Be API Github Banner](.wordpress.org/banner-github.png)</a>

# BEA - Sanitize Filename

This plugin will remove all punctuation and accents from the filename of uploaded files, which is pretty handy.
You could then ask "Why it's not already into core?". This is [make WordPress core](https://core.trac.wordpress.org/ticket/22363) !

# How ?

## Requirements

- No requirements

## Installation

As we assume this is a must have plugin, it's recommended to use at it as mu-plugin. As it is a folder, you must use a MU Loader : https://github.com/BeAPI/wp-mu-loader.

### WordPress

- Download and copy the plugin folder into your must-use plugins folder.
- Nothing more, this plugin is ready to use !

### [Composer](http://composer.rarst.net/)

- Add repository source : `{ "type": "git", "url": "https://github.com/BeAPI/bea-sanitize-filename" }`.
- Include `"bea/sanitize-filename": "dev-master"` in your composer file for last master's commits or a tag released.
- Nothing more, this plugin is ready to use !

# What ?

## Features

* All accented letters will be converted to same letters without accent
* Special characters will be deleted
* Underscores will be replaced by hyphens

## Compatibility for MAC owners

On MAC, you can also create an accented character with the combination of *<code>`</code>* and the wanted letter. This has the effect to create a character with a special filename system encoding format which is not sanitized.
An [issue](https://github.com/BeAPI/bea-sanitize-filename/issues/1) is open about this.
  
## Contributing

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
Please refer to the [contributing guidelines](.github/CONTRIBUTING.md) to increase the chance of your pull request to be merged and/or receive the best support for your issue.

### Issues & features request / proposal

If you identify any errors or have an idea for improving the plugin, feel free to open an [issue](../../issues/new). Please provide as much info as needed in order to help us resolving / approve your request.

# Who ?

Created by [Be API](https://beapi.fr), the French WordPress leader agency since 2009. Based in Paris, we are more than 30 people and always [hiring](https://beapi.workable.com) some fun and talented guys. So we will be pleased to work with you.

This plugin is only maintained by the [Be API team](https://beapi.fr), which means we do not guarantee some free support. Consider reporting an [issue](#issues--features-request--proposal) and be patient.

If you really like what we do or want to thank us for our quick work, feel free to [donate](https://www.paypal.me/BeAPI) as much as you want / can, even 1€ is a great gift for buying cofee :)

## License

BEA - Sanitize Filename is licensed under the [GPLv3 or later](LICENSE.md).
