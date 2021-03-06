import axios from 'axios';

const api = 'http://localhost:5000';

class CallApi {
	
	runPerformanceTest(body) {
        return new Promise((resolve) => resolve(axios.post(`${api}/performance`, body)));
    }

    runAccessibiliyTest(body) {
        return new Promise((resolve) => resolve(axios.post(`${api}/accessibility`, body)))
    }
}

export default new CallApi();
