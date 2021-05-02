# Solferino CI/CD pipeline:
A staging pipeline for Solferino Wordpress website

## Purpose :
This pipeline aims to containerize Solferino WordPress website with common features and settings that apply to other NS sites. Combined practices of docker and CI/CD are implemented. By using this pipeline, we are aiming to enforce automation in building, testing and deployment of our containerized version of wordpress application.

## Software/Tools used:
 1) [CircleCI](https://circleci.com/)
 2) [Traefik](https://doc.traefik.io/traefik/)
 3) [Wordpress Apache docker image](https://hub.docker.com/_/wordpress)

## CI/CD Workflow: 
Once any code changes were pushed to the `master` branch, CircleCI will be automatically triggered to build updates and conduct unit tests. Next, the code changes will be deployed to our staging server, where integration tests and performance tests run.

![Image of CI/CD](https://github.com/actionitdev/pipeline/blob/docs/CI-CD%20Pipeline%20Diagram.jpg)

## How to run docker services:
The following text files are necessary for wordpress and mysql containers to run:
1. `user.txt` refers to the username of mysql service
2. `pw.txt` refers to the user password of mysql service
3. `root.txt` refers to the root password of mysql service
4. `db.txt` refers to the database name for wordpress



 
