# Solferino CI/CD Pipeline
A staging pipeline for Solferino Wordpress website.

## Purpose
This pipeline aims to containerize Solferino WordPress website with common features and settings that apply to other NS sites. Combined practices of docker and CI/CD are implemented. By using this pipeline, we are aiming to enforce automation in building, testing and deployment of our containerized version of wordpress application.

## Software/Tools Used
 1) [CircleCI](https://circleci.com/)
 2) [Traefik](https://doc.traefik.io/traefik/)
 3) [Wordpress Apache docker image](https://hub.docker.com/_/wordpress)

## CI/CD Workflow
Once any code changes were pushed to the `master` branch, CircleCI will be automatically triggered to build updates and conduct unit tests. Next, the code changes will be deployed to our staging server, where integration tests and performance tests run.

![Image of CI/CD](https://github.com/actionitdev/pipeline/blob/docs/CI-CD%20Pipeline%20Diagram.jpg)

## Docker Services Architecture
The following four containers will be running together:
- Traefik: When a HTTP request comes in, traefik will intercept the request and forward it to the wordpress service for processing.
- Wordpress: Wordpress is the main application service. It processes the HTTP request forwarded by traefik. Also, WP-Content folder is mounted to the wordpress container for themes, plugins and user uploads.
- MySQL: MySQL service is for data storage. It stores data which support for wordpress service. 
- dbBackup: DbBackup service is for data backup. It backs up the data dumped from MySQL service and restores data back to MySQL database when necessary. Basically, it periodically backs up data from both MySQL and WP-Content to Amazon S3.

![Image of Docker Services](https://github.com/actionitdev/pipeline/blob/docs/Docker%20Services%20Diagram.jpeg)

## Project File Structure
- `docker-compose.yml`

    `docker-compose.yml` defines the services to run in the docker container. According to [Docker Services Architecture](#docker-services-architecture), three services are included:
    - `traefik`: Serves as the reverse proxy, based on traefik official image v2.4
    - `mysql`: Database service, based on latest mysql official image
    - `wordpress`: Wordpress web application service, based on latest wordpress official image (Apache included) 
    - `dbBackup`: Customized container in charge of data and schema backup

- `.circleci`

    `.circleci` is the folder to store CircleCI configuration files. 
    - `config.yml`: Specifies commands to execute in CI and criteria to decide whether tests are passed or failed

- `.init`

    `.init` is the folder to store mysql database initialization files.
    - `dumpfile.sql.gz`: Specifies sql commands to initialize the data inside mysql container

- `traefik.yml`

    `traefik.yml` defines the configuration of the reverse proxy and ensures auto-detection of other docker services available.

- `dbBackup`

    `dbBackup` is the folder to store configuration and essential scripts of customized data backup container.
    - `Dockerfile`: Defines the instructions to build data backup container
    - `backup.sh`: Script of the commands to make dump file from sql server and make backup in AWS S3 bucket. The dump files consist of user data and also the schema of the whole database. This container allows developer to build the sql database from a blank database or with existing data.
    - `install.sh`: A script defines the packages to be installed in the image.
    - `run.sh`: A script defines when the command to run when the docker image is built.

## How to Run Docker Services

### Prerequisities
1. docker

    Please ensure docker is installed and running properly. Or [Install docker](https://docs.docker.com/get-docker/)
    
    Check docker installation: 
    ```console
    $ docker -v
    ```
2. docker-compose
    Please ensure docker-compose is installed. Or [Install docker-compose](https://docs.docker.com/compose/install/)

    Check docker-compose installation: 
    ```console
    $ docker-compose -v
    ```

### Extra Files for Docker Services to Run
1. `.env` file

    `.env` file is mandatory for mysql database service to run. Please add the following credentials variables in `.env` file. Then, put `.env` file in the root directory of this project.

        - `mysql_db` (database name)

        - `mysql_user` (username)

        - `mysql_pw` (user password)

        - `mysql_root_pw` (root password)

        - `id` (aws user id)

        - `secret` (aws user secret)
    
    Example `.env` file:
    ```
    mysql_db=db
    mysql_user=user
    mysql_pw=userpw
    mysql_root_pw=rootpw
    id=someid
    secret=TheSecretOfTheUser
    ```
2. `wp-content` folder

    `wp-content` folder contains themes, plugins and user uploads used by the wordpress application. You can enable your own wordpress themes, plugins and user uploads by putting your `wp-content` folder in the root directory of this project. 

### Start Docker Services
To start docker services and run wordpress website, simply execute following commands in the command line:
```console
$ docker-compose up -d
```

### Access Wordpress Website Locally
After docker services are started successfully, you can access the wordpress website via `http://localhost:8080` and access wordpress admin console via `http:localhost:8080/wp-admin`.

## Backup
### Acknowledge
The files to build the backup container has used part code from (https://github.com/schickling/dockerfiles) and (https://github.com/fradelg/docker-mysql-cron-backup)
### Usage
The funciton of the backup container is making the backup of data and schema from mysql database and store it in AWS S3 bucket.
The back up is achieved by using the `mysqldump` commond of MYSQL.

The envrionment variables:
- `MYSQLDUMP_OPTIONS` mysqldump options (default: --skip-lock-tables --single-transaction)
- `MYSQLDUMP_DATABASE` list of databases you want to backup (default: --all-databases)
- `MYSQL_HOST` the mysql host *required*
- `MYSQL_PORT` the mysql port (default: 3306)
- `MYSQL_USER` the mysql user *required*
- `MYSQL_PASSWORD` the mysql password *required*
- `S3_ACCESS_KEY_ID` your AWS access key *required*
- `S3_SECRET_ACCESS_KEY` your AWS secret key *required*
- `S3_BUCKET` your AWS S3 bucket path *required*
- `S3_PREFIX` path prefix in your bucket (default: 'backup')
- `S3_FILENAME` a consistent filename to overwrite with your backup.  If not set will use a timestamp.
- `S3_REGION` the AWS S3 bucket region (default: us-eest-2)
- `MULTI_FILES` Allow to have one file per database if set `yes` (default: no)
- `SCHEDULE` backup schedule time, see explainatons below

### Periodic Backups
To change the backup frequency, you can modify the `SCHEDULE` environment variable using cron job format.
Cron job use five or six variables format, For example, `SCHEDULE=10 * * * * *` means to make the schedule and data backup every 10 mins or `SCHEDULE=0 0 * * * *` means to make the backup once an hour, at the beginning of hour. More information can be found in cron document (https://pkg.go.dev/github.com/robfig/cron)