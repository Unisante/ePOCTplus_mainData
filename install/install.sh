#!/bin/bash
HOST=$1
/bin/bash install/gen_ssh.sh $HOST
ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST "bash -s" < install/install_no_web.sh $HOST
scp install/essential.env -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST:.

ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST "bash -s" < install/set_configuration.sh
#/bin/bash install/setup_deploy_key.sh $HOST
/bin/bash install/deploy.sh $HOST
ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST "bash -s" < install/post_deploy.sh
