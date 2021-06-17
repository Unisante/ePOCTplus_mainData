#!/bin/bash


# Create the App
dokku apps:create medal-data
# Install the postgres DB plugin
sudo dokku plugin:install https://github.com/dokku/dokku-postgres.git postgres
# Create Database
dokku postgres:create medal-data-db
# Link the App to the database
dokku postgres:link medal-data-db medal-data
# Set Config variables for Laravel
dokku config:set medal-data DB_CONNECTION=postgres
# Add the PHP buildpack to the apps config
dokku config:set medal-data BUILDPACK_URL=https://github.com/heroku/heroku-buildpack-php

dokku config:set medal-data APP_ENV=local

#dokku plugin:install https://github.com/dokku/dokku-maintenance.git maintenance