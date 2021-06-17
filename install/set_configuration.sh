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
dokku config:set:file essential.env

#dokku plugin:install https://github.com/dokku/dokku-maintenance.git maintenance