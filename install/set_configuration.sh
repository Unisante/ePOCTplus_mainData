#!/bin/bash


set_config() {
    dokku config:set medal-data $1
}
# Create the App
dokku apps:create medal-data
# Install the postgres DB plugin
sudo dokku plugin:install https://github.com/dokku/dokku-postgres.git postgres
# Create Database
dokku postgres:create medal-data-db
# Link the App to the database
dokku postgres:link medal-data-db medal-data
# Set Config variables for Laravel
set_config APP_ENV=local
set_config APP_DEBUG=true
set_config DB_CONNECTION=postgres
set_config LANGUAGE=en
set_config "STUDY_ID=Dynamic Tanzania"
set_config BUILDPACK_URL=https://github.com/heroku/heroku-buildpack-php


#dokku plugin:install https://github.com/dokku/dokku-maintenance.git maintenance


