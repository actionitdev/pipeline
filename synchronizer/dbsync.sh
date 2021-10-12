#! /bin/bash

set -e

if [ "$#" -eq 0 ]; then
    echo "No arguments provided."
    exit 1
fi

if [ "$#" -ne 3 ]; then
    echo "Illegal number of parameters"
fi

MYSQL_USER=$1
MYSQL_PASSWORD=$2
MYSQL_DATABASE=$3

sudo docker exec mysql /usr/bin/mysqldump -u $MYSQL_USER --password=$MYSQL_PASSWORD --no-tablespaces $MYSQL_DATABASE > staging.sql && gzip staging.sql

if [ $? == 0 ]; then
    echo "successfully created the dump database file from the production server!"
else
    echo "failed to create the dump database file"
fi

sudo scp -i ~/.ssh/pems/ai-sandbox.pem ~/staging.sql.gz ubuntu@13.237.60.232:/home/ubuntu/wordpress/pipeline/.init

if [ $? == 0 ]; then
    rm ~/wordpress/pipeline/.init/dumpfile.sql.gz
    echo "successfully synchronizing the database!"
else
    echo "failed to synchronize the database"
fi