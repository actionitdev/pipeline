#! /bin/bash

set -e

if [ "$#" -eq 0 ]; then
    echo "No arguments provided."
    exit 1
fi

if [ "$#" -ne 7 ]; then
    echo "Illegal number of parameters"
fi


S3_ACCESS_KEY_ID=$1
S3_SECRET_ACCESS_KEY=$2
S3_REGION=$3
MYSQL_PORT=$4
MYSQL_USER=$5
MYSQL_PASSWORD=$6
MYSQL_DATABASE=$7
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

if [ "${MYSQL_USER}" == "**None**" ]; then
  echo "You need to set the MYSQL_USER environment variable."
  exit 1
fi

if [ "${MYSQL_PASSWORD}" == "**None**" ]; then
  echo "You need to set the MYSQL_PASSWORD environment variable or link to a container named MYSQL."
  exit 1
fi
if [ "${MYSQL_PORT}" == "**None**" ]; then
  echo "You need to set the MYSQL_PORT environment variable."
  exit 1
fi

if [ "${MYSQL_DATABASE}" == "**None**" ]; then
  echo "You need to set the MYSQL_DATABASE environment variable or link to a container named MYSQL."
  exit 1
fi

export AWS_ACCESS_KEY_ID=$S3_ACCESS_KEY_ID
export AWS_SECRET_ACCESS_KEY=$S3_SECRET_ACCESS_KEY
export AWS_DEFAULT_REGION=$S3_REGION

sudo docker exec mysql /usr/bin/mysqldump -u $MYSQL_USER --password=$MYSQL_PASSWORD --no-tablespaces $MYSQL_DATABASE > backup_staging.sql && gzip backup_staging.sql
if [ $? == 0 ]; then
    echo "successfully created the dump database file!"
else
    echo "failed to create the dump database file"
fi
aws s3 cp backup_staging.sql.gz s3://actionit-staging/backup/staging/db/"${DUMP_START_TIME}-data.sql.gz"
if [ $? == 0 ]; then
    echo "successfully backup the database!"
else
    echo "failed to transfer the backup to the S3"
fi

