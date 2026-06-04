/**
 * Import function triggers from their respective submodules:
 *
 * const {onCall} = require("firebase-functions/v2/https");
 * const {onDocumentWritten} = require("firebase-functions/v2/firestore");
 *
 * See a full list of supported triggers at https://firebase.google.com/docs/functions
 */

//const {setGlobalOptions} = require("firebase-functions");
//const {onRequest} = require("firebase-functions/https");
//const logger = require("firebase-functions/logger");

// For cost control, you can set the maximum number of containers that can be
// running at the same time. This helps mitigate the impact of unexpected
// traffic spikes by instead downgrading performance. This limit is a
// per-function limit. You can override the limit for each function using the
// `maxInstances` option in the function's options, e.g.
// `onRequest({ maxInstances: 5 }, (req, res) => { ... })`.
// NOTE: setGlobalOptions does not apply to functions using the v1 API. V1
// functions should each use functions.runWith({ maxInstances: 10 }) instead.
// In the v1 API, each function can only serve one request per container, so
// this will be the maximum concurrent request count.
//setGlobalOptions({ maxInstances: 2 });

// Create and deploy your first functions
// https://firebase.google.com/docs/functions/get-started

// exports.helloWorld = onRequest((request, response) => {
//   logger.info("Hello logs!", {structuredData: true});
//   response.send("Hello from Firebase!");
// });


// My backend for the CSE2201 project

// Using node and then express and joi for validattion


// ### declaring my imports 
const {onRequest} = require("firebase-functions/v2/https");
const express = require("express");
const cors = require("cors");

// declaring the express app
const app = express();

// setting up cors and initalizing exress
app.use(cors({ origin: true }));
app.use(express.json());

// just a simple health check end point
app.get("/api/health", (req, res) => {
  res.status(200).json({
    success: true,
    status: "ready",
    message: "Campus Events API is running",
    timestamp: new Date().toISOString(),
  });
});



// passing the express app over to a firebase function caller
exports.api = onRequest(app);

