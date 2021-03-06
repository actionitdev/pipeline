import React, {useState } from "react";
import style from "./Accessibility.module.css";
import Button from "react-bootstrap/button";
import { runPa11y } from "./pa11y_script";
import CallApi from "../CallApi";

const Accessibility = () => {

    const [url, updateURL] = useState("");
    const [assessmentResults, updateResultSection] = useState("");

    // Function for triggering the accessibility assessment.
    function perform_assessment() {
        updateResultSection(`<div style="text-align:center; font-size:2rem; margin-top: 12rem">Loading ...</div>`);
        CallApi.runAccessibiliyTest({"url":url})
        .then(response=> {
            updateResultSection(response.data)
        })

        // var xhr = new XMLHttpRequest();
        // xhr.withCredentials = true;

        // xhr.addEventListener("readystatechange", function() {
        // if(this.readyState === 4) {
        //     console.log(this.responseText);
        //     updateResultSection(this.responseText);
        // }
        // });
        // const x = xhr.open("POST", "http://localhost:5000/accessibility");
        // xhr.send();
    }

return (<>
    <div className={style["main-div"]}>
        <div className={style["navbar-div"]}>
            <div><h4>Accessibility Assessment</h4></div>
            <div className={style["control-panel"]}>
                <div className={style["url-input"]}><input placeholder="Enter the URL ..." value={url} onChange={event => updateURL(event.target.value)} /></div>
                <div className={style["button-section"]}>
                    <div><Button variant="dark" onClick={() => perform_assessment()}>Perform Accessibility Test</Button></div>
                </div>
            </div>
        </div>
        <div className={style["results-section"]}>
        <div dangerouslySetInnerHTML={{ __html: assessmentResults }} />
        </div>
    </div>
</>)
}

export default Accessibility;
