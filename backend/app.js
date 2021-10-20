var express = require('express');
var cors = require('cors');

const lighthouse = require("lighthouse");
const chromeLauncher = require("chrome-launcher");
const argv = require("yargs").argv;
const url = require("url");
const fs = require("fs");
const glob = require("glob");
const path = require("path");

var app = express();
app.use(express.static("public"));

const DEVTOOLS_RTT_ADJUSTMENT_FACTOR = 3.75;
const DEVTOOLS_THROUGHPUT_ADJUSTMENT_FACTOR = 0.9;

const throttling = {
  DEVTOOLS_RTT_ADJUSTMENT_FACTOR,
  DEVTOOLS_THROUGHPUT_ADJUSTMENT_FACTOR,
  // These values align with WebPageTest's definition of "Fast 3G"
  // But offer similar charateristics to roughly the 75th percentile of 4G connections.
  mobileSlow4G: {
    rttMs: 150,
    throughputKbps: 1.6 * 1024,
    requestLatencyMs: 150 * DEVTOOLS_RTT_ADJUSTMENT_FACTOR,
    downloadThroughputKbps: 1.6 * 1024 * DEVTOOLS_THROUGHPUT_ADJUSTMENT_FACTOR,
    uploadThroughputKbps: 750 * DEVTOOLS_THROUGHPUT_ADJUSTMENT_FACTOR,
    cpuSlowdownMultiplier: 4,
  },
  // These values partially align with WebPageTest's definition of "Regular 3G".
  // These values are meant to roughly align with Chrome UX report's 3G definition which are based
  // on HTTP RTT of 300-1400ms and downlink throughput of <700kbps.
  mobileRegular3G: {
    rttMs: 300,
    throughputKbps: 700,
    requestLatencyMs: 300 * DEVTOOLS_RTT_ADJUSTMENT_FACTOR,
    downloadThroughputKbps: 700 * DEVTOOLS_THROUGHPUT_ADJUSTMENT_FACTOR,
    uploadThroughputKbps: 700 * DEVTOOLS_THROUGHPUT_ADJUSTMENT_FACTOR,
    cpuSlowdownMultiplier: 4,
  },
  // Using a "broadband" connection type
  // Corresponds to "Dense 4G 25th percentile" in https://docs.google.com/document/d/1Ft1Bnq9-t4jK5egLSOc28IL4TvR-Tt0se_1faTA4KTY/edit#heading=h.bb7nfy2x9e5v
  desktopDense4G: {
    rttMs: 40,
    throughputKbps: 10 * 1024,
    cpuSlowdownMultiplier: 1,
    requestLatencyMs: 0, // 0 means unset
    downloadThroughputKbps: 0,
    uploadThroughputKbps: 0,
  },
};



const launchChromeAndRunLighthouse = url => {
  return chromeLauncher.launch({chromeFlags: ['--headless']}).then(chrome => {
    const opts = {
      port: chrome.port,
	  throttlingMethod: 'provided',
      throttling: throttling.mobileRegular3G,
	  formFactor: 'desktop',
	  screenEmulation: {
		mobile: false,
		width: 1350,
		height: 940,
		deviceScaleFactor: 1,
		disabled: false
	  },
	  output: ['json','html'], 
	  onlyCategories: ['performance']
    };
    return lighthouse(url, opts).then(results => {
      return chrome.kill().then(() => {
        return {
          js: results.lhr,
          json: results.report[0],
		  html: results.report[1]
        };
      });
    });
  });
};

const getContents = pathStr => {
  const output = fs.readFileSync(pathStr, "utf8", (err, results) => {
    return results;
  });
  return JSON.parse(output);
};





var corsOptions = {
    credentials:true,
    origin:'http://localhost:3000',
    optionsSuccessStatus:200
};
app.use(cors(corsOptions));

app.use(express.urlencoded({extended: true})); 
app.use(express.json()); 

app.get('/', function (req, res) {
    res.send('Request Accepted')
});

