import { useState, useEffect } from "react";
import Pipeline from "./components/Pipeline";
import NewDeploy from "./components/NewDeploy";
import Backup from "./components/Backup";
import Navbar from "react-bootstrap/Navbar";
import Nav from "react-bootstrap/Nav";
import Container from "react-bootstrap/Container";
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
  // The total count of sending the get request
  const [requestCount, setRequestCount] = useState(0);
  // The latest deployment workflow id
  const [latestDeployWorkflow, setLatestDeployWorkflow] = useState();
  // If latest deployment is successful: true, if failed: false
  // It determines the backup section is accessable or not
  const [lastSuccessDeploy, setLastSuccessDeploy] = useState();
  axios.defaults.headers.common["Circle-Token"] =
    process.env.REACT_APP_CIRCLECI_TOKEN;

  // Function to get circleCI history build data
  const getData = () => {
    axios.get(getBuildApi).then((res) => {
      const data = res.data;
      const firstDeployWorkflow = data.find(
        (build) => build.workflows.workflow_name === "build-and-deploy"
      ).workflows.workflow_id;
      setLatestDeployWorkflow(firstDeployWorkflow);
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

  // Function to add environment variable to CircleCi
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
    setSyncMessage("Synchronization has started!");
    axios
      .post(postBuildApi, {
        branch: "dev",
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
    setMessage("Deployment has started!");
    axios
      .post(postBuildApi, {
        branch: "dev",
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
      <Navbar bg="dark" variant="dark" className="nav">
        <Container>
          <Navbar.Brand href="./">Solferino Academy Dashboard</Navbar.Brand>
          <Nav className="me-auto">
            <Nav.Link href="https://staging-sa.actionit.dev/" target="_blank">
              Staging Site
            </Nav.Link>
            <Nav.Link
              href="https://production-sa.actionit.dev/"
              target="_blank"
            >
              Production Site
            </Nav.Link>
          </Nav>
        </Container>
      </Navbar>
      <div className="container">
        <div className="row">
          <div className="col-md-6 left">
            <NewDeploy
              handleClick={handleClick}
              message={message}
              syncMessage={syncMessage}
              handleSyncClick={handleSyncClick}
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
              latestDeployWorkflow={latestDeployWorkflow}
            />
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;
