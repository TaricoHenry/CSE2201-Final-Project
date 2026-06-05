// My backend for the CSE2201 project

//written by Tarico Henry
// architecture
// firebase functions functions serve a node/express.js api that handles communticaation between html front end and firebase database

// express app is wrapped and passed to a firebase funciton

// database is hosed in google firestore
// firestore is an object/document database so it schemaless and we did not have to define a schema


// ### declaring my imports
const {onRequest} = require("firebase-functions/v2/https");
const admin = require("firebase-admin");
const {FieldValue} = require("firebase-admin/firestore");
const express = require("express");
const cors = require("cors");
const Joi = require("joi");

// spinning up firbase admin
admin.initializeApp();

// setting up my object db in firestore
const db = admin.firestore();

//setting timestamp var that will be used to store a timestamp on any api rq
const serverTimestamp = FieldValue.serverTimestamp;

// declaring the express app
const app = express();

// setting up cors and initalizing exress
app.use(cors({origin: true}));
app.use(express.json());

// creating one router for all /api/v1 routes
const apiRouter = new express.Router();

// using joi to run input validation
// joi is just an npm package that makes express api input validation
// super simple nothing fancy
const rsvpSchema = Joi.object({
  eventId: Joi.string().trim().required(),
  fullName: Joi.string().trim().required(),
  studentId: Joi.string().trim().required(),
  email: Joi.string().trim().email().required(),
  attendanceStatus: Joi.string().valid("attending", "maybe").required(),
  programme: Joi.string().trim().allow("").optional(),
  comment: Joi.string().trim().allow("").optional(),
});

// validating new event rq with joi
const eventRequestSchema = Joi.object({
  organizerName: Joi.string().trim().required(),
  organizerEmail: Joi.string().trim().email().required(),
  eventTitle: Joi.string().trim().required(),
  category: Joi.string().trim().required(),
  proposedDate: Joi.string().trim().required(),
  proposedTime: Joi.string().trim().required(),
  venue: Joi.string().trim().required(),
  description: Joi.string().trim().required(),
  expectedAudience: Joi.string().trim().allow("").optional(),
  posterLink: Joi.string().trim().allow("").optional(),
});

// validating rq for updating an event
const eventRequestStatusSchema = Joi.object({
  status: Joi.string().valid("pending", "approved", "rejected").required(),
});

// just a simple health check end point
apiRouter.get("/health", (req, res) => {
  res.status(200).json({
    success: true,
    status: "ready",
    message: "Campus Events API is running",
    timestamp: new Date().toISOString(),
  });
});

// GET /api/v1/events
// Grab all approved campus events from Firestore (object DB collection)
apiRouter.get("/events", async (req, res) => {
  try {

    //firbase nomenclature
    // essentially you take a snapshot of the db 
    // each group of documents is a collection and we reqeust a snapshot of the collection when we query the db
    //SQL equivalent is basically SELECT * (note it will not select the id we have to add that in spearate) FROM events where status ="approved"
    const eventsSnapshot = await db
        .collection("events")
        .where("status", "==", "approved")
        .get();

    //store each event in an arry
    const events = [];

    // add the id for each document from the id
    eventsSnapshot.forEach((eventDoc) => {
      events.push({
        id: eventDoc.id,
        ...eventDoc.data(),
      });
    });

    //rtrn 200 response
    res.status(200).json({
      success: true,
      count: events.length,
      data: events,
    });
  } 
  
  //catch any and all errors and throw 500
  catch (error) {
    console.error("Error fetching approved events:", error);

    res.status(500).json({
      success: false,
      message: "Failed to fetch events",
    });
  }
});

// GET /api/v1/events/:id
// Grab a particular event by the document ID in firestore
// this route is really straight forward, just query by id and return the event details
// throw 404 if no event found and 500 on any and all errors
apiRouter.get("/events/:id", async (req, res) => {
  try {
    const eventId = req.params.id;
    const eventDoc = await db.collection("events").doc(eventId).get();

    if (!eventDoc.exists) {
      return res.status(404).json({
        success: false,
        message: "Event not found",
      });
    }

    res.status(200).json({
      success: true,
      data: {
        id: eventDoc.id,
        ...eventDoc.data(),
      },
    });
  } catch (error) {
    console.error("Error fetching event:", error);

    res.status(500).json({
      success: false,
      message: "Failed to fetch event",
    });
  }
});

