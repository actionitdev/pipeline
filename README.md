# Solferino CI/CD Pipeline
A staging pipeline for Solferino Wordpress website.

## Purpose
This pipeline aims to containerize Solferino WordPress website with common features and settings that apply to other NS sites. Combined practices of docker and CI/CD are implemented. By using this pipeline, we are aiming to enforce automation in building, testing and deployment of our containerized version of wordpress application.

## Software/Tools Used
 1) [CircleCI](https://circleci.com/)
 2) [Traefik](https://doc.traefik.io/traefik/)
 3) [Wordpress Apache docker image](https://hub.docker.com/_/wordpress)

## Docker Services Architecture


![Image of Docker Services](https://github.com/actionitdev/pipeline/blob/docs/Docker%20Services%20Diagram.jpeg)

## CI/CD Workflow
Once any code changes were pushed to the `master` branch, CircleCI will be automatically triggered to build updates and conduct unit tests. Next, the code changes will be deployed to our staging server, where integration tests and performance tests run.

![Image of CI/CD](https://github.com/actionitdev/pipeline/blob/docs/CI-CD%20Pipeline%20Diagram.jpg)

## How to Run Docker Services

### Extra Essential Files
1. `.env` file
    `.env` file is necessary for mysql database service to run. Please add the following credentials variables in `.env` file.
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

