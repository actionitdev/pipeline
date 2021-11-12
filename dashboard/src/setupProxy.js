// Use http-proxy-middleware to solve the CORS problem
const { createProxyMiddleware } = require("http-proxy-middleware");
module.exports = function (app) {
  app.use(
    createProxyMiddleware("/api/v1.1", {
      target: "https://circleci.com",
      changeOrigin: true,
    })
  );

  app.use(
    createProxyMiddleware("/api/v2", {
      target: "https://circleci.com",
      changeOrigin: true,
    })
  );
};