// POST /api/v1/rsvps
// accept, validate and then store RSVP submission.
// essentially creating a new doucment record in my document database
// rtrn 201 is created 400 if validation failed to create and 500 for anything else
apiRouter.post("/rsvps", async (req, res) => {
  try {
    const validationResult = rsvpSchema.validate(req.body, {
      abortEarly: false,
      stripUnknown: true,
    });

    if (validationResult.error) {
      const errors = validationResult.error.details.map((detail) => {
        return detail.message;
      });

      return res.status(400).json({
        success: false,
        message: "Validation failed",
        errors: errors,
      });
    }

    const rsvpData = {
      ...validationResult.value,
      submittedAt: serverTimestamp(),
    };

    const newRsvp = await db.collection("rsvps").add(rsvpData);

    res.status(201).json({
      success: true,
      message: "RSVP submitted successfully",
      id: newRsvp.id,
    });
  } catch (error) {
    console.error("Error saving RSVP:", error);

    res.status(500).json({
      success: false,
      message: "Failed to submit RSVP",
    });
  }
});

// POST /api/v1/event-requests
// accept, validate and then store a new event request for admin review.
// essentially creating a new doucment record in my document database
// rtrn 201 is created 400 if validation failed to create and 500 for anything else
apiRouter.post("/event-requests", async (req, res) => {
  try {
    const validationResult = eventRequestSchema.validate(req.body, {
      abortEarly: false,
      stripUnknown: true,
    });

    if (validationResult.error) {
      const errors = validationResult.error.details.map((detail) => {
        return detail.message;
      });

      return res.status(400).json({
        success: false,
        message: "Validation failed",
        errors: errors,
      });
    }

    const eventRequestData = {
      ...validationResult.value,
      status: "pending",
      submittedAt: serverTimestamp(),
      reviewedAt: null,
    };

    const newEventRequest = await db
        .collection("eventRequests")
        .add(eventRequestData);

    res.status(201).json({
      success: true,
      message: "Event request submitted successfully",
      id: newEventRequest.id,
    });
  } catch (error) {
    console.error("Error saving event request:", error);

    res.status(500).json({
      success: false,
      message: "Failed to submit event request",
    });
  }
});

// GET /api/v1/admin/rsvps
// Grab all RSVP submissions for the admin backend page
// Essentially doing a SELECT * FROM rsvps
// return 200 if goot and 500 for any and all errors
apiRouter.get("/admin/rsvps", async (req, res) => {
  try {
    const rsvpsSnapshot = await db.collection("rsvps").get();
    const rsvps = [];

    rsvpsSnapshot.forEach((rsvpDoc) => {
      rsvps.push({
        id: rsvpDoc.id,
        ...rsvpDoc.data(),
      });
    });

    res.status(200).json({
      success: true,
      count: rsvps.length,
      data: rsvps,
    });
  } catch (error) {
    console.error("Error fetching RSVPs:", error);

    res.status(500).json({
      success: false,
      message: "Failed to fetch RSVPs",
    });
  }
});

// GET /api/v1/admin/event-requests
// Grab all event listing requests for the admin backend page
// Essentially doing a SELECT * FROM eventRequests
// return 200 if goot and 500 for any and all errors
apiRouter.get("/admin/event-requests", async (req, res) => {
  try {
    const eventRequestsSnapshot = await db.collection("eventRequests").get();
    const eventRequests = [];

    eventRequestsSnapshot.forEach((eventRequestDoc) => {
      eventRequests.push({
        id: eventRequestDoc.id,
        ...eventRequestDoc.data(),
      });
    });

    res.status(200).json({
      success: true,
      count: eventRequests.length,
      data: eventRequests,
    });
  } catch (error) {
    console.error("Error fetching event requests:", error);

    res.status(500).json({
      success: false,
      message: "Failed to fetch event requests",
    });
  }
});

// PATCH /api/v1/admin/event-requests/:id
// Update an event request status and mark when it was reviewed.
// Essentially doing a SELECT * FROM eventRequests where Id = :id then run an Update on the found record with the new status
// return 200 if gooc, 400 is validation failed and 500 for any and all errors
apiRouter.patch("/admin/event-requests/:id", async (req, res) => {
  try {
    const validationResult = eventRequestStatusSchema.validate(req.body, {
      abortEarly: false,
      stripUnknown: true,
    });

    if (validationResult.error) {
      const errors = validationResult.error.details.map((detail) => {
        return detail.message;
      });

      return res.status(400).json({
        success: false,
        message: "Validation failed",
        errors: errors,
      });
    }

    const eventRequestId = req.params.id;
    const eventRequestRef = db
        .collection("eventRequests")
        .doc(eventRequestId);
    const eventRequestDoc = await eventRequestRef.get();

    if (!eventRequestDoc.exists) {
      return res.status(404).json({
        success: false,
        message: "Event request not found",
      });
    }

    await eventRequestRef.update({
      status: validationResult.value.status,
      reviewedAt: serverTimestamp(),
    });

    res.status(200).json({
      success: true,
      message: "Event request updated successfully",
    });
  } catch (error) {
    console.error("Error updating event request:", error);

    res.status(500).json({
      success: false,
      message: "Failed to update event request",
    });
  }
});

// sticking with the industry standard of api/v1
app.use("/api/v1", apiRouter);

// passing the express app over to a firebase function caller
exports.api = onRequest(app);
