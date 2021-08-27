#!/bin/bash

set -e

#connect via ssh to the staging server
ssh $SSH_USER@$SSH_HOST 


echo "starting the deployment pipeline" 

#navigate to the pipeline directory
cd wordpress/pipeline

#configure git to exclude any unimportant files to be pulled into the staging server
git config core.sparsecheckout true 
rm .git/info/sparse-checkout 2> /dev/null 
cat >> .git/info/sparse-checkout <<EOL
/*
!README.md
!*jpg
!*jpeg
EOL 

#pull from dev branch
git checkout dev 
git pull 

#run the docker service
sudo docker-compose up -d

echo "deployment is finished"