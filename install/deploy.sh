#!/bin/bash
HOST=$1
REMOTE=dokku_$HOST
git remote add $REMOTE dokku@$HOST:medal-data
git push $REMOTE feature/passport:master