app.post('/performance', function (req, res) {
    let targetUrl = req.body.url
	let comparisonResult = ""
	
	
    const compareReports = (from, to) => {
  var compareOutput = "";
  compareOutput += "Metric".padEnd(35, ' ');
  compareOutput += "Previous Report".padEnd(35, ' ');
  compareOutput += "Current Report".padEnd(35, ' ');
  compareOutput += "Comparison Result".padEnd(35, ' ');
  compareOutput += "\n"
  const metricFilter = [
    "first-contentful-paint",
    "first-meaningful-paint",
    "speed-index",
    "estimated-input-latency",
    "total-blocking-time",
    "max-potential-fid",
    "time-to-first-byte",
    "first-cpu-idle",
    "interactive"
  ];

  const calcPercentageDiff = (from, to) => {
    const per = ((to - from) / from) * 100;
    return Math.round(per * 100) / 100;
  };

  for (let auditObj in from["audits"]) {
    if (metricFilter.includes(auditObj)) {
      const percentageDiff = calcPercentageDiff(
        from["audits"][auditObj].numericValue,
        to["audits"][auditObj].numericValue
      );

      let logColor = "\x1b[37m";
      const log = (() => {
        if (Math.sign(percentageDiff) === 1) {
          logColor = "\x1b[31m";
          return `${percentageDiff.toString().replace("-", "") + "%"} slower`;
        } else if (Math.sign(percentageDiff) === 0) {
          return "unchanged";
        } else {
          logColor = "\x1b[32m";
          return `${percentageDiff.toString().replace("-", "") + "%"} faster`;
        }
      })();
      console.log(logColor, `${from["audits"][auditObj].title} is ${log}`);
	  

	  let previousResult = Number(`${from["audits"][auditObj].numericValue}`).toFixed(3);
	  let currentResult = Number(`${to["audits"][auditObj].numericValue}`).toFixed(3);
	  
	  compareOutput += `${from["audits"][auditObj].title}`.padEnd(35, ' ');
	  compareOutput += `${previousResult}ms`.padEnd(35, ' ');
	  compareOutput += `${currentResult}ms`.padEnd(35, ' ');
	  compareOutput += `${log}`.padEnd(35, ' ');
	  compareOutput += '\n'

    }
  };
  
  	fs.writeFile(
      `compareReport/${from["fetchTime"].replace(/:/g, "_")}.txt`,
      compareOutput,
      err => {
        if (err) throw err;
      }
    );
	
	comparisonResult = compareOutput
      
  
};
    
	const urlObj = new URL(targetUrl);
  let dirName = urlObj.host.replace("www.", "");
  if (urlObj.pathname !== "/") {
    dirName = dirName + urlObj.pathname.replace(/\//g, "_");
  }

  if (!fs.existsSync("public/"+dirName)) {
    fs.mkdirSync("public/" + dirName);
  }

  launchChromeAndRunLighthouse(targetUrl).then(results => {
    const prevReports = glob(`public/${dirName}/*.json`, {
      sync: true
    });

    if (prevReports.length) {
      dates = [];
      for (report in prevReports) {
        dates.push(
          new Date(path.parse(prevReports[report]).name.replace(/_/g, ":"))
        );
      }
      const max = dates.reduce(function(a, b) {
        return Math.max(a, b);
      });
      const recentReport = new Date(max).toISOString();

      const recentReportContents = getContents(
        "public/" + dirName + "/" + recentReport.replace(/:/g, "_") + ".json"
      );

      compareReports(recentReportContents, results.js);
    }

    fs.writeFile(
      `public/${dirName}/${results.js["fetchTime"].replace(/:/g, "_")}.json`,
      results.json,
      err => {
        if (err) throw err;
      }
    );
	fs.writeFile(
      `public/${dirName}/${results.js["fetchTime"].replace(/:/g, "_")}.html`,
      results.html,
      err => {
        if (err) throw err;
      }
    );
	res.send({directory:`${dirName}/${results.js["fetchTime"].replace(/:/g, "_")}.html`, compareTxt:comparisonResult})
	 
  });
	
   
});

app.use(express.static(__dirname + 'Accessibility_Reports')); //Serves resources from public folder

const pa11y = require("pa11y");
// const fs = require('fs');

// Function for saving the current report in the JSON format
function saveReport(currentResults) {

  // Saving the current report for the future reference.
  const currentResultsJSON_string = JSON.stringify(currentResults);
  fs.writeFile('./Accessibility_Reports/previousResult.json', currentResultsJSON_string, 'utf-8', function (err) {

    // If the current report could not be saved, then show the error(s) to the user and return
    // the current results report, other show a message for successful save.
    if (err) {
      console.log("The current report couldn't be saved for future reference, due to some error !");
    } else {
      console.log("The current report have been saved succesfully for future reference !");
    }
  });

}

// Function for deleting a given file.
function deleteFile(fileName) {
  fs.unlinkSync(fileName);
}

// Function to check whether a given issue exists in the previous report
function performIssueComparison(currentResults) {

  // Creating a deep copy of the current results
  const currentResultsDeepCopy = JSON.parse(JSON.stringify(currentResults))

  const previousRunReport_JSON = require('./Accessibility_Reports/previousResult.json');
  const previousRunReportIssuesArray = previousRunReport_JSON["issues"];
  const currentReportIssuesComparison = currentResultsDeepCopy["issues"].map(currentIssue => {

    // If an issue of the current report exists in the previous report,
    // then mark that issue accordingly.
    for (let i = 0; i < previousRunReportIssuesArray.length; i++) {
      if (currentIssue.message == previousRunReportIssuesArray[i].message &&
        currentIssue.context == previousRunReportIssuesArray[i].context &&
        currentIssue.selector == previousRunReportIssuesArray[i].selector)
        currentIssue["existedInPreviousReport"] = true;
    }

    if (!currentIssue.hasOwnProperty("existedInPreviousReport")) {
      currentIssue["existedInPreviousReport"] = false;
    }

    return currentIssue;
  });

  return currentReportIssuesComparison;

}

// Function to compare the current report results with the previous report results
function compareResults(currentResults) {

  const previousReportPath = "./Accessibility_Reports/previousResult.json";

  // If the results from the previous run exists, then perform the comparison
  // and modify the current results accordingly to show the results of comparison
  // on the current report.
  if (fs.existsSync(previousReportPath)) {

    // Performing the issue comparison of the current report with the previous report
    const comparisonResults = performIssueComparison(currentResults);

    // Deleting the previous report and saving the current report as the previous report
    deleteFile(previousReportPath);
    saveReport(currentResults);

    return comparisonResults;

  } else {

    // Saving the current report for the future reference.
    saveReport(currentResults);

    // Marking all the issues of the current report as non-existant, since there was no
    // previous report for comparison.
    const comparisonResults = currentResults["issues"].map(currentIssue => {
      currentIssue["existedInPreviousReport"] = false;
      return currentIssue;
    });

    return comparisonResults;

  }
}

// Function to Encode HTML as a string.
function htmlEncode(html) {
  return html.replace(/[&"'\<\>]/g, function (c) {
    switch (c) {
      case "&":
        return "&amp;";
      case "'":
        return "&#39;";
      case '"':
        return "&quot;";
      case "<":
        return "&lt;";
      default:
        return "&gt;";
    }
  });
};

// Function to format report in the HTML format
function formatReportInHTMLformat(comparisonResults) {

  const previousReportInHTMLformatPath = "./Accessibility_Reports/index.html";

  // Deleting any pre-existing report in the HTML format
  if (fs.existsSync(previousReportInHTMLformatPath)) {
    deleteFile(previousReportInHTMLformatPath);
  }

  const HTMLformattedReportArray = comparisonResults.map((currentIssue, index) => {
    return outputString = '<div style="margin: 1% 15%; border-style:solid; border-width: 2px; padding: 4px; border-radius: 10px; background-color: #fdd; border-color: #ff9696;">' +
      '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Error ID:</b> ' + index + ' </h4>' +
      '<h4 style="margin: 5px 0px; font-weight: normal;"><b>Error Message:</b> ' + currentIssue['message'] + '</h4>' +
      '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Error Code:</b> ' + currentIssue['code'] + '</h4>' +
      '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Error Context:</b> <code>' + htmlEncode(currentIssue['context']) + '</code> ' + ' <b>[(select with ' + currentIssue['selector'] + ')]</b> </h4>' +
      '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Existed In Previous Report</b>: ' + currentIssue['existedInPreviousReport'] + '</h4>' + '</div>';
  });

  const HTMLformattedReport = '<h1 style="text-align:center; font-family: Arial, Helvetica, sans-serif;"> [<u>solferinoacademy.com</u>] Website Accessibility Report </h1>' +
    '<div style="margin-left:15%; margin-right:15%; width: max-content; background-color: #fdd; border-color: #ff9696; padding: 4px; border-style:solid; border-width: 2px; border-radius: 5px;"><b>Total number of errors:</b> ' + HTMLformattedReportArray.length + ' </div>' +
    HTMLformattedReportArray.join('');
  fs.writeFileSync(previousReportInHTMLformatPath, HTMLformattedReport);

}

async function runPa11y() {

  const technicalErrorCodeList_A_standard = [
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F3',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F13',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F320',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F30',
    'WCAG2A.Principle1.Guideline1_1.1_1_1.H67.1',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F38',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F39',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F65',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F67',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F71',
    'WCAG2A.Principle4.Guideline1_1.1_1_1.F72',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F2',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F33',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F34',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F42',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F43',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F46',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F48',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F87',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F90',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F91',
    'WCAG2A.Principle4.Guideline1_3.1_3_1.F92',
    'WCAG2A.Principle4.Guideline1_3.1_3_2.F34',
    'WCAG2A.Principle4.Guideline1_3.1_3_2.F33',
    'WCAG2A.Principle4.Guideline1_3.1_3_2.F32',
    'WCAG2A.Principle4.Guideline1_3.1_3_2.F49',
    'WCAG2A.Principle4.Guideline1_3.1_3_2.F1',
    'WCAG2A.Principle4.Guideline1_4.1_4_2.F93',
    'WCAG2A.Principle4.Guideline2_1.2_1_1.F54',
    'WCAG2A.Principle4.Guideline2_1.2_1_1.F55',
    'WCAG2A.Principle4.Guideline2_1.2_1_1.F42',
    'WCAG2A.Principle4.Guideline2_1.2_1_2.F10',
    'WCAG2A.Principle4.Guideline2_2.2_2_1.F40',
    'WCAG2A.Principle4.Guideline2_2.2_2_1.F41',
    'WCAG2A.Principle4.Guideline2_2.2_2_1.F58',
    'WCAG2A.Principle4.Guideline2_2.2_2_2.F47',
    'WCAG2A.Principle4.Guideline2_2.2_2_2.F4',
    'WCAG2A.Principle4.Guideline2_2.2_2_2.F7',
    'WCAG2A.Principle4.Guideline4_1.4_1_1.F70',
    'WCAG2A.Principle4.Guideline4_1.4_1_1.F77',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F59',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F15',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F20',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F68',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F79',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F86',
    'WCAG2A.Principle4.Guideline4_1.4_1_2.F89',
  ];

  const technicalErrorCodeList_AA_standard = [
    'WCAG2AA.Principle4.Guideline4_1.4_1_1.F77',
    'WCAG2AA.Principle1.Guideline1_1.1_1_1.H67.1'
  ];

  const URL = 'https://solferinoacademy.com/';
  const accessibilityTestStandard = 'WCAG2AA';

  try {
    await pa11y(URL, {
      standard: accessibilityTestStandard,
      ignore: technicalErrorCodeList_A_standard.concat(technicalErrorCodeList_AA_standard)
    }).then(results => {
      const comparedReport = compareResults(results);
      const HTMLformatedReport = formatReportInHTMLformat(comparedReport);
      return HTMLformatedReport;
    });
  } catch (error) {
    console.log("The accessiblity test couldn't be performed due to some error.");
  }
}


app.post('/accessibility', async (req, res) => {

  const st = await runPa11y();
  // console.log(st)

  res.sendFile(__dirname + '/Accessibility_Reports/index.html');
  // res.send(st);
});

app.listen(5000, function() {
    console.log('App listening on port 5000...')
});
