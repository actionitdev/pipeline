#! /bin/bash

set -e

#exec go-cron "$SCHEDULE_DB" /bin/sh backupDB.sh & "$SCHEDULE_WP" /bin/sh backupWP.sh
# exec cron && tail -f /var/log/cron.log
# crontab /etc/cron.d/backup-cron
# crond && tail -f /var/log/cron.log
crond -f
