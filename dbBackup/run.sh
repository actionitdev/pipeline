#! /bin/sh

set -e

if [ "${S3_S3V4}" = "yes" ]; then
    aws configure set default.s3.signature_version s3v4
fi

if [ "${SCHEDULE_DB}" = "**None**" ]; then
  # sh backupDB.sh
  sh backupWP.sh
else
  exec go-cron "$SCHEDULE_DB" /bin/sh backupDB.sh
  # exec go-cron "$SCHEDULE_WP" /bin/sh backupWP.sh
fi