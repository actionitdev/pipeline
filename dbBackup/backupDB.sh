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

if [ "${MYSQL_HOST}" == "**None**" ]; then
  echo "You need to set the MYSQL_HOST environment variable."
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


# if [ "${S3_IAMROLE}" != "true" ]; then
#   # env vars needed for aws tools - only if an IAM role is not used

#   export AWS_ACCESS_KEY_ID=$S3_ACCESS_KEY_ID
#   export AWS_SECRET_ACCESS_KEY=$S3_SECRET_ACCESS_KEY
#   export AWS_DEFAULT_REGION=$S3_REGION
# fi

MYSQL_HOST_OPTS="-h $MYSQL_HOST -P $MYSQL_PORT -u$MYSQL_USER -p$MYSQL_PASSWORD"
DUMP_START_TIME=$(date +"%Y-%m-%dT%H%M%SZ")

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
  aws s3 ls s3://$S3_BUCKET/backup
  if [ $? != 0 ]; then
    >&2 echo "Error uploading ${DEST_FILE} on S3"
  fi
  
}

echo "Creating schema and dump for ${MYSQLDUMP_DATABASE} from ${MYSQL_HOST}..."

DUMP_FILE="/tmp/dump.sql.gz"
DUMP_SCHEMA_FILE="/tmp/schema.sql.gz"
mysqldump -h $MYSQL_HOST -P $MYSQL_PORT -u$MYSQL_USER -p$MYSQL_PASSWORD --skip-lock-tables --single-transaction --databases $MYSQLDUMP_DATABASE | gzip > $DUMP_FILE
mysqldump $MYSQL_HOST_OPTS --no-data --databases $MYSQLDUMP_DATABASE | gzip > $DUMP_SCHEMA_FILE
echo "Dumping successful"

if [ $? == 0 ]; then
  if [ "${S3_FILENAME}" == "**None**" ]; then
    S3_FILE="${DUMP_START_TIME}-data.sql.gz"
    copy_s3 $DUMP_FILE $S3_FILE
    echo "Dumping data successful"
    S3_FILE="${DUMP_START_TIME}-schema.sql.gz"
    copy_s3 $DUMP_SCHEMA_FILE $S3_FILE
    echo "Dumping schema successful"
  else
    S3_FILE="${S3_FILENAME}-dump.sql.gz"
    copy_s3 $DUMP_FILE $S3_FILE
    echo "Dumping data successful"
    S3_FILE="${DUMP_START_TIME}-schema.sql.gz"
    copy_s3 $DUMP_SCHEMA_FILE $S3_FILE
    echo "Dumping successful"
  fi
else
  >&2 echo "Error creating dump of all databases"
fi

echo "SQL backup finished"