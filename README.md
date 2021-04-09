# Solferino CI/CD pipeline:
Create a staging pipeline for Solferino web-site

# Purpose :
This pipeline implements combined practices of continuous integration continuous delivery. By using this pipeline we are aiming to integrate the code changes regularly and the system runs unit test cases and integration tests more frequently to identify and locate the errors more quickly and effectively.

# Workflow: 
Once the code changes were pushed to the `master` branch, the CI services will be triggered which builds the changes and deploy to our staging server. The system runs the unit test cases, integration test cases, and also performance tools. This will also alert the team about the status of the test cases and the build results.

# Software/Tools used:
 1) Circle CI

# Important Notes:
Make changes in the `docker-compose.yml` file as per the product requirement changes.
Continuous intergartion pipeline work flow can be managed by editing the `config.yml` file.



 
