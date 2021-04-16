# Solferino CI/CD pipeline:
Create a staging pipeline for Solferino web-site

# Purpose :
This pipeline implements combined practices of continuous integration continuous delivery. By using this pipeline we are aiming to integrate the code changes regularly and the system runs unit test cases and integration tests more frequently to identify and locate the errors more quickly and effectively.

# Workflow: 
Once the code changes were pushed to the `master` branch, the CI services will be triggered which builds the changes and deploy to our staging server. The system runs the unit test cases, integration test cases, and also performance tools. This will also alert the team about the status of the test cases and the build results.

###### `Build starts only when changes are pushed to a particular branch. Now "master" is provided as a default branch.`
#####  `Please merge the changes to master branch to trigger pipeline workflow.`

# Software/Tools used:
 1) Circle CI

# Important Notes:
Make modifications in the `docker-compose.yml` file as per the product requirement changes.
Continuous intergartion pipeline workflow can be managed by editing the `config.yml` file.

# Essential Environment Variables for Services to run
The following environment variables should be specified in `.env` file in the root directory:
1. `mysql_user` refers to the username of mysql service
2. `mysql_pw` refers to the user password of mysql service
3. `mysql_root_pw` refers to the root password of mysql service
4. `mysql_db` refers to the database name used by wordpress service
