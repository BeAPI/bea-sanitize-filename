#!/usr/bin/env bash

composer install --prefer-dist
mkdir wordpress
cd wordpress
wp core download
wp config create --dbname="wordpress" --dbuser="wordpress" --dbpass="wordpress" --dbhost="database:3306" --dbprefix="wp_"
wp core install --url="wp.localhost" --title="Test" --admin_user="beapi" --admin_password="beapi" --admin_email="admin@beapi.fr" --skip-email
wp rewrite structure '/%postname%/' --hard
mkdir -p wordpress/wp-content/plugins/bea-sanitize-filename
ln -s /app/bea-sanitize-filename.php /app/wordpress/wp-content/plugins/bea-sanitize-filename/bea-sanitize-filename.php