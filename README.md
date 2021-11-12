# Solferino CI/CD Pipeline
A staging pipeline for Solferino Wordpress website.

## Purpose
This CI/CD pipeline aims to containerize Solferino WordPress website with common features and settings that apply to other NS sites. Combined practices of docker and CI/CD are implemented. By using this pipeline, we are aiming to enforce automation in building, testing and deployment of our containerized version of wordpress application on both staging and development servers.

## Table of Contents

- [Software/Tools Used](#softwaretools-used)
- [Project File Structure](#project-file-structure)
- [Staging Server's Docker Services Architecture](#staging-server-docker-services-architecture)
  - [Traefik](#traefik)
  - [Wordpress](#wordpress)
  - [MySQL](#mysql)
- [Production Server's Docker Services Architecture](#production-server-docker-services-architecture)
  - [dbBackup](#dbbackup)
- [CI/CD Workflow](#cicd-workflow)
  - [Essential Environment Variables in CI](#essential-environment-variables-in-circleci)
  - [Detailed CI/CD Process](#detailed-cicd-process)
- [Production and Staging Server Configuration](#staging-and-production-server-base-configuration)
  - [Prerequisites](#prerequisities)
  - [Extra Files for Docker Services to Run](#extra-files-for-docker-services-to-run)
  - [Start Docker Services](#start-docker-services)
  - [Access Running Wordpress Website](#access-running-wordpress-website)
- [Staging Server's Data Backup](#staging-server-data-backup)
    - [Usage](#usage)
- [Production Server's Data Backup](#production-server-data-backup)
  - [Acknowledgement](#acknowledgement)
  - [Usage](#usage)
  - [Periodic Backups](#periodic-backups)
  - [S3 Bucket Configuration](#s3-bucket-configuration)

## Software/Tools Used
 1) [CircleCI](https://circleci.com/)
 2) [Traefik](https://doc.traefik.io/traefik/)
 3) [Docker](https://docs.docker.com/)
 3) [Wordpress Apache docker image](https://hub.docker.com/_/wordpress)

## Project File Structure
- `staging-docker-compose.yml`

    `staging-docker-compose.yml` defines containers to be run inside the staging server. According to [Staging Server Docker Services Architecture](#staging-server-docker-services-architecture), three services are included:
    - `traefik`: Serves as the reverse proxy, based on traefik official image v2.4.
    - `mysql`: Database service, based on latest mysql official image.
    - `wordpress`: Wordpress web application service, based on Wordpress 5.7.1 official image (Apache included).

- `production-docker-compose.yml`

    `production-docker-compose.yml` defines containers to be run inside the staging server. According to [Production Server Docker Services Architecture](#production-server-docker-services-architecture), four services are included:
    - `traefik`: Serves as the reverse proxy, based on traefik official image v2.4.
    - `mysql`: Database service, based on latest mysql official image.
    - `wordpress`: Wordpress web application service, based on Wordpress 5.7.1 official image (Apache included). 
    - `dbbackup`: Database backup container to periodically backup database from the production server.

- `.circleci`

    `.circleci` is the folder to store CircleCI configuration files. 
    - `config.yml`: Specifies commands to execute in CI and criteria to decide whether build tests and deployment process are passed or failed

- `.init`

    `.init` is the folder to store mysql database initialization files. There will be frequent changes inside this folder, happening during the deployment or synchronization process between production and staging server.
    - `dumpfile.sql.gz`: Initial DB file that specifies sql commands, to initialize the tables and data inside mysql container.

- `dbBackup`

    `dbBackup` is the folder to store configuration and essential scripts for staging and production's server backup There are two sub-folders:
    - production
        - `Dockerfile`: Defines the instructions to build data backup container. It consists of several scripts like backupDB.sh, install.sh, and run.sh to be run inside the backup container.
        - `backupDB.sh`: Script of the commands to make dump file from MySQL server and make backup in AWS S3 bucket, under /production folder. The dump files consist of user data and also the schema of the whole database. 
        - `install.sh`: A script defines the packages to be installed in the image.
        - `run.sh`: A script defines when the command to run when the docker image is built.
        - `root`: Define several cron-jobs for scheduled backup inside the production container.
    - staging
        - `backupStagingDB.sh`: Database backup script that is run everytime there is a deployment from the staging to the production server (run by config.yml). The backup file itself is stored inside the AWS S3 bucket, under /staging/db folder.
        - `backupStagingWP.sh`: Wordpress backup script that is run alongside `backupStagingDB.sh`. The backup wp-content file itself is stored under /staging/wp folder under AWS S3 bucket.

- `.gitignore`

    `.gitignore` is a file that contains any file names by the git track.

## Staging Server Docker Services Architecture
The following four containers will be running together:

### Traefik
Traefik acts as a reverse proxy. When an HTTP request comes in, traefik will intercept the request and forward it to the wordpress service for processing.

### Wordpress
Wordpress is the main application service. It processes the HTTP request forwarded by traefik. Also, WP-Content folder is mounted to the wordpress container for themes, plugins and user uploads.

### MySQL
MySQL service is for data storage. It stores data which support for wordpress service. Meanwhile, the container mounts every SQL file that is put inside the .init folder.

![Image of Staging Server's Workflow](./staging-architecture.jpeg)


## Production Server Docker Services Architecture
Similar containers as Staging server are running inside the production server. However, there is an additional service running alongside primary containers:

### dbBackup
DbBackup service is for the data backup. It backs up the data dumped from MySQL service in the production server. Technically, it periodically backs up MySQL database via SQLdump and transfers it to the Amazon S3.

![Image of Production Server's Workflow](./production-architecture.jpeg)

## CI/CD Workflow
Once any code changes were pushed to the `dev` branch, CircleCI will be automatically triggered to build updates and conduct unit tests. Next, the code changes will be deployed to our staging server, where integration tests and performance tests run. 
On the other hand, There are two procedures that can be done from the staging to the production server. First, any changes pushed to the `master` branch will trigger the automatic deployment pipeline to the production server. Meanwhile, the production server's production pipeline and content synchronization between the staging and production environment can also be triggered by utilising the interactive dashboard.

![Image of CI/CD](./CICD-workflow.jpeg)

### Essential Environment Variables in CircleCI

- `GITHUB_TOKEN` (github access token for operations on the github repository, eg. create new pull requests)

- `MYSQL_PROD_PASSWORD` (mysql database's password for the production server)

- `MYSQL_PROD_DATABASE` (mysql database's database name for the production server)

- `MYSQL_PROD_USER` (mysql database's user name for the production server)

- `MYSQL_STAGING_PASSWORD` (mysql database's password for the staging server)

- `MYSQL_STAGING_DATABASE` (mysql database's database name for the staging server)

- `MYSQL_STAGING_PORT` (mysql database's port for the staging server)

- `MYSQL_STAGING_USER` (mysql database's user name for the staging server)

- `S3_ACCESS_KEY_ID` (access key ID for the S3 bucket)

- `S3_REGION` (region name for the S3 bucket)

- `S3_SECRET_ACCESS_KEY` (access key secret for the S3 bucket)

- `SSH_USER` (staging server user for ssh access)

- `SSH_HOST` (staging server host for ssh access)

- `SSH_PROD_HOST` (production server host for ssh access)

- `mysql_db` (database name for mysql docker service)

- `mysql_user` (username for mysql docker service)

- `mysql_pw` (user password for mysql docker service)

- `mysql_root_pw` (root password for mysql docker service)

- `id` (aws user id for backup service)

- `secret` (aws user secret for backup service)

### Detailed CI/CD Process

For the whole CI/CD workflow, we have five jobs set up in CircleCI:

1. __staging-build__: 

    This job mainly tests whether the docker services can be built and running properly prior to the deployment process to the staging server.

    - __checkout__: This step checks out the code from the github repository for CircleCI to use.

    - __Test staging-docker-compose build__: This step checks whether the docker services can be built and running successfully.

    - __Test database connection__: This step checks whether the mysql database service is running properly and can be accessed.

    - __gh/setup__: This step sets up environment for github cli commands.

2. __staging-deploy__: 

    This job mainly focuses on the deployment of latest code changes inside the `dev` branch. It depends on the previous `build` job. The steps of `deploy` job are as followed:

    - __add_ssh_keys__: This step prepares the ssh access to the staging server.

    - __Deploy to lightsail staging server__: In this step, firstly, access the staging server via ssh. Then, pull the latest code updates from GitHub repository. Finally, update docker services.

3. __production-build__: 

    This job mainly tests whether the docker services can be built and running properly prior to the deployment process to the production server.

    - __checkout__: This step checks out the code from the github repository for CircleCI to use.

    - __Test production-docker-compose build__: This step checks whether the docker services can be built and running successfully.

4. __production-deploy__: 

    This job mainly focuses on the deployment of latest code changes inside the `master` branch. It depends on the previous `build` job. The steps of `deploy` job are as followed:

    - __add_ssh_keys__: This step prepares the ssh access to the production server.

    - __Backup the database in the staging server__: This step allows the staging's DB backup script to be run, and latest changes of the staging server's database will be recorded in the S3 bucket

    - __Backup the WP-content in the staging server__: This step allows the staging's wp-content backup script to be run, and latest changes of the staging server's wordpress content will be recorded in the S3 bucket

    - __Migrate sql and wp-content from staging to the production server__: This step transfers latest contents and database from the staging to the production for the production's deployment purpose

    - __Deploy to lightsail production server__: In this step, firstly, access the staging server via ssh. Then, pull the latest code updates from GitHub repository. Finally, update docker services.

5. __sync-prod-staging__ (Under development):

    This job mainly focuses on the synchronization process between the production and the staging server. The workflow itself is not triggered manually; instead, it is triggered upon client request by using the Solferino Interactive Dashboard. In general, this workflow will be triggered first to synchronize any changes between the staging and production servers (latest comments, posts from the production will be pulled into the staging to avoid development conflicts), before clients make any changes to the staging server.

    - __Turn off the staging server for DB synchronization__: This step turns off all staging server's services before the synchronization process happens.

    - __Synchronize contents from production to the staging server__: This step transfers all database and wp-contents from the production server to the staging server.

    - __Update content in the staging server__: This step remove old contents from the staging server, replacing them with the latest contents obtained from the production server.

    - __Turn on the staging server after synchronization__: This final step restarts all the services in the staging server, and lets those services to mount the updated contents. 


## Staging and Production Server Base Configuration

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

3. Firewall settings

    Please ensure the following TCP ports are open to the Internet:

    - Port 22 (for ssh access)

    - Port 80 (for http access)

    - Port 443 (for https access)

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
$ docker-compose -f [your_desired_docker_compose_file] up -d
```

### Stop Docker Services
To stop docker services and run wordpress website, simply execute following commands in the command line:
```console
$ docker-compose -f [your_desired_docker_compose_file] down
```

### Navigate through a docker service
to access one of the docker service, simply execute the following command:
 ```console
$ docker exec -it [docker_service_name] bash
```

### Access Running Wordpress Website
After docker services are started successfully, you can access the wordpress website via (https://staging-sa.actionit.dev) for the staging server and (https://production-sa.actionit.dev/) for the production server. Meanwhile, the staging server's wordpress admin console can be accessed via (https://staging-sa.actionit.dev/wp-admin)

## Staging Server Data Backup

### Usage
The backup process is required in the staging server in order to save the content updates. If there are failures happened in the staging server, then the server can be restored by using the previously saved contents. Meanwhile, the backup process happens everytime there is a deployment from the staging to the production server, and the backup process itself includes saving both updated wp-content and mysql database to the S3 bucket (under prefix /staging).

The environment variables (included in the circleCI):
- `S3_ACCESS_KEY_ID` *required*
- `$S3_SECRET_ACCESS_KEY` *required*
- `$S3_REGION` *required*
- `$MYSQL_STAGING_PORT` (default: 3306)
- `$MYSQL_STAGING_USER` (default: 'wpadmin')
- `$MYSQL_STAGING_PASSWORD` *required*
- `$MYSQL_STAGING_DATABASE` (default: 'wp')


## Production Server Data Backup

### Acknowledgement
The files to build the backup container has used part code from (https://github.com/schickling/dockerfiles) and (https://github.com/fradelg/docker-mysql-cron-backup).

### Usage
The function of the backup container is making the backup of data and schema from mysql database and store it in AWS S3 bucket.
The backup process is achieved by using the `mysqldump` commond of MYSQL. There are two back up process, one is for all the user data from the database. By using this one, developer can restore the whole website. The other one is the schema file which does not consist user data. By using this one, developer can restore an empty website. Meanwhile, the production server periodically backups its contents on a weekly basis (every Sunday, 00:00 UTC).

The environment variables:
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
In this project, five-variable cron job format is used, For example, `SCHEDULE=10 * * * *` means to make the schedule and data backup every 10 mins. More information can be found in cron document (https://pkg.go.dev/github.com/robfig/cron)

### S3 Bucket Configuration
#### S3 Lifecycle 
(https://docs.aws.amazon.com/AmazonS3/latest/userguide/how-to-set-lifecycle-configuration-intro.html)
- In bucket list, we can select the bucket we are using for backup, then select **management** tab and chose Create lifecycle rule. The configuration now is apply to all the objects in the bucket. 
- There are several options in the lifecycle rule. The configuration now is to Permanently delete previous versions of objects in a 90 days cycle
- S3 bucket policy
	If we need to modify which user can access the bucket, we need to set the Bucket policy which is in the **Permissions** tab.
	<br>The policy now allows the user that created for the website application to write to the bucket. Also, the Bucket and objects are not public.

<!-- ### Backup container selection -->