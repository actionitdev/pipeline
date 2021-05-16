#! /bin/sh

set -e

if [ "${S3_S3V4}" = "yes" ]; then
    aws configure set default.s3.signature_version s3v4
fi

if [ "${SCHEDULE_DB}" = "**None**" ]; then
  # sh backupdb.sh
  sh backupwp.sh
else
  #exec go-cron "$SCHEDULE_DB" /bin/sh backupdb.sh & "$SCHEDULE_WP" /bin/sh backupwp.sh
  exec go-cron "$SCHEDULE_WP" /bin/sh backupwp.sh
fi