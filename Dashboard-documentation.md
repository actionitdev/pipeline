## Dashboard
- `dashboard` is the folder to store the react project of dashboard.

### Run the Dashboard
1. To run the project on localhost, first access the `dashboard` directory from root.
```console
$ cd dashboard
```
2. Install all the required `node_modules` packages by executing one of the following commands in the terminal:
```console
$ npm install 
```
or 

```console
$ yarn install 
```

3. Add a `.env` file under  `dashboard` directory to store the following environmnet variables:
  - __REACT_APP_CIRCLECI_TOKEN__
  - __REACT_APP_AWS_ACCESS_KEY_ID__
  - __REACT_APP_ACCESS_KEY__
  - __REACT_APP_REGION__
`REACT_APP_CIRCLECI_TOKEN` is your personal CircleCI API token. You can generate one in 'Personal API Tokens' section under your CircleCI user settings page.
`REACT_APP_AWS_ACCESS_KEY_ID`, `REACT_APP_ACCESS_KEY`, `REACT_APP_REGION` are related to Action IT's AWS account.

4. Run the project by one of the following commands:
```console
$ npm start
```
or 

```console
$ yarn start
```

### Dashboard User Interface
Currently, there are three main functions on the dashboard:
  - Synchronize data from production server to staging server.
  - Start a new deployment and update the status in the deployment history section.
  - List the latest 3 backup files of the staging server in AWS S3. When the latest deployment failed, user could choose one of the backups to restore.
  (ps: it will create two environment variables on CircleCI, still needs further implementation on script level.)
  - Show the deploymnet history.
