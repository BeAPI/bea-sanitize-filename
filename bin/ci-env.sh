#!/usr/bin/env bash

echo "BROWSERSTACK_KEY=${BROWSERSTACK_KEY}" >> .env
echo "BROWSERSTACK_USERNAME_REAL=${BROWSERSTACK_USERNAME_REAL}" >> .env
echo "TRAVIS_BUILD_NUMBER=${TRAVIS_BUILD_NUMBER}" >> .env
