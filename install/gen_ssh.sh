#!/bin/bash
HOST=$1
ssh-keygen -f ~/.ssh/medal-data-deploy-key -t ecdsa -b 521
ssh-copy-id -i ~/.ssh/medal-data-deploy-key -oPubkeyAuthentication=no root@$HOST
#echo "Host $HOST" >> ~/.ssh/config
#echo "  IdentityFile ~/.ssh/medal-data-deploy-key" >> ~/.ssh/config
scp ~/.ssh/medal-data-deploy-key.pub -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST:.
ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST 'sudo cp ./medal-data-deploy-key.pub /root/.ssh/medal-data-deploy-key.pub'

