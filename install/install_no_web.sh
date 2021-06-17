#!/bin/bash
HOST=$1
export DEBIAN_FRONTEND=noninteractive
# install prerequisites
echo "dokku dokku/vhost_enable boolean true" | sudo debconf-set-selections
echo "dokku dokku/web_config boolean false" | sudo debconf-set-selections
echo "dokku dokku/hostname string $HOST" | sudo debconf-set-selections
echo "dokku dokku/skip_key_file boolean true" | sudo debconf-set-selections
echo "dokku dokku/key_file string /root/.ssh/medal-data-deploy-key.pub" | sudo debconf-set-selections
echo "dokku dokku/nginx_enable boolean true" | sudo debconf-set-selections
sudo apt-get update -qq >/dev/null
sudo apt-get -qq -y --no-install-recommends install apt-transport-https

# install docker
wget -nv -O - https://get.docker.com/ | sh

# install dokku
wget -nv -O - https://packagecloud.io/dokku/dokku/gpgkey | apt-key add -
OS_ID="$(lsb_release -cs 2>/dev/null || echo "bionic")"
echo "bionic focal" | grep -q "$OS_ID" || OS_ID="bionic"
echo "deb https://packagecloud.io/dokku/dokku/ubuntu/ ${OS_ID} main" | sudo tee /etc/apt/sources.list.d/dokku.list
sudo apt-get update -qq >/dev/null

sudo apt-get -qq -y install dokku
sudo dokku plugin:install-dependencies --core

