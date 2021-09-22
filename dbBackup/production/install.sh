#! /bin/bash

# exit if a command fails
set -e


apk update
apk add tzdata bash gzip openssl mariadb-connector-c
# install mysqldump
apk add mysql-client

# # # RUN apt-get update && apt-get -y install cron

# install s3 tools
# apk add --update --no-cache curl py-pip3
# pip3 install awscli
# apk del py-pip

# install go-cron
# apk add curl
# curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
# unzip awscliv2.zip
# mkdir /usr/local/bin/aws
# ./aws/install -i /usr/local/aws-cli -b /usr/local/bin/aws
# curl -L --insecure https://github.com/odise/go-cron/releases/download/v0.0.6/go-cron-linux.gz | zcat > /usr/local/bin/go-cron
# chmod u+x /usr/local/bin/go-cron
# apk del curl


# cleanup
rm -rf /var/cache/apk/*