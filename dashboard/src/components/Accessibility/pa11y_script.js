// const pa11y = require("pa11y");
// const fs = require('fs');

// // Function for saving the current report in the JSON format
// export const saveReport = (currentResults) => {

//   // Saving the current report for the future reference.
//   const currentResultsJSON_string = JSON.stringify(currentResults);
//   fs.writeFile('./Accessibility_Reports/previousResult.json', currentResultsJSON_string, 'utf-8', function (err) {

//     // If the current report could not be saved, then show the error(s) to the user and return
//     // the current results report, other show a message for successful save.
//     if (err) {
//       console.log("The current report couldn't be saved for future reference, due to some error !");
//       return false;
//     } else {
//       console.log("The current report have been saved succesfully for future reference !");
//       return true;
//     }
//   });

// }

// // Function for checking whether a given path exists or not
// const check_path = (path) => {
//   try {
//     return { pathname: require(`${path}`), result: true };
//   } catch (err) {
//     return { pathname: null, result: false };
//   }
// }

// // Function for deleting a given file.
// const deleteFile = (fileName) => {
//   fs.unlinkSync(fileName);
// }

// // Function to check whether a given issue exists in the previous report
// export const performIssueComparison = (currentResults) => {

//   // Creating a deep copy of the current results
//   const currentResultsDeepCopy = JSON.parse(JSON.stringify(currentResults))

//   const previousRunReport_JSON = check_path('./Accessibility_Reports/previousResult.json').pathname;
//   const previousRunReportIssuesArray = previousRunReport_JSON["issues"];
//   const currentReportIssuesComparison = currentResultsDeepCopy["issues"].map(currentIssue => {

//     // If an issue of the current report exists in the previous report,
//     // then mark that issue accordingly.
//     for (let i = 0; i < previousRunReportIssuesArray.length; i++) {
//       if (currentIssue.message === previousRunReportIssuesArray[i].message &&
//         currentIssue.context === previousRunReportIssuesArray[i].context &&
//         currentIssue.selector === previousRunReportIssuesArray[i].selector)
//         currentIssue["existedInPreviousReport"] = true;
//     }

//     if (!currentIssue.hasOwnProperty("existedInPreviousReport")) {
//       currentIssue["existedInPreviousReport"] = false;
//     }

//     return currentIssue;
//   });

//   return currentReportIssuesComparison;

// }

// // Function to compare the current report results with the previous report results
// export const compareResults = (currentResults) => {

//   const previousReportPath = check_path("./Accessibility_Reports/previousResult.json");

//   // If the results from the previous run exists, then perform the comparison
//   // and modify the current results accordingly to show the results of comparison
//   // on the current report.
//   if (fs.existsSync(previousReportPath.result)) {

//     // Performing the issue comparison of the current report with the previous report
//     const comparisonResults = performIssueComparison(currentResults);

//     // Deleting the previous report and saving the current report as the previous report
//     deleteFile(previousReportPath);
//     saveReport(currentResults);

//     return comparisonResults;

//   } else {

//     // Saving the current report for the future reference.
//     const save_action_outcome = saveReport(currentResults);

//     // Marking all the issues of the current report as non-existant, since there was no
//     // previous report for comparison.
//     const comparisonResults = currentResults["issues"].map(currentIssue => {
//       currentIssue["existedInPreviousReport"] = false;
//       return currentIssue;
//     });

//     // If the report was saved successfully, then return the result of the assessment currently
//     // performed.
//     if (save_action_outcome) {
//       return comparisonResults;
//     }
//     // If the report couldn't be saved successfully, then return false with for save action with
//     // the results of the current assessment performed.
//     else {
//       return comparisonResults;
//     }

//   }
// }

// // Function to Encode HTML as a string.
// export const htmlEncode = (html) => {
//   return html.replace(/[&"'\<\>]/g, function (c) {
//     switch (c) {
//       case "&":
//         return "&amp;";
//       case "'":
//         return "&#39;";
//       case '"':
//         return "&quot;";
//       case "<":
//         return "&lt;";
//       default:
//         return "&gt;";
//     }
//   });
// };

// // Function to format report in the HTML format
// export const formatReportInHTMLformat = (comparisonResults) => {

//   const previousReportInHTMLformatPath = "./Accessibility_Reports/index.html";

//   // Deleting any pre-existing report in the HTML format
//   if (fs.existsSync(previousReportInHTMLformatPath)) {
//     deleteFile(previousReportInHTMLformatPath);
//   }

