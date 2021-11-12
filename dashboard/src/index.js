import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import "bootstrap/dist/css/bootstrap.min.css";
import App from "./App";
import reportWebVitals from "./reportWebVitals";
import Performance from "./../src/components/Performance";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import Header from "./components/Navbar";
import Accessibility from "./components/Accessibility/Accessibility";

ReactDOM.render(
  <React.StrictMode>
    <Router>
      <Header />
      <Switch>
        <Route exact path="/" component={App} />
        <Route path="/perfomancetesting" component={Performance} />
        <Route path="/accessibilitytesting" component={Accessibility} />
      </Switch>
    </Router>
  </React.StrictMode>,
  document.getElementById("root")
);

// ReactDOM.render(
//   <React.StrictMode>
//     <App />
//   </React.StrictMode>,
//   document.getElementById("root")
// );

reportWebVitals();
