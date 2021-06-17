#!/bin/bash
HOST=$1

/bin/bash install/gen_ssh.sh $HOST
ssh root@$HOST "bash -s" < install/install_no_web.sh
ssh root@$HOST "bash -s" < install/set_configuration.sh
/bin/bash install/setup_deploy_key.sh $HOST
/bin/bash install/deploy.sh $HOST
