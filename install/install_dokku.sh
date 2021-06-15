#!/bin/bash
sudo apt update -y
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add
sudo add-apt-repository -y "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable"
sudo apt update -y
apt-cache policy docker-ce
sudo apt install -y docker-ce
sudo apt install -y nginx
sudo ufw allow 'Nginx Full'
wget https://raw.githubusercontent.com/dokku/dokku/master/bootstrap.sh;
chmod +x bootstrap.sh
sudo DOKKU_TAG=v0.21.4 bash bootstrap.sh
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

dokku plugin:install https://github.com/dokku/dokku-maintenance.git maintenance