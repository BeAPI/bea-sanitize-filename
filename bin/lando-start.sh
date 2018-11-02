#!/usr/bin/env bash

# exit on error
set -e

WP_VERSION="${WP_VERSION:-latest}"
WP_LOCALE="${WP_LOCALE:-fr_FR}"
PHP_VERSION="${PHP_VERSION:-7.0}"

cd "$(dirname "$0")"
cd ..

# Hack - awaiting https://github.com/lando/lando/pull/750
perl -pi -we "s/^  php: .*/  php: '$PHP_VERSION'/" .lando.yml


# BrowsterStack Local installation
wget https://www.browserstack.com/browserstack-local/BrowserStackLocal-linux-x64.zip
unzip BrowserStackLocal-linux-x64.zip
rm BrowserStackLocal-linux-x64.zip

lando start -v
lando wp --version || lando bash test/install-wp-cli.sh
rm -rf wordpress/[a-z]*

lando wp core download \
    --path=/app/wordpress/ \
    --version=$WP_VERSION \
    --locale=$WP_LOCALE

lando wp config create \
    --path=/app/wordpress/ \
    --dbname=wordpress \
    --dbuser=wordpress \
    --dbpass=wordpress \
    --dbhost=database

lando wp config set \
    --path=/app/wordpress/ \
    --type=constant \
    --raw \
    WP_AUTO_UPDATE_CORE false

lando wp config set \
    --path=/app/wordpress/ \
    --type=constant \
    --raw \
    WP_DEBUG true

lando wp db reset \
    --path=/app/wordpress/ \
    --yes

wp_url="https://beasanitizefilename.lndo.site"
lando wp core install \
    --path=/app/wordpress/ \
    --url="$wp_url" \
    '--title="My Test Site"' \
    --admin_user="admin" \
    --admin_password="admin" \
    --admin_email="admin@example.com" \
    --skip-email

echo "Testing site URL: $wp_url"

lando composer install

mkdir -p ./wordpress/wp-content/plugins/bea-sanitize-filename/
cp bea-sanitize-filename.php ./wordpress/wp-content/plugins/bea-sanitize-filename/bea-sanitize-filename.php

echo
echo "Test site is ACTIVE: $wp_url"
echo "username: admin"
echo "password: admin"
