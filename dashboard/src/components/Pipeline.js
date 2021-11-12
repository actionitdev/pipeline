import React, { useEffect } from "react";
import {
  HiCheckCircle,
  HiDotsCircleHorizontal,
  HiExclamationCircle,
} from "react-icons/hi";

const Pipeline = ({
  build,
  workflow,
  setLastDeploy,
  setLastSync,
  latestDeployWorkflow,
  latestSyncWorkflow,
}) => {
  // Get the latest deployment and db sync result
  useEffect(() => {
    let status = [];
    let syncStatus = [];
    if (workflow.length > 0 && build[latestDeployWorkflow].length > 0) {
      status = build[latestDeployWorkflow].map((build) =>
        build.lifecycle !== "finished" ? build.lifecycle : build.outcome
      );
    }
    if (
      workflow.length > 0 &&
      build[latestSyncWorkflow] &&
      build[latestSyncWorkflow].length > 0
    ) {
      syncStatus = build[latestSyncWorkflow].map((build) =>
        build.lifecycle !== "finished" ? build.lifecycle : build.outcome
      );
    }
    setLastDeploy(!status.includes("failed"));
    setLastSync(syncStatus.includes("success"));
  }, [
    build,
    setLastDeploy,
    setLastSync,
    workflow,
    latestDeployWorkflow,
    latestSyncWorkflow,
  ]);

  // Function to get the overall workflow status from the related build results
  const getWorkflowStatus = (builds, index) => {
    let status = "success";
    let textStyle = "text-success";
    if (builds) {
      builds.forEach((item, index) => {
        if (item.lifecycle !== "finished") {
          status = item.lifecycle;
          textStyle = "text-primary";
          return;
        } else {
          if (item.outcome !== "success") {
            status = item.outcome;
            textStyle = "text-danger";
            return;
          }
        }
      });
    } else {
      status = "queued";
      textStyle = "pending";
    }
    return [status, textStyle];
  };

  // Function to get the workflow date
  const getWorkflowDate = (builds) => {
    if (builds) {
      return builds[0].usage_queued_at.substring(0, 10);
    } else {
      let today = new Date();
      return (
        today.getFullYear() +
        "-" +
        (today.getMonth() + 1) +
        "-" +
        today.getDate()
      );
    }
  };
  // Function to get the result of every single build
  const getBuildStatus = (build) => {
    if (build.lifecycle !== "finished") {
      return (
        <span className="pending">
          <HiDotsCircleHorizontal /> &nbsp;
          {build.workflows.job_name}
        </span>
      );
    } else {
      if (build.outcome !== "success") {
        return (
          <span className="fail">
            <HiExclamationCircle />
            &nbsp;
            {build.workflows.job_name}
          </span>
        );
      } else {
        return (
          <span>
            <HiCheckCircle />
            &nbsp;
            {build.workflows.job_name}
          </span>
        );
      }
    }
  };
  return (
    <>
      <h4>Deployment History </h4>
      <div className="list-group pipelines-section">
        {workflow.length < 1 ? (
          <div>
            <br />
            <h5>Loading...</h5>
          </div>
        ) : (
          workflow.map((item, index) => (
            <div className="list-group-item" key={index}>
              <div className="d-flex justify-content-between">
                <h5 className="mb-1">
                  {
                    <span className={getWorkflowStatus(build[item], index)[1]}>
                      {getWorkflowStatus(build[item], index)[0]}
                    </span>
                  }
                </h5>
                <small>{getWorkflowDate(build[item])}</small>
              </div>
              {build[item]
                ? build[item].map((buildItem, index) => (
                    <p className="mb-1" key={index}>
                      {getBuildStatus(buildItem)}
                    </p>
                  ))
                : null}
              <small>ID: {item}</small>
            </div>
          ))
        )}
      </div>
    </>
  );
};

export default Pipeline;
