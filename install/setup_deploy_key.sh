#!/bin/bash
HOST=$1
scp ~/.ssh/medal-data-deploy-key.pub root@$HOST:.
ssh root@$HOST 'dokku ssh-keys:add deploy-key ./medal-data-deploy-key.pub'