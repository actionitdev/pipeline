import { useState, useEffect } from "react";
import Pipeline from "./components/Pipeline";
import NewDeploy from "./components/NewDeploy";
import Backup from "./components/Backup";
import axios from "axios";
import "./App.css";

// The full api address is in setupProxy.js file
const getBuildApi =
  "/api/v1.1/project/github/actionitdev/pipeline?limit=20&shallow=true";
const postBuildApi = "/api/v2/project/github/actionitdev/pipeline/pipeline";
const createEnvApi = "api/v2/project/github/actionitdev/pipeline/envvar";
function App() {
  // Previous workflow id
  const [workflow, setWorkflow] = useState([]);
  // Previous build records
  const [build, setBuild] = useState({});
  // Reminding message for 'start new deployment' button
  const [message, setMessage] = useState("");
  //  Reminding message for 'synchronize database' button
  const [syncMessage, setSyncMessage] = useState("");
  // The total count of sending the get request after clicking the button
  const [requestCount, setRequestCount] = useState(0);
  // The latest deployment workflow id
  const [latestDeployWorkflow, setLatestDeployWorkflow] = useState();
  // The latest db synchronize workflow id
  const [latestSyncWorkflow, setLatestSyncWorkflow] = useState();
  // If latest deployment is successful: true, if failed: false
  // It determines the backup section is accessable or not
  const [lastSuccessDeploy, setLastSuccessDeploy] = useState();
  // If latest db sync is successful: true, if failed: false
  // Only when db sync is successful will enable the deployment button
  const [lastSuccessSync, setLastSuccessSync] = useState(false);
  // start the db sync process
  const [startSync, setStartSync] = useState(false);
  axios.defaults.headers.common["Circle-Token"] =
    process.env.REACT_APP_CIRCLECI_TOKEN;

  // Function to get circleCI build data
  const getData = () => {
    axios.get(getBuildApi).then((res) => {
      const data = res.data;
      const firstDeployWorkflow = data.find(
        (build) => build.workflows.workflow_name === "build-and-deploy"
      ).workflows.workflow_id;
      setLatestDeployWorkflow(firstDeployWorkflow);
      if (startSync) {
        const firstSyncWorkflow = data.find(
          (build) => build.workflows.workflow_name === "db-synchronize"
        ).workflows.workflow_id;
        setLatestSyncWorkflow(firstSyncWorkflow);
      }
      const buildData = data.reduce((groupedBuild, build) => {
        const workflowId = build.workflows.workflow_id;
        if (groupedBuild[workflowId] == null) {
          groupedBuild[workflowId] = [];
        }
        groupedBuild[workflowId].push(build);
        return groupedBuild;
      }, {});
      setBuild(buildData);
      setWorkflow(Object.keys(buildData));
    });
  };
  useEffect(() => {
    getData();
  }, [requestCount]);

  // Function to retrieve updated data regularly
  const updateData = () => {
    let count = 0;
    let shortUpdate = 0;
    let shortInter = setInterval(() => {
      shortUpdate += 1;
      getData();
      if (shortUpdate === 4) {
        clearInterval(shortInter);
      }
    }, 5000);
    let inter = setInterval(() => {
      count += 1;
      getData();
      if (count === 15) {
        clearInterval(inter);
      }
    }, 60000);
  };

  // Function to create environment variables on CircleCI
  const setEnvVariable = (db, wp) => {
    axios.post(createEnvApi, {
      name: "backupDb",
      value: db,
    });
    axios.post(createEnvApi, {
      name: "backupWp",
      value: wp,
    });
  };

  // Function to trigger 'build-and-deploy' workflow after clicking the 'new depoloyment' button
  const handleClick = () => {
    setMessage("Deployment has started!");
    axios
      .post(postBuildApi, {
        branch: "master",
        parameters: {
          "run_workflow_build-and-deploy": true,
          "run_workflow_db-synchronize": false,
        },
      })
      .then(() => {
        setRequestCount(requestCount + 1);
        updateData();
      })
      .catch((err) => {
        setMessage(`error: ${err}`);
      });
  };

  // Function to trigger 'db-synchronize' workflow after clicking the 'synchronize database' button
  const handleSyncClick = () => {
    setSyncMessage("Synchronization has started!");
    setStartSync(true);
    axios
      .post(postBuildApi, {
        branch: "master",
        parameters: {
          "run_workflow_db-synchronize": true,
          "run_workflow_build-and-deploy": false,
        },
      })
      .then(() => {
        setRequestCount(requestCount + 1);
        updateData();
      })
      .catch((err) => {
        setMessage(`error: ${err}`);
      });
  };
  return (
    <div className="App">
      <div className="container">
        <div className="row">
          <div className="col-md-6 left">
            <NewDeploy
              handleClick={handleClick}
              message={message}
              syncMessage={syncMessage}
              handleSyncClick={handleSyncClick}
              lastSuccessSync={lastSuccessSync}
            />
            <Backup
              setEnvVariable={setEnvVariable}
              lastDeploy={lastSuccessDeploy}
            />
          </div>
          <div className="col-md-6">
            <Pipeline
              build={build}
              workflow={workflow}
              setLastDeploy={setLastSuccessDeploy}
              setLastSync={setLastSuccessSync}
              latestDeployWorkflow={latestDeployWorkflow}
              latestSyncWorkflow={latestSyncWorkflow}
            />
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;
