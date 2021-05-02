# Solferino CI/CD pipeline:
A staging pipeline for Solferino Wordpress website

## Purpose :
This pipeline implements combined practices of continuous integration and continuous delivery. By using this pipeline we are aiming to integrate the code changes regularly and the system runs unit test cases and integration tests more frequently to identify and locate the errors more quickly and effectively.

## CI/CD Workflow: 
Once the code changes were pushed to the `master` branch, the CI services will be triggered which builds the changes and deploy to our staging server. The system runs the unit test cases, integration test cases, and also performance tools. This will also alert the team about the status of the test cases and the build results.

![Image of CI/CD](https://github.com/actionitdev/pipeline/blob/docs/CI-CD%20Pipeline%20Diagram.jpg)

## Software/Tools used:
 1) [CircleCI](https://circleci.com/)
 2) [Traefik](https://doc.traefik.io/traefik/)
 3) [Wordpress Apache docker image](https://hub.docker.com/_/wordpress)

## Important Notes:
Make modifications in the `docker-compose.yml` file as per the product requirement changes.
Continuous intergartion pipeline workflow can be managed by editing the `config.yml` file.

## Essential :
The following text files are necessary for wordpress and mysql containers to run:
1. `user.txt` refers to the username of mysql service
2. `pw.txt` refers to the user password of mysql service
3. `root.txt` refers to the root password of mysql service
4. `db.txt` refers to the database name for wordpress



 
