import { useState, useEffect } from "react";
import axios from "axios";
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
  } from "react-router-dom";
import Backup from "./components/Backup";

  
import Pipeline from "./components/Pipeline";
import NewDeploy from "./components/NewDeploy";

// The full api address is in setupProxy.js file
const getBuildApi =
  "/api/v1.1/project/github/actionitdev/pipeline?limit=20&shallow=true";
const postBuildApi = "/api/v2/project/github/actionitdev/pipeline/pipeline";
const createEnvApi = "api/v2/project/github/actionitdev/pipeline/envvar";
function Dashboard() {
  const [workflow, setWorkflow] = useState([]);
  const [build, setBuild] = useState({});
  const [message, setMessage] = useState("");
  const [requestCount, setRequestCount] = useState(0);
  const [lastDeploy, setLastDeploy] = useState();
  axios.defaults.headers.common["Circle-Token"] =
    process.env.REACT_APP_CIRCLECI_TOKEN;

  // Function to get circleCI build data
  const getData = () => {
    axios.get(getBuildApi).then((res) => {
      const data = res.data;
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
      if (count === 18) {
        clearInterval(inter);
      }
    }, 60000);
  };

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

  const handleClick = () => {
    setMessage("Deployment has started!");
    axios
      .post(postBuildApi, {
        branch: "dev",
        parameters: { "run_workflow_build-and-deploy": true },
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
    
  



      <div className="container">
        <div className="row">
          <div className="col-md-6 left">
            <NewDeploy handleClick={handleClick} message={message} />
            <Backup setEnvVariable={setEnvVariable} lastDeploy={lastDeploy} />
          </div>
          <div className="col-md-6">
            <Pipeline
              build={build}
              workflow={workflow}
              setLastDeploy={setLastDeploy}
            />
          </div>
        </div>
      </div>
     
   
  );
}

export default Dashboard;