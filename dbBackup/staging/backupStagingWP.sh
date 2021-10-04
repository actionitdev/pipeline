#! /bin/bash

set -e


if [ "$#" -eq 0 ]; then
    echo "No arguments provided."
    exit 1
fi

if [ "$#" -ne 3 ]; then
    echo "Illegal number of parameters"
fi

S3_ACCESS_KEY_ID=$1
S3_SECRET_ACCESS_KEY=$2
S3_REGION=$3
DUMP_START_TIME=$(date +"%Y-%m-%dT%H%M%SZ")

if [ "${S3_ACCESS_KEY_ID}" == "**None**" ]; then
  echo "Warning: You did not set the S3_ACCESS_KEY_ID environment variable."
  exit 1
fi

if [ "${S3_SECRET_ACCESS_KEY}" == "**None**" ]; then
  echo "Warning: You did not set the S3_SECRET_ACCESS_KEY environment variable."
  exit 1
fi

if [ "${S3_BUCKET}" == "**None**" ]; then
  echo "You need to set the S3_BUCKET environment variable."
  exit 1
fi

export AWS_ACCESS_KEY_ID=$S3_ACCESS_KEY_ID
export AWS_SECRET_ACCESS_KEY=$S3_SECRET_ACCESS_KEY
export AWS_DEFAULT_REGION=$S3_REGION

mkdir backup

# todo: uncomment these two lines the docker run volumes does not work as intended 
#sudo docker exec wordpress tar --warning=no-file-changed -zcvf wp-content-staging.tar.gz -C / var/www/html/wp-content 
#sudo docker cp wordpress:/var/www/html/wp-content-staging.tar.gz .

docker run --rm --volumes-from wordpress -v ~/backup:/backup ubuntu tar czvf /backup/wp-content-staging.tar.gz var/www/html/wp-content

if [ $? == 0 ]; then
    echo "wp-content backup has been created"
else
    echo "failed to create the wp-content"
fi
mv ~/backup/wp-content-staging.tar.gz wp-content-staging.tar.gz
aws s3 cp wp-content-staging.tar.gz s3://actionit-staging/backup/staging/wp/"${DUMP_START_TIME}-wpcontent-backup.tar.gz"
if [ $? == 0 ]; then
    echo "successfully backup the wp-content!"
else
    echo "failed to transfer the wp-content backup to the S3"
fi
