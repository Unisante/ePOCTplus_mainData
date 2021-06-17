#!/bin/bash
dokku config:set medal-data APP_KEY=$(dokku run medal-data php artisan key:generate --show)
dokku run medal-data php artisan db:wipe --force
dokku run medal-data php artisan migrate --force
dokku run medal-data php artisan db:seed --force