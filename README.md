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


![Image of Docker Services](https://github.com/actionitdev/pipeline/blob/docs/Docker%20Services%20Diagram.jpeg)

## Project File Structure
- `docker-compose.yml`
    `docker-compose.yml` defines the services to run in the docker container. According to [Docker Services Architecture](#docker-services-architecture), three services are included:
    - `traefik`: Serves as the reverse proxy, based on traefik official image v2.4
    - `mysql`: Database service, based on latest mysql official image
    - `wordpress`: Wordpress web application service, based on latest wordpress official image (Apache included) 
    - `dbBackup`: Customised container in charge of data and schema backup

- `.circleci`
    `.circleci` is the folder to store CircleCI configuration files. 
    - `config.yml`: Specifies commands to execute in CI and criteria to decide whether tests are passed or failed.

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
    
    Example `.env` file:
    ```
    mysql_db=db
    mysql_user=user
    mysql_pw=userpw
    mysql_root_pw=rootpw
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
