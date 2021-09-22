FROM golang:1.15.8-alpine3.12 AS binary
RUN apk -U add openssl git

ARG DOCKERIZE_VERSION=v0.6.1
WORKDIR /go/src/github.com/jwilder
RUN git clone https://github.com/jwilder/dockerize.git && cd dockerize && git checkout ${DOCKERIZE_VERSION}

WORKDIR /go/src/github.com/jwilder/dockerize
RUN go get github.com/robfig/glock
RUN glock sync -n < GLOCKFILE
RUN go install

FROM bentolor/docker-dind-awscli

ADD install.sh install.sh
RUN sh install.sh && rm install.sh

# # # ADD crontab /etc/cron.d/backup-cron

COPY --from=binary /go/bin/dockerize /usr/local/bin

ENV TIMEOUT "10s"
ENV MYSQLDUMP_OPTIONS --quote-names --quick --add-drop-table --add-locks --allow-keywords --disable-keys --extended-insert --single-transaction --create-options --comments --net_buffer_length=16384
ENV MYSQLDUMP_DATABASE wp
ENV MYSQL_HOST mysql
ENV MYSQL_PORT 3306
ENV MYSQL_USER **None**
ENV MYSQL_PASSWORD **None**
ENV S3_ACCESS_KEY_ID **None**
ENV S3_SECRET_ACCESS_KEY **None**
ENV S3_BUCKET **None**
ENV S3_REGION us-west-1
ENV S3_ENDPOINT **None**
ENV S3_S3V4 no
ENV S3_PREFIX 'backup'
ENV S3_FILENAME **None**
ENV SCHEDULE_DB **None**
# ENV SCHEDULE_WP **None**
ENV S3_IAMROLE false

COPY root /var/spool/cron/crontabs/root
ADD run.sh run.sh
ADD backupDB.sh backupDB.sh
# ADD backupWP.sh backupWP.sh
ADD script.sh /script.sh
RUN chmod +x /script.sh
# RUN chmod +x /backupWP.sh
RUN chmod +x /backupDB.sh
# RUN chmod 0644 /etc/cron.d/backup-cron
#configure your default profile
# RUN aws configure set region us-west-1

# # or in non-interactive environment
# # --configure <profile_name> <aws_access_key_id> <aws_secret_access_key> [<aws_session_token>]
# RUN aws --configure default S3_ACCESS_KEY_ID S3_SECRET_ACCESS_KEY
# # # RUN chmod 0644 /etc/cron.d/backup-cron
# RUN crontab /etc/cron.d/backup-cron
# Create the log file to be able to run tail
# # #RUN touch /var/log/cron.log
RUN chmod u+x run.sh
CMD dockerize -wait tcp://${MYSQL_HOST}:${MYSQL_PORT} -timeout ${TIMEOUT} /run.sh
#CMD ["sh", "run.sh"]
# CMD cron && tail -f /var/log/cron.log