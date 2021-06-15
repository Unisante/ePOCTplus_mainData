#!/bin/bash
HOST=$1
ssh-keygen -f ~/.ssh/medal-data-deploy-key -t ecdsa -b 521
ssh-copy-id -i ~/.ssh/medal-data-deploy-key root@$HOST

