#! /bin/bash

set -e

if [ "${S3_ACCESS_KEY_ID}" == "**None**" ]; then
  echo "Warning: You did not set the S3_ACCESS_KEY_ID environment variable."
fi

if [ "${S3_SECRET_ACCESS_KEY}" == "**None**" ]; then
  echo "Warning: You did not set the S3_SECRET_ACCESS_KEY environment variable."
fi

if [ "${S3_BUCKET}" == "**None**" ]; then
  echo "You need to set the S3_BUCKET environment variable."
  exit 1
fi

BACKUP_START_TIME=$(date +"%Y-%m-%dT%H%M%SZ")

copy_s3 () {
  SRC_FILE=$1
  DEST_FILE=$2

  export AWS_ACCESS_KEY_ID=$S3_ACCESS_KEY_ID
  export AWS_SECRET_ACCESS_KEY=$S3_SECRET_ACCESS_KEY
  export AWS_DEFAULT_REGION=$S3_REGION

  if [ "${S3_ENDPOINT}" == "**None**" ]; then
    AWS_ARGS=""
  else
    AWS_ARGS="--endpoint-url ${S3_ENDPOINT}"
  fi

  echo "Uploading ${DEST_FILE} on S3..."

  #cat $SRC_FILE | aws $AWS_ARGS s3 cp - s3://$S3_BUCKET/$S3_PREFIX/$DEST_FILE
  aws s3 cp $SRC_FILE s3://$S3_BUCKET/$S3_PREFIX/$DEST_FILE
  echo "Objects in S3 bucket"
  aws s3 ls s3://$S3_BUCKET/backup/
  if [ $? != 0 ]; then
    >&2 echo "Error uploading ${DEST_FILE} on S3"
  fi

  rm $SRC_FILE
}

echo "Creating backup for wp-content..."

S3_FILE="${BACKUP_START_TIME}-wp.tar.gz"
tar -zcvf /tmp/wp.tar.gz -C / var/www/html/wp-content/index.php
BUCKUP_FILE="/tmp/wp.tar.gz"
copy_s3 $BUCKUP_FILE $S3_FILE
echo "Backup wp-content successful"

echo "wp-content backup finished"