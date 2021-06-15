#!/bin/bash
dokku maintenance:on medal-data
dokku config:set medal-data APP_KEY=$(dokku run medal-data php artisan key:generate --show)
dokku run medal-data php artisan migrate
dokku run medal-data php artisan db:seed
dokku maintenance:off medal-data