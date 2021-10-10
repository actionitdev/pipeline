import React, {useState} from 'react';
import CallApi from './CallApi';

export default function Performance() {
    const [content, setContent] = useState("");
    
    function handlePerformance () {
        CallApi.runPerformanceTest({"url":"https://solferinoacademy.com/"})
        .then(response => {
            console.log(response.data)
			setContent(response.data)
        })
    }


    return (
            <>
                <h3>{ content }</h3>
                <div>
                    <button onClick={ handlePerformance }>Performance test</button>
                </div>
            </>
    );
}