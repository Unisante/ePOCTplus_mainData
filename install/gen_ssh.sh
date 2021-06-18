#!/bin/bash
HOST=$1
ssh-keygen -f ~/.ssh/medal-data-deploy-key -t ecdsa -b 521
echo "setting public key in server"
ssh-copy-id -i ~/.ssh/medal-data-deploy-key -oPubkeyAuthentication=no root@$HOST
echo "Host $HOST" >> ~/.ssh/config
echo "  IdentityFile ~/.ssh/medal-data-deploy-key" >> ~/.ssh/config
echo "copying key to server"
scp -v -oIdentitiesOnly=yes -oIdentityFile=~/.ssh/medal-data-deploy-key ~/.ssh/medal-data-deploy-key.pub  root@$HOST:.
ssh -oIdentitiesOnly=yes -oIdentityFile=~/.ssh/medal-data-deploy-key root@$HOST 'sudo cp ./medal-data-deploy-key.pub /root/.ssh/medal-data-deploy-key.pub'

