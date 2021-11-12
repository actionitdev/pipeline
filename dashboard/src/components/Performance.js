import React, {useState} from 'react';
import style from "./Performance.css";
import CallApi from './CallApi';
import Button from "react-bootstrap/button";

export default function Performance() {
    const [content, setContent] = useState("");
    const [textInput, setTextInput] = useState("");
	const [comparisonContent, setComparisonContent] = useState("")

    
    function handlePerformance () {
	setComparisonContent("Test running")
        CallApi.runPerformanceTest({"url":textInput})
        .then(response => {
            console.log(response.data)
			setContent("http://localhost:5000/" + response.data.directory)
			setComparisonContent(response.data.compareTxt)
        })
    }
    
    const handleChange = (event) => {
        setTextInput(event.target.value);
    }


    return (
            <>
                 <div class = "main-div">
                    <div class = "navbar-div">
                        <div><h4>Performance Assessment</h4></div>
                        <div class = "control-panel">
                            <div class = "url-input"><input onChange={handleChange} placeholder="Type a target url..." /></div>
                            <div class = "button-section">
                                <div><Button variant="dark" onClick={ handlePerformance }>Performance test</Button></div>
                                
                            </div>
                        </div>
                    </div>
                    <div className={style["results-section"]}>
						<a href = {content} target="_blank">View the performance test result</a>
						<br/>
						<h5>Compare to previous Test</h5>
                        <pre>{comparisonContent}</pre>
                    </div>
                </div>
                
            </>
    );
}
