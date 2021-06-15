#!/bin/bash
HOST=$1

/bin/bash install/gen_ssh.sh $HOST
#ssh root@$HOST "bash -s" < install_dokku.sh
/bin/bash install/setup_deploy_key.sh $HOST
/bin/bash install/deploy.sh $HOST
