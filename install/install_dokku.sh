#!/bin/bash
HOST=$1
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
sudo systemctl stop dokku-installer
dokku domains:add-global $HOST