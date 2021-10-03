import React from "react";
import Button from "react-bootstrap/button";

const NewDeploy = ({ handleClick, message }) => {
  return (
    <>
      <div className="deploy">
        <Button variant="dark" onClick={handleClick} className="deploybtn">
          Start a New Deployment
        </Button>
        <p className="text-primary">{message}</p>
      </div>
    </>
  );
};

export default NewDeploy;
