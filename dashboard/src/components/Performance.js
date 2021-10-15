import React, {useState} from 'react';
import style from "./Performance.css";
import CallApi from './CallApi';
import Button from "react-bootstrap/button";

export default function Performance() {
    const [content, setContent] = useState("");
    const [textInput, setTextInput] = useState("");

    
    function handlePerformance () {
        CallApi.runPerformanceTest({"url":textInput})
        .then(response => {
            console.log(response.data)
			setContent(response.data)
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
                        <pre>{content}</pre>
                    </div>
                </div>
                
            </>
    );
}