//   const HTMLformattedReportArray = comparisonResults.map((currentIssue, index) => {
//     return '<div style="margin: 1% 15%; border-style:solid; border-width: 2px; padding: 4px; border-radius: 10px; background-color: #fdd; border-color: #ff9696;">' +
//       '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Error ID:</b> ' + index + ' </h4>' +
//       '<h4 style="margin: 5px 0px; font-weight: normal;"><b>Error Message:</b> ' + currentIssue['message'] + '</h4>' +
//       '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Error Code:</b> ' + currentIssue['code'] + '</h4>' +
//       '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Error Context:</b> <code>' + htmlEncode(currentIssue['context']) + '</code> ' + ' <b>[(select with ' + currentIssue['selector'] + ')]</b> </h4>' +
//       '<h4 style="margin: 5px 0px; font-weight: normal;"> <b>Existed In Previous Report</b>: ' + currentIssue['existedInPreviousReport'] + '</h4>' + '</div>';
//   });

//   const HTMLformattedReport = '<h1 style="text-align:center; font-family: Arial, Helvetica, sans-serif;"> [<u>solferinoacademy.com</u>] Website Accessibility Report </h1>' +
//     '<div style="margin-left:15%; margin-right:15%; width: max-content; background-color: #fdd; border-color: #ff9696; padding: 4px; border-style:solid; border-width: 2px; border-radius: 5px;"><b>Total number of errors:</b> ' + HTMLformattedReportArray.length + ' </div>' +
//     HTMLformattedReportArray.join('');

//   // Saving the current report in the HTML format.
//   fs.writeFileSync(previousReportInHTMLformatPath, HTMLformattedReport);

//   return HTMLformattedReport;

// }

// export const runPa11y = () => {

//   const technicalErrorCodeList_A_standard = [
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F3',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F13',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F320',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F30',
//     'WCAG2A.Principle1.Guideline1_1.1_1_1.H67.1',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F38',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F39',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F65',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F67',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F71',
//     'WCAG2A.Principle4.Guideline1_1.1_1_1.F72',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F2',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F33',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F34',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F42',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F43',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F46',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F48',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F87',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F90',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F91',
//     'WCAG2A.Principle4.Guideline1_3.1_3_1.F92',
//     'WCAG2A.Principle4.Guideline1_3.1_3_2.F34',
//     'WCAG2A.Principle4.Guideline1_3.1_3_2.F33',
//     'WCAG2A.Principle4.Guideline1_3.1_3_2.F32',
//     'WCAG2A.Principle4.Guideline1_3.1_3_2.F49',
//     'WCAG2A.Principle4.Guideline1_3.1_3_2.F1',
//     'WCAG2A.Principle4.Guideline1_4.1_4_2.F93',
//     'WCAG2A.Principle4.Guideline2_1.2_1_1.F54',
//     'WCAG2A.Principle4.Guideline2_1.2_1_1.F55',
//     'WCAG2A.Principle4.Guideline2_1.2_1_1.F42',
//     'WCAG2A.Principle4.Guideline2_1.2_1_2.F10',
//     'WCAG2A.Principle4.Guideline2_2.2_2_1.F40',
//     'WCAG2A.Principle4.Guideline2_2.2_2_1.F41',
//     'WCAG2A.Principle4.Guideline2_2.2_2_1.F58',
//     'WCAG2A.Principle4.Guideline2_2.2_2_2.F47',
//     'WCAG2A.Principle4.Guideline2_2.2_2_2.F4',
//     'WCAG2A.Principle4.Guideline2_2.2_2_2.F7',
//     'WCAG2A.Principle4.Guideline4_1.4_1_1.F70',
//     'WCAG2A.Principle4.Guideline4_1.4_1_1.F77',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F59',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F15',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F20',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F68',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F79',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F86',
//     'WCAG2A.Principle4.Guideline4_1.4_1_2.F89',
//   ];

//   const technicalErrorCodeList_AA_standard = [
//     'WCAG2AA.Principle4.Guideline4_1.4_1_1.F77',
//     'WCAG2AA.Principle1.Guideline1_1.1_1_1.H67.1'
//   ];

//   const URL = 'https://solferinoacademy.com/';
//   const accessibilityTestStandard = 'WCAG2AA';

//   try {
//     pa11y(URL, {
//       standard: accessibilityTestStandard,
//       ignore: technicalErrorCodeList_A_standard.concat(technicalErrorCodeList_AA_standard)
//     }).then(results => {
//       const comparedReport = compareResults(results);
//       return formatReportInHTMLformat(comparedReport);
//     });
//   } catch (error) {
//     console.log("The accessiblity test couldn't be performed due to some error.");
//   }
// }

// runPa11y();
