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

export_aws_keys() {
    export AWS_ACCESS_KEY_ID=$S3_ACCESS_KEY_ID
    export AWS_SECRET_ACCESS_KEY=$S3_SECRET_ACCESS_KEY
    export AWS_DEFAULT_REGION=$S3_REGION
}

copy_to_s3() {
    sudo docker exec wordpress tar -zcvf wp-content-staging.tar.gz -C / var/www/html/wp-content > wp-content-staging-backup.tar.gz
    if [ $? == 0 ]; then
        echo "wp-content backup has been created!"
        aws s3 cp wp-content-staging-backup.tar.gz s3://actionit-staging/backup/staging/wp/"${DUMP_START_TIME}-wpcontent-backup.tar.gz"
        if [ $? == 0 ]; then
            echo "successfully backup the wordpress content!"
        fi
    fi
}

export_aws_keys
copy_to_s3
