import React, { useState, useEffect } from "react";
import Button from "react-bootstrap/button";
import AWS from "aws-sdk";

const Backup = ({ setEnvVariable, lastDeploy }) => {
  const [db, setDb] = useState([]);
  const [wp, setWp] = useState([]);
  const [select, setSelect] = useState();
  AWS.config.update({
    accessKeyId: process.env.REACT_APP_AWS_ACCESS_KEY_ID,
    secretAccessKey: process.env.REACT_APP_ACCESS_KEY,
    region: process.env.REACT_APP_REGION,
  });
  const dbparams = {
    Bucket: "actionit-staging",
    Delimiter: "",
    Prefix: "backup/staging/db/",
  };
  const wpparams = {
    Bucket: "actionit-staging",
    Delimiter: "",
    Prefix: "backup/staging/wp/",
  };
  const s3 = new AWS.S3();
  useEffect(() => {
    s3.listObjectsV2(dbparams, (err, data) => {
      if (err) {
        console.log(err, err.stack);
      } else {
        const contents = data.Contents;
        const dbkeys = contents.map((item) => item.Key).slice(-3);
        setDb(dbkeys);
      }
    });
    s3.listObjectsV2(wpparams, (err, data) => {
      if (err) {
        console.log(err, err.stack);
      } else {
        const contents = data.Contents;
        const wpkeys = contents.map((item) => item.Key).slice(-3);
        setWp(wpkeys);
      }
    });
  }, []);

  const onClick = (index) => {
    setSelect(index);
    const backupDb = db[index];
    const backupWp = wp[index];
    setEnvVariable(backupDb, backupWp);
  };

  return (
    <div className="backup">
      <h4 className={lastDeploy ? "text-secondary" : ""}>Previous Backup </h4>
      {db.length < 1 || wp.length < 1 ? (
        <div>
          <br />
          <h5>Loading...</h5>
        </div>
      ) : (
        <div className="list-group backup-section">
          {db.map((item, index) => (
            <div className="list-group-item" key={index}>
              {item && wp[index] ? (
                <>
                  <div className="d-flex justify-content-between">
                    <div
                      className={`backups ${
                        lastDeploy ? "text-secondary" : ""
                      }`}
                    >
                      <p className="mb-1">{item.substr(18, 10)}</p>
                      <p className="mb-1">{item.substr(37)}</p>
                      <p className="mb-1">{wp[index].substr(37)}</p>
                    </div>
                    <div className="button-section">
                      <Button
                        variant={select === index ? "dark" : "outline-dark"}
                        size="sm"
                        onClick={() => onClick(index)}
                        className="backupbtn"
                        disabled={lastDeploy}
                      >
                        Select
                      </Button>
                      <div
                        style={{
                          visibility: select === index ? "visible" : "hidden",
                        }}
                      >
                        <small className="text-primary"> Backup Selected</small>
                      </div>
                    </div>
                  </div>
                </>
              ) : null}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Backup;
