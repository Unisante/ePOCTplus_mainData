#!/bin/bash
HOST=$1
echo "Generating Key Pair"
/bin/bash install/gen_ssh.sh $HOST
echo "Installing Dependencies on server"
ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST "bash -s" < install/install_no_web.sh $HOST
echo "Copying Environment file"
scp install/essential.env -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST:.
echo "Configuring Database and Environment Variables on Server"
ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST "bash -s" < install/set_configuration.sh
#/bin/bash install/setup_deploy_key.sh $HOST
echo "Deploying Application to Server"
/bin/bash install/deploy.sh $HOST
echo "Running Post Deploy Configuration"
ssh -o IdentitiesOnly=yes -o IdentityFile=~/.ssh/medal-data-deploy-key root@$HOST "bash -s" < install/post_deploy.sh
